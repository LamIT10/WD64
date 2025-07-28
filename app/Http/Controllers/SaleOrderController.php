<?php

namespace App\Http\Controllers;

use App\Exports\SaleOrderExport;
use App\Http\Requests\StoreSaleOrderRequest;
use App\Http\Requests\UpdatePayBeforeSaleOrderRequest;
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
        $result = $this->saleOrdersRepository->index($request);
        if (isset($result['error'])) {
            return Inertia::render('admin/SaleOrders/List', [
                'listOrders' => [
                    'data' => [],
                    'error' => $result['error'],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 10,
                        'total' => 0,
                    ],
                ]
            ]);
        }
        return Inertia::render('admin/SaleOrders/List', [
            'listOrders' => [
                'data' => $result->items(),
                'meta' => [
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'links' => $result->getUrlRange(1, $result->lastPage()),
                ],
            ],
            'sale_order_id' => $request->query('sale_order_id'),
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
        $rejectReason = request()->validate([
            'reject_reason' => 'required|string|max:255',
        ])['reject_reason'];
        $result = $this->saleOrdersRepository->rejectOrder($id, $rejectReason);
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['error' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', $result['message']);
    }
    public function approve(UpdatePayBeforeSaleOrderRequest $request, $id)
    {

        $result = $this->saleOrdersRepository->approveOrder($id,  $request->validated()['pay_before']);
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['pay_before' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', $result['message']);
    }

    public function complete(Request $request, $id)
    {
        $data = $request->validate([
            'pay_after' => 'required|numeric|min:0',
            'customer_id' => 'required|exists:customers,id',
        ]);
        $result = $this->saleOrdersRepository->completeOrder($id,  $data['pay_after'], $data['customer_id']);
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['pay_after' => $result['error']]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', $result['message']);
    }
    public function export(Request $request)

    {
        return Excel::download(new SaleOrderExport($request->only(['status', 'customer', 'order_date'])), 'Đơn_Xuất.xlsx');
    }
    public function generateQR($id, Request $request)
    {
        $result = $this->saleOrdersRepository->generateQR($id, $request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);  // Return JSON error với status 400
        }

        return response()->json($result);  // Return JSON success
    }
    public function findPage(Request $request)
    {
        $orderId = $request->query('order_id');
        $perPage = $request->query('per_page', 10);
        $page = app(SaleOrdersRepository::class)
            ->getPageOfOrder($orderId, $perPage);

        return response()->json(['page' => $page]);
    }
}
