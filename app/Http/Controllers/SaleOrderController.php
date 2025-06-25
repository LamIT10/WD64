<?php

namespace App\Http\Controllers;

use App\Exports\SaleOrderExport;
use App\Http\Requests\StoreSaleOrderRequest;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SaleOrdersRepository;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SaleOrderController extends Controller
{
    protected $saleOrdersRepository;
    protected $customerRepository;
    protected $productRepository;
    protected $unitRepository;
    public function __construct(
        SaleOrdersRepository $saleOrdersRepository,
        CustomerRepository $customerRepository,
        ProductRepository $productRepository,
        UnitRepository $unitRepository
    ) {
        $this->saleOrdersRepository = $saleOrdersRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->unitRepository = $unitRepository;
    }
    public function index()
    {
        $saleOrders = $this->saleOrdersRepository->getList();
        $units = $this->unitRepository->getList();
        return inertia('admin/sale-orders/List', [
            'saleOrders' => $saleOrders,
            'units' => $units
        ]);
    }
    public function create()
    {

        return inertia('admin/sale-orders/Create');
    }
    public function searchProductJson(Request $request)
    {
        return $this->saleOrdersRepository->searchProduct($request);
    }
    public function getAllVariantsJson($productId)
    {
        return $this->saleOrdersRepository->getAllVariantsByProductId($productId);
    }
    public function getAllUnitJson($productId)
    {
        return $this->saleOrdersRepository->getUnitConversions($productId);
    }
    public function searchCustomerJson(Request $request)
    {
        return $this->saleOrdersRepository->searchCustomer($request);
    }
    public function store(StoreSaleOrderRequest $request)
    {
        $result = $this->saleOrdersRepository->createSaleOrder($request->validated());

        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['error' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', 'Tạo đơn hàng thành công');
    }
    public function export()
    {
        return Excel::download(new SaleOrderExport, 'DonHangXuat.xlsx');
    }
}
