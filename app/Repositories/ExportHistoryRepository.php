<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\ExportHistories;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportHistoryRepository extends BaseRepository
{
    public function __construct(ExportHistories $model)
    {
        $this->handleModel = $model;
    }

    public function createHistory($data)
    {
        try {
            DB::beginTransaction();
            $newObj = [
                'sale_order_id' => $data['sale_order_id'],
                'action_name' => $data['action_name'],
                'content' => $data['content'],
                'created_id' => Auth::user()->id,
            ];
            $history = $this->handleModel->create($newObj);
            DB::commit();
            return $history;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return null;
        }
    }
    public function getHistoryBySaleOrderId($saleOrderId)
    {
        return $this->handleModel->where('sale_order_id', $saleOrderId)
            ->with([
                'User' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])->select(['*'])
            ->orderBy('id', 'desc')
            ->get();
    }
    
}
