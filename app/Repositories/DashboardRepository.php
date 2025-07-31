<?php

namespace App\Repositories;

use App\Models\GoodReceipt;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository extends BaseRepository
{
    protected  ProductRepository $product;
    public function __construct(ProductRepository $productRepo)
    {
        $this->product = $productRepo;
    }
    // tổng giá trị bán ra của toàn bộ sản phẩm tồn kho
    private int $sumProductInventoryValue = 0;
    public function getDataForDashBoard($query = [])
    {
        $current = Carbon::now();
        $sevenDayAgo = Carbon::now()->subDays(7);
        // tổng giá trị bán ra của toàn bộ sản phẩm tồn kho
        Product::with('productVariants')->get()->map(function ($item, $sumProductInventoryValue) {
            $this->sumProductInventoryValue =   $this->sumProductInventoryValue + array_sum($item->productVariants->pluck('sale_price')->toArray());
        });

        // tổng số lượng sản phẩm trong tình trạng số lượng tồn kho nhỏ hơn min_stock
        $productIsOutOfStock =  $this->product->getAll($filter = [
            'stock_status' => 'low_stock',
            'get_count' => true,
        ]);


        // lấy ra thông kê đơn xuất theo tháng đơn nhập
        // $currentMonth = Carbon::now()->month;
        // $currentYear = Carbon::now()->year;
        // $totalPurchaseInMonth = PurchaseOrder::select(['id','created_at', 'total_amount']);
        // if (!empty($query['fromDatePurchase']) || !empty($query['toDatePurchase'])) {
        //     if (!empty($query['fromDatePurchase'])) {
        //         $totalPurchaseInMonth->where('created_at', ">=", $query['fromDatePurchase']);
        //     }
        //     if (!empty($query['toDatePurchase'])) {
        //         $totalPurchaseInMonth->where('created_at', "<=", $query['toDatePurchase']);
        //     }
        // } else {
        //     $totalPurchaseInMonth->whereMonth('created_at', $currentMonth)
        //         ->whereYear('created_at', $currentYear);
        // }
        // // Đếm tổng số đơn
        // $countPurchaseInMonth = (clone $totalPurchaseInMonth)->count();

        // // Đếm theo từng trạng thái
        // $countPurchaseInMonthPending = (clone $totalPurchaseInMonth)->where('order_status', '0')->count();
        // $countPurchaseInMonthAccepted    = (clone $totalPurchaseInMonth)->where('order_status', '1')->count();
        // $countPurchaseInMonthClosed = (clone $totalPurchaseInMonth)->where('order_status', '4')->count();
        // $countPurchaseInMonthImportPartial = (clone $totalPurchaseInMonth)->where('order_status', '2')->count();
        // $countPurchaseInMonthCompoleted = (clone $totalPurchaseInMonth)->where('order_status', '3')->count();

        // $sumValueGoodReceiptInMonth = GoodReceipt::select(['purchase_order_id', 'total_amount'])
        // ->whereHas('purchaseOrder', function ($query) {
        //     $query->whereIn('order_status', [2, 3]); // hoặc status bạn cần
        // })
        // ->whereIn('purchase_order_id', $totalPurchaseInMonth->pluck('id')->toArray())
        // ->get()
        // ->sum('total_amount');
        // lấy ra thay các đơn hàng được tạo trong 7 ngày
        // dd($sevenDayAgo->startOfDay());
        $purchaseChangeInSevenDay = PurchaseOrder::select(DB::raw("Date(order_date) as date"),DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('order_date', [$sevenDayAgo->startOfDay(), $current->endOfDay()])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get()->toArray();

        // lấy ra thông kê đơn xuất theo tháng đơn xuất
        // $totalSaleOrderInMonth = SaleOrder::select(['created_at', 'total_amount']);
        // if (!empty($query['fromDateSaleOrder']) || !empty($query['toDateSaleOrder'])) {
        //     if (!empty($query['fromDateSaleOrder'])) {
        //         $totalSaleOrderInMonth->where('created_at', ">=", $query['fromDateSaleOrder']);
        //     }
        //     if (!empty($query['toDateSaleOrder'])) {
        //         $totalSaleOrderInMonth->where('created_at', "<=", $query['toDateSaleOrder']);
        //     }
        // } else {
        //     $totalSaleOrderInMonth->whereMonth('created_at', $currentMonth)
        //         ->whereYear('created_at', $currentYear);
        // }

        // // Đếm tổng số đơn
        // $countSaleOrderInMonth = (clone $totalSaleOrderInMonth)->count();

        // // Đếm theo từng trạng thái
        // $countSaleOrderInMonthPending = (clone $totalSaleOrderInMonth)->where('status', 'pending')->count();
        // $countSaleOrderInMonthShipped = (clone $totalSaleOrderInMonth)->where('status', 'shipped')->count();
        // $countSaleOrderInMonthClosed = (clone $totalSaleOrderInMonth)->where('status', 'closed')->count();

        // // Tổng giá trị
        // $sumValueSaleOrderInMonth = (clone $totalSaleOrderInMonth)->where('status', 'shipped')->sum('total_amount');
        // lấy ra thay các đơn hàng được tạo trong 7 ngày
        $saleOrderChangeInSevenDay = SaleOrder::select(DB::raw("Date(order_date) as date"), DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('order_date', [$sevenDayAgo->startOfDay(), $current->endOfDay()])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get()->toArray();



        $data = [
            'count_product' => Product::get()->count(), // số lượng sản phẩm trong kho
            'sum_product_inventory_value' => $this->sumProductInventoryValue,
            'product_is_out_of_stock' => $productIsOutOfStock,
            'statistical_purchases' => [
                // 'count_purchase_in_month' => $countPurchaseInMonth,
                // 'sum_value_good_receipt_in_month' => $this->formatNumberInt($sumValueGoodReceiptInMonth) . " ₫",
                // 'count_purchase_in_month_pending' => $countPurchaseInMonthPending,
                // 'count_purchase_in_month_accepted' => $countPurchaseInMonthAccepted,
                // 'count_purchase_in_month_closed' => $countPurchaseInMonthClosed,
                // 'count_purchase_in_month_import_partial' => $countPurchaseInMonthImportPartial,
                // 'count_purchase_in_month_completed' => $countPurchaseInMonthCompoleted,
                'purchase_change_in_seven_day' => $purchaseChangeInSevenDay,
            ],
            'statistical_sale_order' => [
                // 'count_sale_order_in_month' => $countSaleOrderInMonth,
                // 'count_sale_order_in_month_pending' => $countSaleOrderInMonthPending,
                // 'count_sale_order_in_month_shipped' => $countSaleOrderInMonthShipped,
                // 'count_sale_order_in_month_closed' => $countSaleOrderInMonthClosed,
                // 'sum_value_sale_order_in_month' => $this->formatNumberInt($sumValueSaleOrderInMonth) . " ₫",
                'sale_order_change_in_seven_day' => $saleOrderChangeInSevenDay,
            ],
            'top_10_product_variants' => [
                'week' => $this->getTop10ProductVariants('week'),
                'month' => $this->getTop10ProductVariants('month'),
                'year' => $this->getTop10ProductVariants('year'),
            ],
            'top_10_customers' => [
                'week' => $this->getTop10Customers('week'),
                'month' => $this->getTop10Customers('month'),
                'year' => $this->getTop10Customers('year'),
            ],
            'net_revenue' => [
                'daily' => $this->getNetRevenueStats('daily'),
                'weekly' => $this->getNetRevenueStats('weekly'),
                'monthly' => $this->getNetRevenueStats('monthly'),
                'quarterly' => $this->getNetRevenueStats('quarterly'),
            ],

            'inventory_by_paper_type' => [
                '7days' => $this->getInventoryByPaperType('7days'),
                '30days' => $this->getInventoryByPaperType('30days'),
                '90days' => $this->getInventoryByPaperType('90days'),
            ],
         

            'low_stock_items' => $this->getLowStockItems(10),

        ];


        return $data;
    }

    private function getTop10ProductVariants($type = 'month')
    {
        $startDate = match ($type) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        return DB::table('sale_order_items')
            ->join('sale_orders', 'sale_order_items.sales_order_id', '=', 'sale_orders.id')
            ->join('product_variants', 'sale_order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('units as base_unit', 'products.default_unit_id', '=', 'base_unit.id')
            ->leftJoin('product_unit_conversions as puc', function ($join) {
                $join->on('products.id', '=', 'puc.product_id')
                    ->on('sale_order_items.unit_id', '=', 'puc.from_unit_id')
                    ->on('products.default_unit_id', '=', 'puc.to_unit_id');
            })
            ->where('sale_orders.order_date', '>=', $startDate)
            ->select(
                'product_variants.id as variant_id',
                'product_variants.code as variant_code',
                DB::raw("CONCAT(products.name, ' - ', product_variants.code) AS full_variant_name"),
                'base_unit.name as base_unit_name',
                DB::raw("
                SUM(
                    CASE 
                        WHEN puc.conversion_factor IS NOT NULL THEN sale_order_items.quantity_ordered * puc.conversion_factor
                        ELSE sale_order_items.quantity_ordered
                    END
                ) as total_quantity
            "),
                DB::raw('SUM(sale_order_items.subtotal) as total_revenue')
            )
            ->groupBy(
                'product_variants.id',
                'product_variants.code',
                'products.name',
                'base_unit.name'
            )
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
    }


    private function getTop10Customers($type = 'month')
    {
        $startDate = match ($type) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        return DB::table('sale_orders')
            ->join('customers', 'sale_orders.customer_id', '=', 'customers.id')
            ->where('sale_orders.order_date', '>=', $startDate)
            ->where('sale_orders.status', '=', 'completed') 
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                DB::raw('COUNT(sale_orders.id) as order_count'),
                DB::raw('SUM(sale_orders.total_amount) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();
    }


    public function getNetRevenueStats($range = 'weekly')
    {
        $query = DB::table('sale_orders')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->where('status', 'completed'); // chỉ lấy đơn đã hoàn thành

        $today = Carbon::now();

        switch ($range) {
            case 'daily':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();

                $results = DB::table('sale_orders')
                    ->selectRaw("DATE(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('order_date', '<=', $end)
                    ->where('status', 'completed')
                    ->groupBy(DB::raw("DATE(order_date)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'monthly':
                $start = $today->copy()->startOfYear();
                $results = DB::table('sale_orders')
                    ->selectRaw("MONTH(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('status', 'completed')
                    ->groupBy(DB::raw("MONTH(order_date)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'quarterly':
                $start = $today->copy()->startOfYear();
                $results = DB::table('sale_orders')
                    ->selectRaw("QUARTER(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('status', 'completed')
                    ->groupBy(DB::raw("QUARTER(order_date)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'weekly':
            default:
                $start = $today->copy()->subWeeks(3)->startOfWeek(); // lấy 4 tuần gần nhất
                $results = DB::table('sale_orders')
                    ->selectRaw("DATE_FORMAT(order_date, '%x-W%v') as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('status', 'completed')
                    ->groupBy(DB::raw("DATE_FORMAT(order_date, '%x-W%v')"))
                    ->orderBy('label')
                    ->get();
                break;
        }

        // Tính số liệu thống kê
        $values = $results->pluck('revenue')->map(fn($v) => (float) $v)->toArray();
        $labels = $results->pluck('label')->toArray();

        $current = end($values);
        $last = count($values) > 1 ? $values[count($values) - 2] : 0;
        $change = $last > 0 ? round((($current - $last) / $last) * 100, 1) : 0;

        return [
            'labels' => $labels ?? [],
            'values' => count($labels) === count($values) ? $values : array_fill(0, count($labels), 0),
            'current' => $current ?: 0,
            'previous' => $last ?: 0,
            'percent_change' => is_numeric($change) ? $change : 0,
        ];
    }
    // Lấy thống kê tồn kho theo loại giấy
    public function getInventoryByPaperType($range = '90days')
    {
        $startDate = match ($range) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            default => now()->subDays(90),
        };

        return DB::table('inventory')
            ->join('product_variants', 'inventory.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('product_unit_conversions as puc', function ($join) {
                $join->on('products.id', '=', 'puc.product_id')
                    ->on('inventory.unit_id', '=', 'puc.from_unit_id')
                    ->on('products.default_unit_id', '=', 'puc.to_unit_id');
            })
            ->leftJoin('units as default_units', 'products.default_unit_id', '=', 'default_units.id')
            ->where('inventory.updated_at', '>=', $startDate)
            ->select(
                'products.name as paper_type',
                DB::raw("
                SUM(
                    CASE 
                        WHEN puc.conversion_factor IS NOT NULL 
                            THEN inventory.quantity_on_hand * puc.conversion_factor
                        ELSE inventory.quantity_on_hand
                    END
                ) as total_quantity
            "),
                DB::raw('MAX(default_units.symbol) as unit')
            )
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->get();
    }

    // Lấy danh sách sản phẩm có số lượng tồn kho thấp hơn ngưỡng nhất định
public function getLowStockItems($threshold = 10, $limit = 5)
{
    return DB::table('inventory')
        ->join('product_variants', 'inventory.product_variant_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->leftJoin('units', 'inventory.unit_id', '=', 'units.id')
        ->where('inventory.quantity_on_hand', '<=', $threshold)
        ->select(
            'product_variants.id',
            'product_variants.code',
            'products.name as product_name',
            'inventory.quantity_on_hand',
            'units.symbol as unit'
        )
        ->orderBy('inventory.quantity_on_hand', 'asc')
        ->limit($limit)
        ->get();
}
public function getMonthlyExportStatsByYear($year)
{
    return DB::table('sale_orders')
        ->selectRaw('MONTH(order_date) as month, COUNT(*) as total_orders, SUM(total_amount) as total_amount')
        ->whereYear('order_date', $year)
        ->where('status', 'completed') 
        ->groupBy(DB::raw('MONTH(order_date)'))
        ->orderBy('month')
        ->get();
}
public function getExportStatusSummaryByMonth($month)
{
    $query = DB::table('sale_orders')
        ->select('status', DB::raw('COUNT(*) as total'))
        ->whereMonth('order_date', $month);

    // Nếu là tháng hiện tại thì lấy đủ 4 trạng thái
    if ((int) $month === (int) now()->month) {
        $query->whereIn('status', ['pending', 'shipped', 'completed', 'cancelled']);
    } else {
        $query->whereIn('status', ['completed', 'cancelled']);
    }

    $results = $query->groupBy('status')->pluck('total', 'status');

    return [
        'month' => (int) $month,
        'data' => [
            'pending'   => $results['pending'] ?? 0,
            'shipped'   => $results['shipped'] ?? 0,
            'completed' => $results['completed'] ?? 0,
            'cancelled' => $results['cancelled'] ?? 0,
        ]
    ];
}

}
