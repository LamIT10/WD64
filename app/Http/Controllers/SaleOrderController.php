<?php

namespace App\Http\Controllers;

use App\Exports\SaleOrderExport;
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
    public function index()
    {
        $saleOrders = $this->saleOrdersRepository->getList();
        $customers = $this->customerRepository->getList();
        $products = $this->productRepository->getAll();
        $units = $this->unitRepository->getList();
        return inertia('admin/sale-orders/List', [
            'saleOrders' => $saleOrders,
            'customers' => $customers,
            'products' => $products->items(),
            'units' => $units
        ]);
    }
    public function create()
    {
        $customers = $this->customerRepository->getList();
        return inertia('admin/sale-orders/Create', ['customers' => $customers,]);
    }
    public function export()
    {
        return Excel::download(new SaleOrderExport, 'DonHangXuat.xlsx');
    }
}
