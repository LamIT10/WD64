<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GoodsReceiptsExport;
use App\Http\Controllers\Controller;
use App\Repositories\GoodReceiptRepository;
use App\Http\Requests\GoodReceiptRequest;
use App\Models\GoodReceipt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
 use Barryvdh\DomPDF\Facade\Pdf;

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
    public function store(GoodReceiptRequest $request)
    {
        $data = $request->validated();
        $goodReceipt = $this->handleRepository->store($data);
        return $this->returnInertia($goodReceipt, 'Tạo đơn hàng phiếu nhập kho thành công', 'admin.purchases.index');
    }
        public function exportGoodsReceipts()
        {
            return Excel::download(new GoodsReceiptsExport, 'danh_sach_phieu_nhap.xlsx');
        }

     

        public function print($id)
        {
            $receipt = GoodReceipt::with([
                'purchaseOrder.supplier',
                'items.productVariant.product',
                'items.productVariant.attributes',
                'items.unit',
                'createBy',
            ])->findOrFail($id);

            return Pdf::loadView('pdf.goodreceipt', compact('receipt'))
                ->stream("phieu-nhap-{$receipt->code}.pdf");
        }
}
