<?php

namespace App\Http\Controllers\admin;

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
   
    public function update(UpdateCustommerTransactionRequest $request, $id)
{
    $this->customerTransactionRepo->updateTransaction($id, $request->validated());

    return redirect()->back()->with('success', 'Cập nhật công nợ thành công.');
}
}
