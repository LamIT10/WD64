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
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodReceiptRepository extends BaseRepository
{
    protected $productRepository;
    protected $log;
    public function __construct(GoodReceipt $model, PurchaseOrderLogRepository $log)
    {
        $this->handleModel = $model;
        $this->log = $log;
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

        // Lọc theo mã phiếu nhập
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        // Lọc theo mã đơn nhập
        if ($request->filled('purchase_order_code')) {
            $query->whereHas('purchaseOrder', function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->purchase_order_code . '%');
            });
        }
        // Lọc theo ngày nhập
        if ($request->filled('date_from')) {
            $query->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('receipt_date', '<=', $request->date_to);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate(10)->withQueryString();
    }
    public function getByPurchaseOrder($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if (!$purchaseOrder) {
            abort(404, 'Đơn hàng không tồn tại');
        }
        if ($purchaseOrder->order_status != 1 && $purchaseOrder->order_status != 2) {
            abort(403, 'Đơn hàng không thể tạo phiếu nhập kho');
        }
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
                $query->select(['id', 'product_id', 'code']);
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

                // tính số lượng theo đơn vị cơ bản cho quantity_received
                $quantityReceivedInBase = null;
                if ($item['unit_default'] == $item['unit_id']) {
                    $quantityReceivedInBase = $item['quantity_received'];
                } else {
                    $factor = ProductUnitConversion::where('product_id', $item['product_id'])
                        ->where('from_unit_id', $item['unit_default'])
                        ->where('to_unit_id', $item['unit_id'])
                        ->first();
                    $quantityReceivedInBase = $factor->conversion_factor * $item['quantity_received'];
                }

                // tính số lượng theo đơn vị cơ bản cho quantity_ordered (số đặt - để trừ trong quantity_in_transit)
                $quantityOrderedInBase = null;
                if ($item['unit_default'] == $item['unit_id']) {
                    $quantityOrderedInBase = $item['quantity_ordered'];
                } else {
                    $factor = ProductUnitConversion::where('product_id', $item['product_id'])
                        ->where('from_unit_id', $item['unit_default'])
                        ->where('to_unit_id', $item['unit_id'])
                        ->first();
                    $quantityOrderedInBase = $factor->conversion_factor * $item['quantity_ordered'];
                }

                $inventory->update([
                    'quantity_in_transit' => max(0, $inventory->quantity_in_transit - $quantityOrderedInBase),
                    'quantity_on_hand' => $inventory->quantity_on_hand + $quantityReceivedInBase,
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
            // ghi log
            $this->log->createLog(6, [
                'purchase_order_id' => $purchaseOrder->id,
                'create_grn_by' => Auth::user()->id,
                'detail' => $goodReceipt->code
            ]);

            DB::commit();
            app(NotificationService::class)->create(
                'grn',
                'Tạo phiếu nhập kho',
                "Tạo phiếu nhập kho cho đơn hàng #{$purchaseOrder->code}.",
                [],
            );
            $actor = Auth::user();
            app(NotificationService::class)->notifyAll(
                'grn',
                'Tạo phiếu nhập kho',
                "Tạo phiếu nhập kho cho đơn hàng #{$purchaseOrder->code} bởi {$actor->name} ",
                []
            );
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
