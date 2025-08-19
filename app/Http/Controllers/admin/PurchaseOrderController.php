<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest;
use App\Repositories\PurchaseOrderRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\PurchaseOrdersExport;
use Maatwebsite\Excel\Facades\Excel;

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
            'listOrders' => $listOrders,
            'filters' => $request->only(['order_status','supplier','code','start','end']),
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

    public function store(PurchaseOrderRequest $request)
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
    public function cancel(Request $request, $id)
    {
        $data = $request->all();
        $success = $this->handleRepository->cancel($data, $id);
        return $this->returnInertia($success, 'Từ chối đơn hàng thành công', 'admin.purchases.index');
    }
    public function edit($id)
    {
        $data = $this->handleRepository->getPurchaseDetail($id);
        return Inertia::render('admin/PurchaseOrders/FormUpdate',  [
            'purchase' => $data['purchase'],
            'users' => $data['user'],
            'products'=> $data['product']
        ]);
    }
    public function update(PurchaseOrderRequest $request, $id)
    {
        $data = $request->all();
        $success = $this->handleRepository->update($data, $id);
        return $this->returnInertia($success, 'Cập nhật đơn hàng thành công', 'admin.purchases.edit', [$id]);
    }
    public function end($id)
    {
        $success = $this->handleRepository->end($id);
        return $this->returnInertia($success, 'Đã kết thúc đơn hàng', 'admin.purchases.index');
    }
    
      
        public function exportExcel(Request $request)
    {
        $filters = $request->only(['code', 'supplier', 'order_status', 'start', 'end']);
        return Excel::download(new PurchaseOrdersExport($filters), 'don-nhap.xlsx');
    }
    public function log($id)
    {
        $data = $this->handleRepository->getLog($id);
        
        if (isset($data['error'])) {
            return back()->with('error', $data['message']);
        }
        return Inertia::render('admin/PurchaseOrders/PurchaseLog', [
            'logs' => $data['logs'],
            'purchase' => $data['purchase'],
            'purchaseId' => $id
        ]);
    }
}
