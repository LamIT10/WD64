<?php

namespace App\Repositories;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\PurchaseOrder;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ReportRepository extends BaseRepository
{
    private PurchaseOrder $purchase;
    private GoodReceipt $goodsReceipt;
    private GoodReceiptItem $goodsReceiptItem;
    public function __construct(
        PurchaseOrder $purchaseModel,
        GoodReceipt $goodsReceiptModel,
        GoodReceiptItem $goodsReceiptItemModel
    ) {
        $this->purchase = $purchaseModel;
        $this->goodsReceipt = $goodsReceiptModel;
        $this->goodsReceiptItem = $goodsReceiptItemModel;
    }
    public function getDataForReportImport($params)
    {

        $data = [];
        $startDate = $params['start_date'] ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $params['end_date'] ?? Carbon::now()->endOfMonth()->toDateString();
        $year =  $params['year'] ?? Carbon::now()->year;
        $purchaseOrders = $this->purchase->with(['supplier', 'user'])->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $countPendingPurchase = (clone $purchaseOrders)->where('order_status', '0')->count();
        $countAcceptedPurchase = (clone $purchaseOrders)->where('order_status', '1')->count();
        $countParitalImportPurchase = (clone $purchaseOrders)->where('order_status', '2')->count();
        $countCompletedPurchase = (clone $purchaseOrders)->where('order_status', '3')->count();
        $countClosedPurchase = (clone $purchaseOrders)->where('order_status', '4')->count();

        $rawData = $this->purchase
            ->whereYear('created_at', $year)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->whereIn('order_status', [3, 4])
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->get()
            ->keyBy('month');
        $fullMonthData = collect(range(1, 12))->map(function ($month) use ($rawData, $year) {
            $key = sprintf('%d-%02d', $year, $month);
            return [
                'month' => $key,
                'count' => $rawData->get($key)->count ?? 0
            ];
        });

        $purchaseOrdersByMonth = $this->purchase
            ->whereYear('created_at', $year)
            ->whereIn('order_status', [2, 3])
            ->selectRaw("id, DATE_FORMAT(created_at, '%Y-%m') as month")
            ->get()
            ->groupBy('month');

        // Tạo ra cấu trúc giống hệt $rawDataReceipt nhưng theo logic mới
        $rawDataReceipt = collect();
        foreach ($purchaseOrdersByMonth as $month => $orders) {
            $orderIds = $orders->pluck('id')->toArray();
            if (!empty($orderIds)) {
                $total = $this->goodsReceipt
                    ->whereIn('purchase_order_id', $orderIds)
                    ->sum('total_amount');
                $rawDataReceipt->put($month, (object)[
                    'month' => $month,
                    'total' => $total
                ]);
            }
        }
        
        $fullMonthDataReceipt = collect(range(1, 12))->map(function ($month) use ($rawDataReceipt, $year) {
            $key = sprintf('%d-%02d', $year, $month);
            return [
                'month' => $key,
                'total' => $rawDataReceipt->get($key)->total ?? 0
            ];
        });
        $pluckYear = $this->goodsReceipt
            ->selectRaw('YEAR(receipt_date) as year')
            ->distinct()
            ->orderBy('year', 'desc') // hoặc 'asc' nếu muốn ngược lại
            ->pluck('year')
            ->toArray();
        $top5Suppliers = $this->goodsReceipt::with('purchaseOrder.supplier')->whereBetween('receipt_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($receipt) {
                return $receipt->purchaseOrder->supplier_id ?? null;
            })
            ->map(function ($group) {
                return [
                    'supplier_id' => $group->first()->purchaseOrder->supplier_id ?? null,
                    'total_paid' => $group->sum('total_amount'),
                    'supplier_name' => $group->first()->purchaseOrder->supplier->name ?? 'Không xác định'
                ];
            })
            ->sortByDesc('total_paid')
            ->take(5)
            ->values();

        $top10Product = $this->goodsReceiptItem
            ->selectRaw('product_variant_id, SUM(quantity_received) as total_quantity')
            ->whereHas('goodReceipt', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('receipt_date', [$startDate, $endDate]);
            })
            ->with(['productVariant.product', 'productVariant.attributes'])
            ->groupBy('product_variant_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $productName = optional($item->productVariant->product)->name ?? '';
                $attributes = optional($item->productVariant->attributes)->pluck('name')->all();
                $attributeText = count($attributes) ? ' - ' . implode(' - ', $attributes) : '';

                return [
                    'product_variant_id' => $item->product_variant_id,
                    'product_variant_name' => $productName . $attributeText,
                    'total_quantity' => $item->total_quantity,
                ];
            });

        $totalPurchaseInMonth = $this->purchase::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("COUNT(*) as total_purchase, SUM(total_amount) as total_amount")
            ->get();
        $ListIdPurchaseInMonth = $this->purchase::whereBetween('created_at', [$startDate, $endDate])
            ->pluck('id')
            ->toArray();
        $totalGoodReceiptInMonth = $this->goodsReceipt::whereIn("purchase_order_id", $ListIdPurchaseInMonth)
            ->distinct("purchase_order_id")
            ->selectRaw("COUNT(*) as total_receipt, SUM(total_amount) as total_amount")
            ->get();
        $data = [
            'purchase_orders' => [
                'count_pending_purchase' => $countPendingPurchase,
                'count_accepted_purchase' => $countAcceptedPurchase,
                'count_parital_import_purchase' => $countParitalImportPurchase,
                'count_completed_purchase' => $countCompletedPurchase,
                'count_closed_purchase' => $countClosedPurchase,
                'count_purchase_orders' => $purchaseOrders->count(),
            ],
            'purchase_in_month_in_year' => [
                'data' => $fullMonthData,
                'year' => $year,
            ],
            'receipt_count_in_month_in_year' => [
                'data' => $fullMonthDataReceipt,
                'year' => $year,
            ],
            'pluck_year' => $pluckYear,
            'top_5_suppliers' => $top5Suppliers->take(5),
            'top_10_product' => $top10Product,
            'total_purchase_in_month' => $totalPurchaseInMonth->first(),
            'total_good_receipt_in_month' => $totalGoodReceiptInMonth->first(),
        ];
        return $data;
    }
}
