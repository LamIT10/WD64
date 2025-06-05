<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Rank;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    protected CustomerRepository $customerRepo;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
        // $this->middleware('auth:admin')->only(['destroy']);
    }

    public function index()
    {
        $perPage = request()->get('perPage', 10);
        $filters = [
            'search' => request()->only(['name', 'phone', 'email']),
            'absoluteFilter' => request()->only(['status']),
            'between' => [
                'total_spent' => request()->only(['total_spent_min', 'total_spent_max']),
                'current_debt' => request()->only(['current_debt_min', 'current_debt_max']),
            ],
            'relation' => [
                'rank' => request()->input('rank_name') ? [
                    'field' => 'name',
                    'value' => request()->input('rank_name'),
                ] : null,
            ],
            'relationAbsolute' => [
                'rank' => request()->input('rank_id') ? [
                    'field' => 'id',
                    'value' => request()->input('rank_id'),
                ] : null,
            ],
        ];
        $customers = $this->customerRepo->getAll($perPage, $filters);
        return $this->renderView(['customers' => $customers], 'admin/Customers/Index');
    }

    public function show(Customer $customer)
    {
        return $this->renderView(['customer' => $customer->load('rank')], 'admin/Customers/Show');
    }

    public function create()
    {
        return $this->renderView([
            'rankTemplates' => Rank::where('status', 'active')->get()->toArray(),
        ], 'admin/Customers/Create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $result = $this->customerRepo->createCustomer($request->validated());
        return $this->returnInertia($result, 'Thêm khách hàng mới thành công', 'admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        Log::info('Customer data:', ['customer' => $customer->toArray()]);
        return $this->renderView([
            'customer' => $customer->load('rank'),
            'rankTemplates' => Rank::where('status', 'active')->get()->toArray(),
        ], 'admin/Customers/Edit');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $result = $this->customerRepo->updateCustomer($customer, $request->validated());
        return $this->returnInertia($result, 'Cập nhật khách hàng thành công', 'admin.customers.index');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->salesOrders()->exists()) {
            return $this->returnInertia(
                ['status' => false, 'message' => 'Không thể xóa khách hàng có đơn hàng'],
                '',
                'admin.customers.index'
            );
        }
        $result = $this->customerRepo->deleteCustomer($customer);
        return $this->returnInertia(
            $result,
            'Khách hàng đã được xóa thành công',
            'admin.customers.index'
        );
    }
}