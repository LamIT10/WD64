<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdateCustommerTransactionRequest;
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


    public function index(Request $request)
    {
        $customerTransaction = $this->customerTransactionRepo->getDebtSummaryByOrder();
        // dd($customerTransaction->toArray());
        return Inertia::render('admin/Customers/Transaction/Index', [
            'customerTransaction' => $customerTransaction,
        ]);
    }
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

       $customer= $this->customerTransactionRepo->updateTransaction($orderId, $validated);
        return $this-> returnInertia($customer, 'Thêm giao dịch thành công', 'admin.customer-transaction.index');
    }
    public function updateDueDate(Request $request, $orderId)
    {
        $request->validate([
            'credit_due_date' => ['required', 'date'],
        ]);

        $customer=$this->customerTransactionRepo->updateDueDate($orderId, $request->credit_due_date);

        return $this->returnInertia($customer, 'Cập nhật hạn công nợ thành công', 'admin.customer-transaction.index');
    }
}
