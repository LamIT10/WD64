<?php

namespace App\Repositories;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Receiving;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodReceiptRepository extends BaseRepository
{
    protected $productRepository;
    public function __construct(GoodReceipt $model)
    {
        $this->handleModel = $model;
    }
    public function getList($request)
    {
        $query = $this->handleModel->with([
            'purchaseOrder' => function ($query) {
                $query->select(['id', 'supplier_id', 'user_id', 'order_status']);
            },
            'purchaseOrder.supplier' => function ($query) {
                $query->select(['id', 'name']);
            },
            'createBy' => function ($query) {
                $query->select(['id', 'name']);
            },
            'items' => function ($query) {
                $query->select(['id', 'good_receipt_id', 'product_variant_id', 'quantity_received', 'unit_price', 'unit_id', 'subtotal']);
            },
            'items.productVariant' => function ($query) {
                $query->select(['id', 'product_id']);
            },
            'items.productVariant.product' => function ($query) {
                $query->select(['id', 'name', 'default_unit_id']);
            },
            'items.productVariant.attributes' => function ($query) {
                $query->select(['*']);
            },
            'items.unit' => function ($query) {
                $query->select(['id', 'name']);
            },
        ]);
        $query->orderBy('created_at', 'desc');
        $list = $query->get();
        return $list;
    }
    public function getByPurchaseOrder($id)
    {
        $query = PurchaseOrder::with([
            'supplier' => function ($query) {
                $query->select(['id', 'name']);
            },
            'user' => function ($query) {
                $query->select(['id', 'name']);
            },
            'items' => function ($query) {
                $query->select(['id', 'purchase_order_id', 'product_variant_id', 'quantity_ordered', 'quantity_received', 'unit_price', 'unit_id', 'subtotal']);
            },
            'items.productVariant' => function ($query) {
                $query->select(['id', 'product_id']);
            },
            'items.productVariant.product' => function ($query) {
                $query->select(['id', 'name', 'default_unit_id']);
            },
            'items.productVariant.attributes' => function ($query) {
                $query->select(['*']);
            },
            'items.unit' => function ($query) {
                $query->select(['id', 'name']);
            },
        ]);
        $query->where('id', $id);
        $purchaseOrder = $query->first();
        return $purchaseOrder;
    }
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $newGoodReceipt = [];
            $newGoodReceipt['purchase_order_id'] = $data['purchase_order_id'];
            $newGoodReceipt['note'] = $data['note'] ?? '';
            $newGoodReceipt['code'] = $this->autoAddDealCode();
            $newGoodReceipt['receipt_date'] = Carbon::now();
            $newGoodReceipt['created_by'] = Auth::id();
            $newGoodReceipt['total_amount'] = $data['total_amount'] ?? 0;
            $goodReceipt = $this->handleModel->create($newGoodReceipt);

            $purchaseOrder = PurchaseOrder::find($data['purchase_order_id']);
            if ($purchaseOrder) {
                $orderUpdate = [];
                $orderUpdate['order_status'] = ($data['receive_type'] == "full") ? 3 : 2; // 1: dã nhận một phần, 2: đã nhận toàn bộ
                $purchaseOrder->update($orderUpdate);
            }

            if (!$goodReceipt) {
                DB::rollBack();
                return $this->returnError('Không thể tạo phiếu nhập kho, vui lòng thử lại sau');
            }
            $idGoodReceipt = $goodReceipt->id;
            foreach ($data['items'] as $item) {
                $purchaseItem = PurchaseOrderItem::where('purchase_order_id', $data['purchase_order_id'])
                    ->where('product_variant_id', $item['product_variant_id'])
                    ->first();
                if (!$purchaseItem) {
                    DB::rollBack();
                    return $this->returnError('Mục phiếu nhập kho không tồn tại trong đơn hàng, vui lòng kiểm tra lại');
                }
                $purchaseItem->update([
                    'quantity_received' => $item['quantity_received'],
                ]);

                $newItem = [];
                $newItem['good_receipt_id'] = $idGoodReceipt;
                $newItem['product_variant_id'] = $item['product_variant_id'];
                $newItem['quantity_received'] = $item['quantity_received'];
                $newItem['unit_price'] = $item['unit_price'];
                $newItem['unit_id'] = $item['unit_id'];
                $newItem['subtotal'] = $item['subtotal'];

                $goodReceiptItem = GoodReceiptItem::create($newItem);
                if (!$goodReceiptItem) {
                    DB::rollBack();
                    return $this->returnError('Không thể tạo mục phiếu nhập kho, vui lòng thử lại sau');
                }
            }

            DB::commit();
            return $goodReceipt;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo phiếu nhập: ' . $e->getMessage());
            return $this->returnError($e->getMessage());
        }
    }
    public function autoAddDealCode()
    {
        $lastExportCode = $this->handleModel
            ->where('code', 'LIKE', 'PN-%')
            ->orderByDesc('id')
            ->value('code');

        if ($lastExportCode) {
            $lastExportNumber = (int) str_replace('PN-', '', $lastExportCode);
        } else {
            $lastExportNumber = 0;
        }

        $autoExportCode = 'PN-' . str_pad($lastExportNumber + 1, 6, '0', STR_PAD_LEFT);

        return $autoExportCode;
    }
}
