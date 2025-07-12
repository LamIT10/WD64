<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdateCustommerTransactionRequest;
use App\Models\Customer;
use App\Repositories\CustomerTransactionRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerTransactionController extends Controller
{
    protected $customerTransactionRepo;

    public function __construct(CustomerTransactionRepository $customerTransactionRepo)
    {
        $this->customerTransactionRepo = $customerTransactionRepo;
    }


    // public function index(Request $request)
    // {
    //     $customerTransaction = $this->customerTransactionRepo->getDebtSummaryByOrder();
    //     $totalforCustomer = $this->customerTransactionRepo->getDebtSummaryByCustomer();
    //     // dd($totalforCustomer->toArray());
    //     // dd($customerTransaction->toArray());
    //     return Inertia::render('admin/Customers/Transaction/Index', [
    //         'customerTransaction' => $customerTransaction,
    //     ]);
    // }
    public function show($orderId)
    {
        $detail = $this->customerTransactionRepo->getDebtDetailByOrderId($orderId);
        // dd($detail);
        return Inertia::render('admin/Customers/Transaction/ShowTransaction', [
            'debt' => $detail,
        ]);
    }

    public function addTransaction(Request $request, $orderId)
    {
        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:250'],
        ]);

        $customer = $this->customerTransactionRepo->updateTransaction($orderId, $validated);
        return redirect()->back()->with('success', 'Cập nhật thanh toán thành công');
    }
    public function updateDueDate(Request $request, $orderId)
    {
        $request->validate([
            'credit_due_date' => ['required', 'date'],
        ]);

        $customer = $this->customerTransactionRepo->updateDueDate($orderId, $request->credit_due_date);

        return redirect()->back()->with('success', 'Cập nhật hạn công nợ thành công');
    }
    public function debtOrdersByCustomer($customerId)
    {
        $orders = $this->customerTransactionRepo->getDebtSummaryByOrder([], 1000, $customerId);

        $customer = Customer::findOrFail($customerId);

        return Inertia::render('admin/Customers/Transaction/Index', [
            'customer' => $customer,
            'customerTransaction' => $orders,
        ]);
    }
}
