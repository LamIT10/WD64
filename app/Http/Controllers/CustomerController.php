<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
// use App\Repositories\CustomerTransactionRepository;
use App\Models\Customer;
use App\Models\Rank;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerTransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $filters = [
            'search' => ['search' => request()->input('search')],
            'absoluteFilter' => request()->only(['status']),
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
        // sử lí công nợ
        $debts = app(CustomerTransactionRepository::class)->getDebtSummaryByCustomer();
        $customers = $this->customerRepo->getAll($perPage, $filters);
        // gán công nợ cho từng khách hàng
        $customers->getCollection()->transform(function ($customer) use ($debts) {
            $debt = $debts->firstWhere('customer_id', $customer->id);

            $customer->remaining_amount = $debt['remaining_amount'] ?? 0;
       

            return $customer;
        });
        // dd($customers->toArray());

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
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.customers.index');
        }
        return $this->returnInertia($result, 'Thêm khách hàng mới thành công', 'admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        if (config('app.debug')) {
            Log::info('Customer data:', ['customer' => $customer->toArray()]);
        }
        return $this->renderView([
            'customer' => $customer->load('rank'),
            'rankTemplates' => Rank::where('status', 'active')->get()->toArray(),
        ], 'admin/Customers/Edit');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $result = $this->customerRepo->updateCustomer($customer, $request->validated());
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.customers.index');
        }
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
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.customers.index');
        }
        return $this->returnInertia(
            $result,
            'Khách hàng đã được xóa thành công',
            'admin.customers.index'
        );
    }

    public function bulkDelete(Request $request)
    {
        $customerIds = $request->input('customer_ids', []);
        try {
            DB::beginTransaction();
            $customers = Customer::whereIn('id', $customerIds)->get();
            foreach ($customers as $customer) {
                if ($customer->salesOrders()->exists()) {
                    throw new \Exception("Không thể xóa khách hàng {$customer->name} vì có đơn hàng liên quan.");
                }
                $this->customerRepo->deleteCustomer($customer);
            }
            DB::commit();
            return $this->returnInertia(['status' => true], 'Xóa hàng loạt thành công', 'admin.customers.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi xóa hàng loạt: ' . $th->getMessage());
            return $this->returnInertia(['status' => false, 'message' => $th->getMessage()], '', 'admin.customers.index');
        }
    }
}
