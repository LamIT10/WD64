<?php

namespace App\Repositories;

use App\Models\CustomerTransaction;
use Carbon\Carbon;

class CustomerTransactionRepository extends BaseRepository
{
    public function __construct(CustomerTransaction $model)
    {
        $this->handleModel = $model;
    }

    /**
     * Danh sách công nợ chưa thanh toán / còn lại
     */
    public function getOutstandingDebts(array $filters = [])
    {
        $query = $this->handleModel->query()
            ->where('remaining_amount', '>', 0)
            ->orderByDesc('credit_due_date');

        return $this->filterData($query, $filters)->get();
    }

    /**
     * Danh sách công nợ quá hạn
     */
    public function getOverdueDebts(array $filters = [])
    {
        $query = $this->handleModel->query()
            ->where('remaining_amount', '>', 0)
            ->whereDate('credit_due_date', '<', now());

        return $this->filterData($query, $filters)->get();
    }

    /**
     * Ghi nhận thanh toán vào một giao dịch công nợ
     */
    public function recordPayment(int $id, float $amount): array
    {
        $transaction = $this->handleModel->findOrFail($id);

        if ($amount <= 0) {
            return $this->returnError("Số tiền thanh toán phải lớn hơn 0.");
        }

        if ($transaction->remaining_amount <= 0) {
            return $this->returnError("Giao dịch đã được thanh toán đủ.");
        }

        $transaction->paid_amount += $amount;
        $transaction->remaining_amount = max(0, $transaction->remaining_amount - $amount);
        $transaction->transaction_date = Carbon::now();
        $transaction->save();

        return [
            'status' => true,
            'message' => 'Thanh toán thành công',
            'data' => $transaction
        ];
    }

    /**
     * Tổng hợp công nợ theo khách hàng
     */
    public function summaryByCustomer()
    {
        return $this->handleModel
            ->selectRaw('customer_id, SUM(paid_amount) as total_paid, SUM(remaining_amount) as total_debt')
            ->groupBy('customer_id')
            ->get();
    }
}
