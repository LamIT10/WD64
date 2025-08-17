<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SupplierDebtHistory;
use App\Models\SupplierTransaction;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
                throw new \Exception("Hạn công nợ cần lớn hạn cũ");
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
                'created_id' => Auth::user()->id,
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
            $obj = $this->handleModel::with("goodReceipt")->where("id", $id)->firstOrFail();
            $newObj = [];
            $newObj["paid_amount"] = $obj['paid_amount'] + $data["payment"];
            if ($newObj["paid_amount"] > $obj->goodReceipt->total_amount) {
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
                'proof_image' => $this->handleUploadOneFile($data['file']),
                'created_id' => Auth::user()->id,
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


        $query = $this->handleModel::with(
            [
                'goodReceipt' => function ($query) {
                    return $query->select(['id', 'code', 'receipt_date', 'status', 'approved_by', 'approved_at', 'total_amount', 'created_by', 'purchase_order_id']);
                },
                'goodReceipt.purchaseOrder.supplier' => function ($query) {
                    return $query;
                },
                'goodReceipt.createBy' => function ($query) {
                    return $query->select(['id', 'name']);
                },
                'goodReceipt.approvedBy' => function ($query) {
                    return $query->select(['id', 'name']);
                },
                'goodReceipt.items' => function ($query) {
                    return $query->select(['id', 'good_receipt_id', 'product_variant_id', 'unit_id', 'quantity_expected', 'quantity_received', 'subtotal', 'unit_price']);
                },
                'goodReceipt.items.productVariant' => function ($query) {
                    return $query;
                },
                'goodReceipt.items.productVariant.attributes' => function ($query) {
                    return $query;
                },
                'goodReceipt.items.productVariant.product' => function ($query) {
                    return $query->select(['id', 'description', 'name']);
                },
                'goodReceipt.items.productVariant.product.unitConversions' => function ($query) {
                    return $query;
                },
                'goodReceipt.items.productVariant.product.unitConversions.fromUnit' => function ($query) {
                    return $query;
                },
                'goodReceipt.items.productVariant.product.unitConversions.toUnit' => function ($query) {
                    return $query;
                },
                'goodReceipt.items.unit' => function ($query) {
                    return $query;
                }

            ]
        )->where('goods_receipt_id', $id)->select(['id', 'paid_amount', 'transaction_date', 'credit_due_date', 'description', 'goods_receipt_id'])->first();

        $supplierDebtHistory = $this->supplierDebt::with('createdBy')->where("supplier_transaction_id", $id)->orderBy("created_at", 'desc')->get();
        $supplierDebtHistory = $supplierDebtHistory->map(function ($history) {
            $formated = $history->toArray();
            if ($history->update_type === 'payment') {
                $formatted['new_value'] = $this->formatNumberInt($history->new_value);
            } elseif ($history->update_type === 'due_date') {
                $formatted['new_value'] = $this->formatDate($history->new_value);
            }
            return $formated;
        });
        return [
            'name_supplier' => $query->goodReceipt->purchaseOrder->supplier->name ?? "",
            'contact_person' => $query->goodReceipt->purchaseOrder->supplier->contact_person ?? "",
            'phone' => $query->goodReceipt->purchaseOrder->supplier->phone ?? "",
            'email' => $query->goodReceipt->purchaseOrder->supplier->email ?? "",
            'address' => $query->goodReceipt->purchaseOrder->supplier->address ?? "",
            'order_code' => $query->goodReceipt->code ?? "",
            'order_date' => $query->goodReceipt->purchaseOrder->order_date ?? "",
            'total_amount' => $query->goodReceipt->total_amount ?? 0,
            'paid_amount' => $query->paid_amount ?? 0,
            'transaction_date' => $query->transaction_date ?? "",
            'credit_due_date' => $query->credit_due_date ?? "",
            'description' => $query->description ?? "",
            'status' => $query->goodReceipt->status ?? -1,
            'list_item_order' => $query->goodReceipt->items ?? "",
            'supplier_debt_history' => $supplierDebtHistory ?? "",
            'approved_by' => $query->goodReceipt->approvedBy->name ?? "",
            'created_by' => $query->goodReceipt->createBy->name ?? "",
            // 'approved_by' => $query->goodReceipt->approved_by,
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