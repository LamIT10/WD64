<?php

namespace App\Repositories;

use App\Models\SaleOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerTransactionRepository extends BaseRepository
{
    public function getDebtSummaryByOrder($filter = [], $limit = 10)
    {
        $query = SaleOrder::with(['customer', 'latestTransaction'])
            ->where('status', 'shipped') // chỉ lấy đơn đã giao hàng
            ->withSum('transactions as total_paid', 'paid_amount') // gộp tiền đã trả
            ->orderBy('created_at', 'desc');


        $paginated = $query->paginate($limit);

        return $paginated->through(function ($order) {
            $total = $order->total_amount ?? 0;
            $paid = $order->total_paid ?? 0;
            $remaining = $total - $paid;

            // tính trạng thái công nợ
            $dueDate = optional($order->latestTransaction)->credit_due_date
                ? Carbon::parse($order->latestTransaction->credit_due_date)
                : Carbon::parse($order->created_at)->addDays(30);

            $isOverdue = $dueDate->lt(now()) && $remaining > 0;
            $status = match (true) {
                $remaining <= 0 => 'Đã thanh toán',
                $isOverdue => 'Quá hạn',
                default => 'Còn nợ'
            };
            return [
                'id' => $order->id,
                'order_code' => $order->order_code ?? null,
                'total_amount' => $total,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'credit_due_date' => $dueDate,
                'transaction_date' => optional($order->latestTransaction)->transaction_date,
                'status' => $status,
                'customer' => [
                    'name' => $order->customer->name ?? null,
                    'phone' => $order->customer->phone ?? null,
                ],
            ];
        });
    }

    public function updateTransaction($id, array $data)
    {
        $transaction = $this->handleModel->findOrFail($id);

        $transaction->update([
            'paid_amount' => $data['paid_amount'],
            'credit_due_date' => $data['credit_due_date'],
            'transaction_date' => $data['transaction_date'],
        ]);

        return $transaction;
    }
}
