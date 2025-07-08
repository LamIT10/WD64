<?php

namespace App\Repositories;

use App\Models\SaleOrder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerTransactionRepository extends BaseRepository
{


    public function getDebtSummaryByOrder($filter = [], $limit = 10, $customerId = null)
    {
        $orders = SaleOrder::with(['customer', 'latestTransaction', 'transactions'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

            
    if ($customerId) {
        $orders = $orders->where('customer_id', $customerId);
    }


        $filtered = $orders->map(function ($order) {
            $total = $order->total_amount ?? 0;

            $paid = ($order->pay_before ?? 0)
                + ($order->pay_after ?? 0)
                + $order->transactions->where('type', 'payment')->sum('paid_amount');

            $remaining = $total - $paid;

            if ($remaining <= 0) return null;

            $dueDate = $order->credit_due_date
                ? Carbon::parse($order->credit_due_date)
                : Carbon::parse($order->created_at)->addDays(30);

            $isOverdue = $dueDate->lt(now());

            $status = $isOverdue ? 'Đã quá hạn' : 'Chưa thanh toán';

            return [
                'id' => $order->id,
                'total_amount' => round($total),
                'paid_amount' => round($paid),
                'remaining_amount' => round($remaining),
                'credit_due_date' => $dueDate,
                'transaction_date' => optional($order->latestTransaction)->transaction_date,
                'status' => $status,
                'customer' => [
                    'name' => $order->customer->name ?? null,
                    'phone' => $order->customer->phone ?? null,
                ],
            ];
        })->filter()->values(); // loại null

        $page = request()->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $limit),
            $filtered->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginated;
    }


    public function getDebtSummaryByCustomer()
    {
        $customers = Customer::with(['salesOrders.transactions'])->get();

        return $customers->map(function ($customer) {
            $orders = $customer->salesOrders;

            $totalAmount = 0;
            $totalPaid = 0;
            $isOverdue = false;

            foreach ($orders as $order) {
                if ($order->status !== 'completed') {
                    continue;
                }

                $totalAmount += $order->total_amount ?? 0;

                $paid = ($order->pay_before ?? 0) + ($order->pay_after ?? 0)
                    + $order->transactions->where('type', 'payment')->sum('paid_amount');

                $totalPaid += $paid;

                $remaining = $order->total_amount - $paid;

                $dueDate = $order->credit_due_date
                    ? Carbon::parse($order->credit_due_date)
                    : Carbon::parse($order->created_at)->addDays(30);

                if ($remaining > 0 && $dueDate->lt(now())) {
                    $isOverdue = true;
                }
            }

            $remainingAmount = $totalAmount - $totalPaid;

            if ($totalAmount == 0) return null;

            $status = match (true) {
                $remainingAmount <= 0 => 'Đã thanh toán',
                $isOverdue => 'Đã quá hạn',
                default => 'Chưa thanh toán',
            };

            return [
                'customer_id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'total_amount' => round($totalAmount),
                'paid_amount' => round($totalPaid),
                'remaining_amount' =>  round($remainingAmount),
                'status' => $status,
            ];
        })->filter()->sortByDesc('remaining_amount')->values();
    }

    public function getDebtDetailByOrderId($orderId)
    {
        $order = SaleOrder::with([
            'customer',
            'transactions',
            'items.productVariant.product',
            'items.unit'
        ])->findOrFail($orderId);

        $total = $order->total_amount;

        $paid = ($order->pay_before ?? 0) + ($order->pay_after ?? 0)
            + $order->transactions->where('type', 'payment')->sum('paid_amount');

        $remaining = $total - $paid;

        $dueDate = $order->credit_due_date
            ? Carbon::parse($order->credit_due_date)
            : Carbon::parse($order->created_at)->addDays(30);

        $isOverdue = $dueDate->lt(now()) && $remaining > 0;

        $status = match (true) {
            $remaining <= 0 => 'Đã thanh toán',
            $isOverdue => 'Đã quá hạn',
            default => 'Chưa thanh toán',
        };

        return [
            'id' => $order->id,
            'order_code' => 'DH' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'remaining_amount' => $remaining,
            'status' => $status,
            'credit_due_date' => $dueDate,
            'transaction_date' => optional($order->transactions->last())->transaction_date,
            'customer' => [
                'name' => $order->customer->name ?? null,
                'phone' => $order->customer->phone ?? null,
                'email' => $order->customer->email ?? null,
            ],
            'items' => $order->items->map(function ($item) {
                return [
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => optional($item->productVariant->product)->name ?? 'Không xác định',
                    'quantity_ordered' => $item->quantity_ordered,
                    'quantity_shipped' => $item->quantity_shipped,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'unit_name' => optional($item->unit)->name ?? '-',
                ];
            }),
            'payment_history' => $order->transactions
                ->where('type', 'payment')
                ->sortByDesc('transaction_date')
                ->values()
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'paid_amount' => $t->paid_amount,
                        'transaction_date' => $t->transaction_date,
                        'note' => $t->description,
                    ];
                }),
            'adjustment_history' => $order->transactions
                ->where('type', 'adjustment')
                ->sortByDesc('transaction_date')
                ->values()
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'type' => $t->type,
                        'credit_due_date' => $t->credit_due_date,
                        'transaction_date' => $t->transaction_date,
                        'note' => $t->description,
                    ];
                }),
        ];
    }

    public function updateTransaction($orderId, array $data)
    {
        try {
            DB::beginTransaction();

            $order = SaleOrder::findOrFail($orderId);
            $newPaid = $data['paid_amount'];

            if ($newPaid <= 0) {
                throw new \Exception('Số tiền thanh toán phải lớn hơn 0.');
            }

            if (empty($data['transaction_date']) || !strtotime($data['transaction_date'])) {
                throw new \Exception('Ngày thanh toán không hợp lệ.');
            }

            $total = $order->total_amount;
            $paidSoFar = ($order->pay_before ?? 0)
                + $order->transactions()->where('type', 'payment')->sum('paid_amount');

            if (($paidSoFar + $newPaid) > $total) {
                throw new \Exception('Tổng thanh toán (' . ($paidSoFar + $newPaid) . ') vượt quá giá trị công nợ (' . $total . ').');
            }

            $transaction = $order->transactions()->create([
                'type' => 'payment',
                'paid_amount' => $newPaid,
                'transaction_date' => $data['transaction_date'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();
            return $transaction;
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }
    }

    public function updateDueDate($orderId, string $newDueDate)
    {
        try {
            DB::beginTransaction();

            $order = SaleOrder::findOrFail($orderId);

            if (!strtotime($newDueDate)) {
                throw new \Exception('Ngày hết hạn không hợp lệ.');
            }
            if (Carbon::parse($newDueDate)->lt(Carbon::now())) {
                throw new \Exception('Ngày hết hạn không thể là quá khứ.');
            }

            $order->credit_due_date = $newDueDate;
            $order->save();

            $order->transactions()->create([
                'type' => 'adjustment',
                'paid_amount' => 0,
                'transaction_date' => now(),
                'credit_due_date' => $newDueDate,
                'description' => 'Điều chỉnh hạn thanh toán',
            ]);

            DB::commit();
            return $order;
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }
    }

    
}
