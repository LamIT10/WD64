<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Repositories\PurchaseOrderLogRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderLogController extends Controller
{
    private $purchaseLogRepository;
    public function __construct(PurchaseOrderLogRepository $purchaseLogRepository)
    {
        $this->purchaseLogRepository = $purchaseLogRepository;
    }
    public function getList($id)
    {
        $list = $this->purchaseLogRepository->getList($id);
    }
}
