<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SupplierDebtHistory;
use App\Models\SupplierTransaction;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierTransactionRepository extends BaseRepository
{
    private $supplierDebt;
    public function __construct(SupplierTransaction $model, SupplierDebtHistory $supplier_debt)
    {
        $this->handleModel = $model;
        $this->supplierDebt = $supplier_debt;
    }
    public function getData($data, $perPage)
    {
        $query = $this->handleModel::with("purchaseOrder")->with("purchaseOrder.supplier")->select(["*"]);

        if (!empty($data)) {
            $query = $query->when(!empty($data['supplierFilter']), function ($query, $supplierFilter) use ($data) {
                return $query->whereHas('purchaseOrder', function ($subQuery) use ($data) {
                    $subQuery->where('supplier_id', $data['supplierFilter']);
                });
            });

            if (!empty($data['fromCreditDueDate'])) {
                if (empty($data['toCreditDueDate'])) {
                    $query = $query->where('credit_due_date', '>=', $data['fromCreditDueDate']);
                } else {
                    $query = $query->whereBetween('credit_due_date', [
                        $data['fromCreditDueDate'],
                        $data['toCreditDueDate']
                    ]);
                }
            } elseif (!empty($data['toCreditDueDate'])) {
                $query = $query->where('credit_due_date', '<=', $data['toCreditDueDate']);
            }

            if (!empty($data['fromPayment'])) {
                $query = $query->whereHas('purchaseOrder', function ($subQuery) use ($data) {
                    $subQuery->whereRaw('total_amount - supplier_transactions.paid_amount >= ?', [$data['fromPayment']]);
                });
            }

            if (!empty($data['toPayment'])) {
                $query = $query->whereHas('purchaseOrder', function ($subQuery) use ($data) {
                    $subQuery->whereRaw('total_amount - supplier_transactions.paid_amount <= ?', [$data['toPayment']]);
                });
            }

            if (!empty($data['statusFilter'])) {
                $query = $query->whereHas('purchaseOrder', function ($subQuery) use ($data) {
                    if ($data['statusFilter'] == 4) {
                        $subQuery->whereRaw('total_amount - supplier_transactions.paid_amount = ?', [0]);
                    } else {
                        $subQuery->whereRaw('total_amount - supplier_transactions.paid_amount > ?', [0]);
                    }
                });

                if ($data['statusFilter'] == 3) {
                    $query = $query->where('credit_due_date', '>=', Carbon::now()->endOfDay())
                        ->where('credit_due_date', '<=', Carbon::now()->endOfDay()->addDays(7));
                } elseif ($data['statusFilter'] == 2) {
                    $query = $query->where('credit_due_date', '>', Carbon::now()->endOfDay());
                } elseif ($data['statusFilter'] == 1) {
                    $query = $query->where('credit_due_date', '<', Carbon::now()->endOfDay());
                }
            }
        }
        $query = $query->orderBy("credit_due_date", "asc")->paginate($perPage);

        $query->getCollection()->transform(function ($item) {
            $item->outstanding_amount = $this->formatNumberInt($item->purchaseOrder->total_amount - $item->paid_amount);
            $item->paid_amount = $this->formatNumberInt($item->paid_amount);
            $item->purchaseOrder->total_amount = $this->formatNumberInt($item->purchaseOrder->total_amount);
            return $item;
        });

        return $query;
    }
    public function hanldeUpdateCreditDueDate($id, $data)
    {
        try {
            $obj = $this->findById($id);

            if (!isset($data['credit_due_date']) || empty($data['credit_due_date'])) {
                throw new \Exception("Dữ liệu hạn công nợ không hợp lệ");
            }
            if ($obj->transaction_date > $data["credit_due_date"]) {
                throw new \Exception("Hạn công nợ cần lớn hơn ngày giao dịch");
            }
            if ($obj->credit_due_date > $data["credit_due_date"]) {
                throw new \Exception("Hạn công nợ cầh lớn hạn cũ");
            }
            $newObj = [];
            $newObj["credit_due_date"] = $data["credit_due_date"];
            $obj = $obj->update($newObj);
            if (!$obj) {
                throw new \Exception("Có lỗi cập nhật");
            }
            $this->supplierDebt::create([
                'supplier_transaction_id' => $id,
                'new_value' => $newObj['credit_due_date'],
                'update_type' => "due_date",
                'note' => $data['note'] ?? "",
            ]);

            DB::commit();
            return $obj;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Lỗi: " . $e->getMessage());
        }
    }
    public function hanldeUpdatePayment($id, $data)
    {
        try {
            $obj = $this->handleModel::with("purchaseOrder")->where("id", $id)->firstOrFail();
            $newObj = [];

            $newObj["paid_amount"] = $obj['paid_amount'] + $data["payment"];
            if ($newObj["paid_amount"] > $obj->purchaseOrder->total_amount) {
                throw new \Exception("Tổng tiền thanh toán đã vượt quá đơn hàng");
            }
            if ($data["payment"] <= 0) {
                throw new \Exception("Số tiền thanh toán cần lớn 0");
            }
            $obj = $obj->update($newObj);
            if (!$obj) {
                throw new \Exception("Có lỗi cập nhật");
            }
            $this->supplierDebt::create([
                'supplier_transaction_id' => $id,
                'new_value' => $data['payment'],
                'update_type' => "payment",
                'note' => $data['note'] ?? "",
            ]);
            DB::commit();
            return $obj;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Lỗi: " . $e->getMessage());
        }
    }
    public function handleCreate($data)
    {
        try {
            DB::beginTransaction();
            $newObj = [];
            $newObj['purchase_order_id'] = $data['purchase_order_id'];
            $newObj['paid_amount'] = $data['paid_amount'];
            $newObj['transaction_date'] = $data['transaction_date'];
            $newObj['credit_due_date'] = $data['credit_due_date'];
            $newObj['description'] = $data['description'];
            $newTransaction = $this->handleModel::create($newObj);
            if (!$newTransaction) {
                throw new \Exception('Có lỗi khi thêm công nợ nhà cung cấp');
            }
            DB::commit();
            return $newTransaction;
        } catch (\Throwable $th) {
            Log::error("Thêm công nợ lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function getDataForShowTransaction($id)
    {
        // Eager load tất cả relationships cần thiết trong một truy vấn
        $obj = $this->handleModel->with([
            'purchaseOrder.supplier',
            'purchaseOrder.items.productVariant.product.unitConversions.toUnit',
            'supplierDebtHistories'
        ])->findOrFail($id);

        // Lấy items và transform ngay trong query
        $listItem = $obj->purchaseOrder->items->map(function ($item) {
            $variant = $item->productVariant;
            $product = $variant->product;

            // Tạo danh sách conversion
            $conversions = $product->unitConversions->map(function ($conversion) use ($item) {
                return sprintf(
                    "1 %s = %s %s",
                    $item->unit->name,
                    $this->formatNumberDemical($conversion->conversion_factor),
                    $conversion->toUnit->name
                );
            })->toArray();

            return [
                'product_name' => sprintf(
                    "%s - %s",
                    $product->name,
                    $variant->attributes->pluck('name')->implode(' - ')
                ),
                'quantity_ordered' => "{$item->quantity_ordered} - {$item->unit->name}",
                'convertion' => $conversions,
                'quantity_received' => "{$item->quantity_received} - {$item->unit->name}",
                'unit_price' => $this->formatNumberInt($item->unit_price) . " ₫",
                'subtotal' => $this->formatNumberInt($item->subtotal) . " ₫",
            ];
        })->toArray();
        $supplierDebtHistory = $this->supplierDebt::where("supplier_transaction_id", $id)->orderBy("created_at", 'desc')->get();
        // $supplierDebtHistory = $subSupplierDebtHistory->map(function ($history) {
        //     $formated = $history->toArray();
        //     if ($history->update_type === 'payment') {
        //         $formatted['new_value'] = $this->formatNumberInt($history->new_value); 
        //     } elseif ($history->update_type === 'due_date') {
        //         $formatted['new_value'] = $this->formatDate($history->new_value); 
        //     }
        //     // $formated['created_date'] = $this->formatDate($history->created);
        //     return $formated;
        // });

        // dd($supplierDebtHistory);
        return [
            'name_supplier' => $obj->purchaseOrder->supplier->name,
            'contact_person' => $obj->purchaseOrder->supplier->contact_person,
            'phone' => $obj->purchaseOrder->supplier->phone,
            'email' => $obj->purchaseOrder->supplier->email,
            'address' => $obj->purchaseOrder->supplier->address,
            'order_code' => 'MDH - ' . $obj->purchaseOrder->id,
            'order_date' => $this->formatDate($obj->purchaseOrder->order_date),
            'total_amount' => $obj->purchaseOrder->total_amount,
            'paid_amount' => $obj->paid_amount,
            'transaction_date' => $this->formatDate($obj->transaction_date),
            'credit_due_date' => $this->formatDate($obj->credit_due_date),
            'description' => $obj->description,
            'status' => $this->getStatusBySupplierTransaction($obj->id),
            'list_item_order' => $listItem,
            'supplier_debt_histort' => $supplierDebtHistory,
        ];
    }
    private function getStatusBySupplierTransaction($id)
    {
        $obj = $this->findById($id);
        if ($obj->purchaseOrder->total_amount - $obj->paid_amount == 0) {
            return 4;
        } else if ($obj->credit_due_date <= Carbon::now()->endOfDay()->addDays(7)) {
            return 3;
        } else if ($obj->credit_due_date >  Carbon::now()->endOfDay()) {
            return 2;
        }
        return 1;
    }
}
