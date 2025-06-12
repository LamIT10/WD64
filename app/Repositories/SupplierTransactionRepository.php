<?php

namespace App\Repositories;

use App\Models\SupplierTransaction;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class SupplierTransactionRepository extends BaseRepository
{
    public function __construct(SupplierTransaction $model)
    {
        $this->handleModel = $model;
    }
    public function getData($data, $perPage)
    {
        $query = $this->handleModel::with(relations: 'supplier')->with("purchaseOrder")->select(["*"]);
        if (!empty($data)) {
            $filters = [];
            $query = $this->filterData($query, $filters);
        }
        return $query->orderBy("credit_due_date", "asc")->paginate($perPage);
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
            $newObj = [];
            $newObj["credit_due_date"] = $data["credit_due_date"];
            $obj = $obj->update($newObj);
            if (!$obj) {
                throw new \Exception("Có lỗi cập nhật");
            }
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
            if($newObj["paid_amount"] > $obj->purchaseOrder->total_amount) {
                throw new \Exception("Tổng tiền thanh toán đã vượt quá đơn hàng") ;
            }
            $obj = $obj->update($newObj);
            if (!$obj) {
                throw new \Exception("Có lỗi cập nhật");
            }
            DB::commit();
            return $obj;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Lỗi: " . $e->getMessage());
        }
    }
}
