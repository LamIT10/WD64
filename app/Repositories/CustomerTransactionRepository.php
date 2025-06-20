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
            // Cộng dồn tất cả các giao dịch 
            ->withSum('transactions as total_paid', 'paid_amount')
            ->orderBy('created_at', 'desc');

        $paginated = $query->paginate($limit);

        return $paginated->through(function ($order) {
            $total = $order->total_amount ?? 0;
            $paid = $order->total_paid ?? 0;
            $remaining = $total - $paid;

            //  Tính hạn trả nợ từ đơn hàng (nếu có cột riêng credit_due_date trong sale_orders)
            $dueDate = $order->credit_due_date
                ? Carbon::parse($order->credit_due_date)
                : Carbon::parse($order->created_at)->addDays(30);

            $isOverdue = $dueDate->lt(now()) && $remaining > 0;

            // Trạng thái công nợ
            $status = match (true) {
                $remaining <= 0 => 'Đã thanh toán',
                $isOverdue => 'Đã quá hạn',
                default => 'Chưa thanh toán',
            };

            return [
                'id' => $order->id,
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


    public function getDebtDetailByOrderId($orderId)
    {
        $order = SaleOrder::with([
            'customer',
            'transactions',
            'items.productVariant.product', // load đến cả tên sản phẩm nếu cần
            'items.unit'
        ])->findOrFail($orderId);

        $total = $order->total_amount ?? 0;
        $paid = $order->transactions->sum('paid_amount');
        $remaining = $total - $paid;

           $dueDate = $order->credit_due_date
                ? Carbon::parse($order->credit_due_date)
                : Carbon::parse($order->created_at)->addDays(30);

            $isOverdue = $dueDate->lt(now()) && $remaining > 0;

            //  Trạng thái công nợ
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
            'status' => $status,
            'remaining_amount' => $remaining,
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
                ->whereNotNull('paid_amount')
                ->where('paid_amount', '>', 0)
                ->sortByDesc('transaction_date') // sắp xếp mới nhất trước
                ->values()
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'paid_amount' => $t->paid_amount,
                        'transaction_date' => $t->transaction_date,
                        'note' => $t->description,
                    ];
                }),
        ];
    }




    public function updateTransaction($orderId, array $data)
    {
        $order = SaleOrder::findOrFail($orderId);
        $newPaid = $data['paid_amount'];
        $total = $order->total_amount;

        //  Tính tổng đã thanh toán trước đó
        $paidSoFar = $order->transactions()->sum('paid_amount');

        //  Kiểm tra tổng thanh toán không vượt quá giá trị đơn hàng
        if (($paidSoFar + $newPaid) > $total) {
            throw new \Exception('Tổng thanh toán vượt quá giá trị đơn hàng.');
        }

        //  Tạo giao dịch thanh toán mới
        return $order->transactions()->create([
            'paid_amount' => $newPaid,
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function updateDueDate($orderId, string $newDueDate)
    {
        $order = SaleOrder::findOrFail($orderId);
        $order->credit_due_date = $newDueDate;
        $order->save();
        return $order;
    }
}
