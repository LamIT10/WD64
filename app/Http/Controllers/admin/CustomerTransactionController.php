<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
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

    /**
     * Hiển thị danh sách công nợ (có lọc nâng cao)
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search', []),                // ví dụ: ['description' => 'đơn hàng']
            'absoluteFilter' => $request->input('absoluteFilter', []),// ví dụ: ['customer_id' => 1]
            'between' => $request->input('between', []),              // ví dụ: ['credit_due_date' => ['min' => '2025-05-01', 'max' => '2025-06-30']]
            'relation' => $request->input('relation', [])             // ví dụ: ['customer' => ['field' => 'name', 'value' => 'ABC']]
        ];

        $debts = $this->customerTransactionRepo->getOutstandingDebts($filters);

        return Inertia::render('admin/Customers/Transaction/Index', [
            'debts' => $debts,
            'filters' => $filters,
        ]);
    }

    /**
     * Ghi nhận thanh toán cho 1 công nợ
     */
    public function storePayment(StorePaymentRequest $request)
    {
        $result = $this->customerTransactionRepo->recordPayment(
            $request->transaction_id,
            $request->amount
        );

        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    /**
     * Danh sách công nợ quá hạn
     */
    public function overdue(Request $request)
    {
        $filters = $request->only(['search', 'absoluteFilter', 'between', 'relation']);

        $debts = $this->customerTransactionRepo->getOverdueDebts($filters);

        return Inertia::render('Debt/Overdue', [
            'debts' => $debts,
            'filters' => $filters,
        ]);
    }

    /**
     * Tổng hợp công nợ theo khách hàng
     */
    public function summary()
    {
        $summary = $this->customerTransactionRepo->summaryByCustomer();

        return Inertia::render('Debt/Summary', [
            'summary' => $summary,
        ]);
    }
}
