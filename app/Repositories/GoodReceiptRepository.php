<?php

namespace App\Repositories;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\Inventory;
use App\Models\ProductUnitConversion;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\SupplierDebtHistory;
use App\Models\SupplierTransaction;
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
                $query->select(['id', 'supplier_id', 'user_id', 'order_status', 'code']);
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
        if (isset($request->code)) {
            $query->where('code', $request->code);
        }
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
        // phương thức để tạo phiếu nhập, cập nhật tồn kho, cập nhật công nợ nhà cung cấp, cập nhật giao dịch nhà cung cấp
        try {
            DB::beginTransaction();

            // tạo phiếu nhập kho mới
            $newGoodReceipt = [];
            $newGoodReceipt['purchase_order_id'] = $data['purchase_order_id'];
            $newGoodReceipt['note'] = $data['note'] ?? '';
            $newGoodReceipt['code'] = $this->autoAddDealCode();
            $newGoodReceipt['receipt_date'] = Carbon::now();
            $newGoodReceipt['created_by'] = Auth::id();
            $newGoodReceipt['total_amount'] = $data['total_amount'] ?? 0;
            $goodReceipt = $this->handleModel->create($newGoodReceipt);

            // cập nhật trạng thái đơn hàng
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

            // cập nhật số lượng đã nhận của đơn hàng chi tiết
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
                    'quantity_received' => $purchaseItem['quantity_received'] + $item['quantity_received'],
                ]);

                // cập nhật tồn kho
                $inventory = Inventory::where('product_variant_id', $item['product_variant_id'])->first();
                $quantityLast = null;
                if ($item['unit_default'] == $item['unit_id']) {
                    $quantityLast = $item['quantity_received'];
                } else {
                    $factor = ProductUnitConversion::where('product_id', $item['product_id'])->where('from_unit_id', $item['unit_default'])->where('to_unit_id', $item['unit_id'])->first();
                    $quantityLast = $factor->conversion_factor * $item['quantity_received'];
                }
                $factor = ProductUnitConversion::where('product_id', $item['product_id'])->where('from_unit_id', $item['unit_default'])->where('to_unit_id', $item['unit_id'])->first();
                $inventory->update([
                    'quantity_in_transit' => $inventory->quantity_in_transit - $quantityLast,
                    'quantity_on_hand' => $inventory->quantity_on_hand + $quantityLast,
                ]);

                // tạo mới chi tiết phiếu nhập kho
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

            // tạo mới giao dịch
            $newTransaction = [];
            $newTransaction['goods_receipt_id'] = $idGoodReceipt;
            $newTransaction['paid_amount'] = $data['payment'] ?? 0;
            $newTransaction['transaction_date'] = Carbon::now();
            $newTransaction['credit_due_date'] = Carbon::now()->addDays(10);
            $newTransaction['description'] = 'Nhập kho';
            $goodReceiptTransaction = SupplierTransaction::create($newTransaction);
            if (!$goodReceiptTransaction) {
                DB::rollBack();
                return $this->returnError('Không thể tạo giao dịch, vui lòng thử lại sau');
            }

            // tạo mới lịch sử công nợ
            $idTransaction = $goodReceiptTransaction->id;
            $newDebt = [];
            $newDebt['supplier_transaction_id'] = $idTransaction;
            $newDebt['new_value'] = $data['payment'] ?? 0;
            $newDebt['note'] = 'Thanh toán lần đầu';
            $newDebt['update_type'] = 'payment';
            $goodReceiptDebt = SupplierDebtHistory::create($newDebt);
            if (!$goodReceiptDebt) {
                DB::rollBack();
                return $this->returnError('Có lỗi khi lưu lịch sử thanh toán');
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
