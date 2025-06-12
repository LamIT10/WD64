<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\PurchaseOrderRepository;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    public function __construct(PurchaseOrderRepository $handleRepository)
    {
        $this->handleRepository = $handleRepository;
    }
    public function getList()
    {
        return Inertia::render('admin/PurchaseOrders/FormCreate');
    }
    public function create()
    {
        $data = $this->handleRepository->getDataForcreate();
        return Inertia::render('admin/PurchaseOrders/FormCreate', $data);
    }
    public function getVariants($idProduct){
       $data = $this->handleRepository->getVariants($idProduct);
       return response()->json($data);
    }
    public function getSupplierAndUnit($idVariant){
        $data = $this->handleRepository->getSupplierAndUnit($idVariant);
        return response()->json($data);
    }
}
