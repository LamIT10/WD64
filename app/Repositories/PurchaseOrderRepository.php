<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderRepository extends BaseRepository
{
    public function __construct(PurchaseOrder $model)
    {
        $this->handleModel = $model;
    }
}