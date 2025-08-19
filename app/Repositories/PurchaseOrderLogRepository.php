<?php

namespace App\Repositories;

use App\Models\PurchaseOrderLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderLogRepository extends BaseRepository
{
    protected $model;
    public function __construct(PurchaseOrderLog $purchaseOrderLog)
    {
        $this->model = $purchaseOrderLog;
    }
    public function getList($id){
        return $this->model->where('purchase_order_id', $id)
            ->with(['purchaseOrder'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function createLog($type, $data){
        try {
            DB::beginTransaction();
            $log = $this->model->create([
                'purchase_order_id' => $data['purchase_order_id'],
                'created_by' => $data['created_by'] ?? null,
                'refused_by' => $data['refused_by'] ?? null,
                'updated_by' => $data['updated_by'] ?? null,
                'approved_by' => $data['approved_by'] ?? null,
                'end_by' => $data['end_by'] ?? null,
                'create_grn_by' => $data['create_grn_by'] ?? null,
                'type' => $type,
                'detail' => $data['detail'] ?? null
            ]);
            DB::commit();
            return $log;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi thêm log: ' . $th->getMessage());
        }
    }
}
