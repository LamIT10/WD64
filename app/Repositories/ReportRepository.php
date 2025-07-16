<?php

namespace App\Repositories;

use App\Models\GoodReceipt;
use App\Models\PurchaseOrder;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ReportRepository extends BaseRepository
{
    private PurchaseOrder $purchase;
    private GoodReceipt $goodsReceipt;
    public function __construct(PurchaseOrder $purchaseModel, GoodReceipt $goodsReceiptModel)
    {
        $this->purchase = $purchaseModel;
        $this->goodsReceipt = $goodsReceiptModel;
    }
    public function getDataForReportImport($params)
    {

        $data = [];
        $startDate = $params['start_date'] ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $params['end_date'] ?? Carbon::now()->endOfMonth()->toDateString();
        $year =  $params['year'] ?? Carbon::now()->year;
        $purchaseOrders = $this->purchase->with(['supplier', 'user'])->whereBetween('order_date', [$startDate, $endDate])
            ->get();
        $countPendingPurchase = (clone $purchaseOrders)->where('order_status', '0')->count();
        $countAcceptedPurchase = (clone $purchaseOrders)->where('order_status', '1')->count();
        $countParitalImportPurchase = (clone $purchaseOrders)->where('order_status', '2')->count();
        $countCompletedPurchase = (clone $purchaseOrders)->where('order_status', '3')->count();
        $countClosedPurchase = (clone $purchaseOrders)->where('order_status', '4')->count();

        $rawData = $this->purchase
        ->whereYear('order_date', $year)
        ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as count")
        ->groupByRaw("DATE_FORMAT(order_date, '%Y-%m')")
        ->orderByRaw("DATE_FORMAT(order_date, '%Y-%m')")
        ->get()
        ->keyBy('month');
        $fullMonthData = collect(range(1, 12))->map(function ($month) use ($rawData, $year) {
            $key = sprintf('%d-%02d', $year, $month);
            return [
                'month' => $key,
                'count' => $rawData->get($key)->count ?? 0
            ];
        });
        
        $rawDataReceipt = $this->goodsReceipt
        ->whereYear('receipt_date', $year)
        ->selectRaw("DATE_FORMAT(receipt_date, '%Y-%m') as month, sum(total_amount) as total")
        ->groupByRaw("DATE_FORMAT(receipt_date, '%Y-%m')")
        ->orderByRaw("DATE_FORMAT(receipt_date, '%Y-%m')")
        ->get()
        ->keyBy('month');
        $pluckYear = $this->goodsReceipt
        ->selectRaw('YEAR(receipt_date) as year')
        ->distinct()
        ->orderBy('year', 'desc') // hoặc 'asc' nếu muốn ngược lại
        ->pluck('year')
        ->toArray();
    
        $fullMonthDataReceipt = collect(range(1, 12))->map(function ($month) use ($rawDataReceipt, $year) {
            $key = sprintf('%d-%02d', $year, $month);
            
            return [
                'month' => $key,
                'total' => $rawDataReceipt->get($key)->total ?? 0
            ];
        });
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
        ];
        return $data;
    }
}
