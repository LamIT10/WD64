<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\PurchaseOrderRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    public function __construct(PurchaseOrderRepository $handleRepository)
    {
        $this->handleRepository = $handleRepository;
    }
    public function getList(Request $request)
    {
        $listOrders = $this->handleRepository->getList($request);
        return Inertia::render('admin/PurchaseOrders/List',  [
            'listOrders' => $listOrders
        ]);
    }
    public function create()
    {
        $data = $this->handleRepository->getDataForcreate();
        return Inertia::render('admin/PurchaseOrders/FormCreate', $data);
    }
    public function getVariants($idProduct)
    {
        $data = $this->handleRepository->getVariants($idProduct);
        return response()->json($data);
    }
    public function getSupplierAndUnit($idVariant)
    {
        $data = $this->handleRepository->getSupplierAndUnit($idVariant);
        return response()->json($data);
    }
    public function store(Request $request)
    {
        $dataCreate = $request->all();
        $success = $this->handleRepository->store($dataCreate);
        return $this->returnInertia($success, 'Tạo đơn hàng thành công', 'admin.purchases.index');
    }
    public function approve($id)
    {
        $success = $this->handleRepository->approve($id);
        return $this->returnInertia($success, 'Phê duyệt đơn hàng thành công', 'admin.purchases.index');
    }
    public function edit($id)
    {
        $data = $this->handleRepository->getPurchaseDetail($id);
        // return response()->json($data);
        return Inertia::render('admin/PurchaseOrders/FormUpdate',  [
            'purchase' => $data['purchase'],
            'users' => $data['user']
        ]);
    }
}
