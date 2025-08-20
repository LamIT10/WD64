<?php

namespace App\Repositories;

use App\Models\SaleOrder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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


            if ($remaining <= 0 && $order->transactions->isEmpty()) {
                return null;
            }

            $dueDate = $order->credit_due_date
                ? Carbon::parse($order->credit_due_date)
                : Carbon::parse($order->created_at)->addDays(30);


            $daysUntilDue = $dueDate->diffInDays(now(), false);


            $status = match (true) {
                $remaining <= 0 => 'Đã thanh toán',
                $daysUntilDue > 0 => 'Đã quá hạn',
                $daysUntilDue >= -7 => 'Sắp quá hạn',
                default => 'Chưa thanh toán',
            };

            return [
                'id' => $order->id,
                'code' => $order->code,
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
            'transactions.creator',
            'items' => function ($query) {
                $query->with([
                    'productVariant' => function ($q) {
                        $q->with([
                            'product:id,name',
                            'attributes:id,name'
                        ]);
                    },
                    'unit:id,name'
                ]);
            }
        ])->findOrFail($orderId);

        $total = $order->total_amount;

        $paid = ($order->pay_before ?? 0) + ($order->pay_after ?? 0)
            + $order->transactions->where('type', 'payment')->sum('paid_amount');

        $remaining = $total - $paid;

        $dueDate = $order->credit_due_date
            ? Carbon::parse($order->credit_due_date)
            : Carbon::parse($order->created_at)->addDays(30);

        $daysUntilDue = $dueDate->diffInDays(now(), false);

        $status = match (true) {
            $remaining <= 0 => 'Đã thanh toán',
            $daysUntilDue > 0 => 'Đã quá hạn',
            $daysUntilDue >= -7 => 'Sắp quá hạn',
            default => 'Chưa thanh toán',
        };

        return [
            'id' => $order->id,
            'order_code' => $order->code,
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
                $productName = optional($item->productVariant?->product)->name ?? 'Không xác định';
                $variantAttributes = $item->productVariant?->attributes->pluck('name')->toArray() ?? [];
                $variantName = implode(' - ', $variantAttributes);
                $fullName = trim($productName . ' - ' . $variantName);

                return [
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $fullName,
                    'quantity_ordered' => $item->quantity_ordered,
                    'quantity_shipped' => $item->quantity_shipped,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'unit_name' => optional($item->unit)->name ?? '-',
                ];
            }),
            'history' => $order->transactions
                ->filter(fn($t) => in_array($t->type, ['payment', 'adjustment']))
                ->sortByDesc(fn($t) => $t->transaction_date ?? $t->created_at)
                ->values()
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'type' => $t->type,
                        'paid_amount' => $t->type === 'payment' ? $t->paid_amount : null,
                        'credit_due_date' => $t->type === 'adjustment' && $t->credit_due_date
                            ? Carbon::parse($t->credit_due_date)->format('Y-m-d')
                            : null,
                        'transaction_date' => $t->transaction_date
                            ? Carbon::parse($t->transaction_date)->format('Y-m-d')
                            : null,
                        'created_at' => $t->created_at
                            ? Carbon::parse($t->created_at)->format('Y-m-d')
                            : null,
                        'note' => $t->description,
                        'proof_image' => $t->proof_image,
                        'creator' => $t->creator ? [
                            'id' => $t->creator->id,
                            'name' => $t->creator->name,
                            'email' => $t->creator->email,
                        ] : null,
                    ];
                }),

        ];
    }
    public function updateTransaction($orderId, array $data)
    {
        $order = SaleOrder::findOrFail($orderId);
        $newPaid = $data['paid_amount'];

        if ($newPaid <= 0) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Số tiền thanh toán phải lớn hơn 0.'
            ]);
        }

        if (empty($data['transaction_date']) || !strtotime($data['transaction_date'])) {
            throw ValidationException::withMessages([
                'transaction_date' => 'Ngày thanh toán không hợp lệ.'
            ]);
        }

        $total = $order->total_amount;

        $paidSoFar = ($order->pay_before ?? 0)
            + ($order->pay_after ?? 0)
            + $order->transactions()->where('type', 'payment')->sum('paid_amount');

        $remaining = $total - $paidSoFar;

        if ($newPaid > $remaining) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Số tiền thanh toán vượt quá số tiền còn nợ.'
            ]);
        }

        return DB::transaction(function () use ($order, $data) {
            $imagePath = null;
            if (!empty($data['file']) && ($data['payment_method'] ?? null) === 'bank_transfer') {
                $imagePath = $this->handleUploadOneFile($data['file']);
            }
            return $order->transactions()->create([
                'type' => 'payment',
                'paid_amount' => $data['paid_amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => $data['description'] ?? null,
                'created_id' => Auth::id(),
                'proof_image' => $imagePath,
            ]);
        });
    }
    public function updateDueDate($orderId, string $newDueDate)
    {
        $order = SaleOrder::findOrFail($orderId);

        if (!strtotime($newDueDate)) {
            throw ValidationException::withMessages([
                'credit_due_date' => 'Ngày hết hạn không hợp lệ.'
            ]);
        }

        if (Carbon::parse($newDueDate)->lt(Carbon::now())) {
            throw ValidationException::withMessages([
                'credit_due_date' => 'Ngày hết hạn không thể là quá khứ.'
            ]);
        }

        return DB::transaction(function () use ($order, $newDueDate) {
            $order->credit_due_date = $newDueDate;
            $order->save();

            $order->transactions()->create([
                'type' => 'adjustment',
                'paid_amount' => 0,
                'transaction_date' => now(),
                'credit_due_date' => $newDueDate,
                'description' => 'Điều chỉnh hạn thanh toán',
                'created_id' => Auth::user()->id,
            ]);

            return $order;
        });
    }
}
