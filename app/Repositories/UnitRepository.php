<?php

namespace App\Repositories;

use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class UnitRepository extends BaseRepository
{
    protected $model;
    public function __construct(Unit $Unit) 
    {
        $this->model = $Unit;
    }
    public function getList()
    {
        try {
            return  $this->model->get();
        } catch (\Throwable $th) {
            return  Log::error('Lỗi lấy toàn bộ danh sách đơn vị: ' . $th->getMessage());
        }
    }
}