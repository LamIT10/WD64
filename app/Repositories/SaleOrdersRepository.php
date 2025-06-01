<?php

namespace App\Repositories;

use App\Models\SaleOrder;
use Illuminate\Support\Facades\Log;

class SaleOrdersRepository extends BaseRepository
{
    protected $model;
    public function __construct(SaleOrder $saleOrder)
    {
        $this->model = $saleOrder;
    }
    public function getList()
    {
        try {
            return  $this->model->select(
                'sale_orders.id',
                'customers.id as customer_id',
                'customers.name',
                'customers.address',
                'sale_orders.status',
                'sale_orders.total_amount'
            )->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
                ->get();
        } catch (\Throwable $th) {
            return  Log::error('Lỗi lấy toàn bộ danh sách đơn xuất: ' . $th->getMessage());
        }
    }
    public function create() {}
}
