<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GoodsReceiptsExport;
use App\Http\Controllers\Controller;
use App\Repositories\GoodReceiptRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class GoodReceiptController extends Controller
{
    public function __construct(GoodReceiptRepository $handleRepository)
    {
        $this->handleRepository = $handleRepository;
    }
    public function getList(Request $request)
    {
        $listGoodReceipts = $this->handleRepository->getList($request);
        return Inertia::render('admin/PurchaseOrders/ListGoodReceipt', [
            'listGoodReceipts' => $listGoodReceipts,
            'filters' => $request->only(['code', 'purchase_order_code', 'date_from', 'date_to']),
        ]);
    }
    public function createFromPurchaseOrder($id)
    {
        $purchaseOrder = $this->handleRepository->getByPurchaseOrder($id);
        if (!$purchaseOrder) {
            return redirect()->route('admin.purchases.index')->with('error', 'Đơn hàng không tồn tại');
        }
        return Inertia::render('admin/PurchaseOrders/FormGoodReceipt', [
            'purchaseOrder' => $purchaseOrder
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $goodReceipt = $this->handleRepository->store($data);
        return $this->returnInertia($goodReceipt, 'Tạo đơn hàng phiếu nhập kho thành công', 'admin.purchases.index');
    }
    public function exportGoodsReceipts()
{
    return Excel::download(new GoodsReceiptsExport, 'danh_sach_phieu_nhap.xlsx');
}
}
