<?php

namespace App\Http\Controllers;

use App\Exports\SaleOrderExport;
use App\Http\Requests\StoreSaleOrderRequest;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SaleOrdersRepository;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $orders = $this->saleOrdersRepository->index($request);
        return Inertia::render('admin/SaleOrders/List', [
            'listOrders' => ['data' => $orders]
        ]);
    }
    public function create()
    {

        return inertia('admin/SaleOrders/Create');
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
    public function getInventoryQuantity($productVariantId)
    {
        $quantity = $this->saleOrdersRepository->getInventoryQuantity($productVariantId);
        return response()->json(['quantity_on_hand' => $quantity]);
    }
    public function rejectSaleOrder($id)
    {
        $result = $this->saleOrdersRepository->rejectOrder($id);
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['error' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', $result['message']);
    }
    public function approve($id)
    {
        $result = $this->saleOrdersRepository->approveOrder($id);
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['error' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', $result['message']);
    }
    public function export()
    {
        return Excel::download(new SaleOrderExport(), 'Đơn_Xuất.xlsx');
    }
}