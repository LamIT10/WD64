<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Rank;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerController extends Controller
{
    protected CustomerRepository $customerRepo;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $perPage = request()->get('perPage', 10);
        $customers = $this->customerRepo->getAll($perPage);

        return Inertia::render('admin/Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function show(Customer $customer)
    {
        return Inertia::render('admin/Customers/Show', [
            'customer' => $customer->load('rank'),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Customers/Create', [
            'rankTemplates' => Rank::all()->toArray(),
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customerRepo->createCustomer($request->validated());

        return redirect()->route('admin.customers.index')->with('success', 'Thêm khách hàng mới thành công');
    }

    public function edit(Customer $customer)
    {
        Log::info('Customer data:', ['customer' => $customer->toArray()]);

        return Inertia::render('admin/Customers/Edit', [
            'customer' => $customer->load('rank'),
            'rankTemplates' => Rank::all()->toArray(),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->customerRepo->updateCustomer($customer, $request->validated());

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật khách hàng thành công.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->salesOrders()->exists()) {
            return redirect()->route('admin.customers.index')->with('error', 'Không thể xóa khách hàng có đơn hàng.');
        }

        $this->customerRepo->deleteCustomer($customer);

        return redirect()->route('admin.customers.index')->with('success', 'Xóa khách hàng thành công.');
    }
}