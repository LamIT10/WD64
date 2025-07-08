<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\GoodReceipt;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class DashboardRepository extends BaseRepository
{

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
        $productIsOutOfStock = Product::select('products.*')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->join('inventory', 'inventory.product_variant_id', '=', 'product_variants.id')
            ->selectRaw('products.*, SUM(inventory.quantity_on_hand) as total_qty')
            ->groupBy('products.id', 'products.min_stock') // hoặc groupBy tất cả cột nếu dùng MySQL strict mode off
            ->havingRaw('total_qty < products.min_stock')
            ->get()->count();



        // lấy ra thông kê đơn xuất theo tháng đơn nhập
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totalGoodReceiptInMonth = GoodReceipt::select(['created_at', 'total_amount']);
        if (!empty($query['fromDateGoodReceipt']) || !empty($query['toDateGoodReceipt'])) {
            if (!empty($query['fromDateGoodReceipt'])) {
                $totalGoodReceiptInMonth->where('created_at', ">=", $query['fromDateGoodReceipt']);
            }
            if (!empty($query['toDateGoodReceipt'])) {
                $totalGoodReceiptInMonth->where('created_at', "<=", $query['toDateGoodReceipt']);
            }
        } else {
            $totalGoodReceiptInMonth->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);
        }
        // Đếm tổng số đơn
        $countGoodReceiptInMonth = (clone $totalGoodReceiptInMonth)->count();

        // Đếm theo từng trạng thái
        $countGoodReceiptInMonthPending = (clone $totalGoodReceiptInMonth)->where('status', 0)->count(); 
        $countGoodReceiptInMonthReceived = (clone $totalGoodReceiptInMonth)->where('status', 0)->count();
        $countGoodReceiptInMonthClosed = (clone $totalGoodReceiptInMonth)->where('status', 0)->count();
        $sumValueGoodReceiptInMonth = (clone $totalGoodReceiptInMonth)->where('status', 3)->sum('total_amount');
        // lấy ra thay các đơn hàng được tạo trong 7 ngày
        // dd($sevenDayAgo->startOfDay());
        $GoodReceiptChangeInSevenDay = GoodReceipt::select(DB::raw("Date(created_at) as date"), DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('created_at', [$sevenDayAgo->startOfDay(), $current->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()->toArray();

        // lấy ra thông kê đơn xuất theo tháng đơn xuất
        $totalSaleOrderInMonth = SaleOrder::select(['created_at', 'total_amount']);
        if (!empty($query['fromDateSaleOrder']) || !empty($query['toDateSaleOrder'])) {
            if (!empty($query['fromDateSaleOrder'])) {
                $totalSaleOrderInMonth->where('created_at', ">=", $query['fromDateSaleOrder']);
            }
            if (!empty($query['toDateSaleOrder'])) {
                $totalSaleOrderInMonth->where('created_at', "<=", $query['toDateSaleOrder']);
            }
        } else {
            $totalSaleOrderInMonth->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);
        }

        // Đếm tổng số đơn
        $countSaleOrderInMonth = (clone $totalSaleOrderInMonth)->count();

        // Đếm theo từng trạng thái
        $countSaleOrderInMonthPending = (clone $totalSaleOrderInMonth)->where('status', 'pending')->count();
        $countSaleOrderInMonthShipped = (clone $totalSaleOrderInMonth)->where('status', 'shipped')->count();
        $countSaleOrderInMonthClosed = (clone $totalSaleOrderInMonth)->where('status', 'closed')->count();

        // Tổng giá trị
        $sumValueSaleOrderInMonth = (clone $totalSaleOrderInMonth)->where('status', 'shipped')->sum('total_amount');
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
            'statistical_good_receipt' => [
                'count_good_receipt_in_month' => $countGoodReceiptInMonth,
                'count_good_receipt_in_month_pending' => $countGoodReceiptInMonthPending,
                'count_good_receipt_in_month_received' => $countGoodReceiptInMonthReceived,
                'count_good_receipt_in_month_closed' => $countGoodReceiptInMonthClosed,
                'sum_value_good_receipt_in_month' => $this->formatNumberInt($sumValueGoodReceiptInMonth) . " ₫",
                'good_receipt_change_in_seven_day' => $GoodReceiptChangeInSevenDay,
            ],
            'statistical_sale_order' => [
                'count_sale_order_in_month' => $countSaleOrderInMonth,
                'count_sale_order_in_month_pending' => $countSaleOrderInMonthPending,
                'count_sale_order_in_month_shipped' => $countSaleOrderInMonthShipped,
                'count_sale_order_in_month_closed' => $countSaleOrderInMonthClosed,
                'sum_value_sale_order_in_month' => $this->formatNumberInt($sumValueSaleOrderInMonth) . " ₫",
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
            ->where('sale_orders.status', '=', 'closed')
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
            ->where('status', 'closed'); // chỉ lấy đơn đã hoàn thành

        $today = Carbon::now();

        switch ($range) {
            case 'daily':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();

                $results = DB::table('sale_orders')
                    ->selectRaw("DATE(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('order_date', '<=', $end)
                    ->where('status', 'closed')
                    ->groupBy(DB::raw("DATE(order_date)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'monthly':
                $start = $today->copy()->startOfYear();
                $results = DB::table('sale_orders')
                    ->selectRaw("MONTH(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('status', 'closed')
                    ->groupBy(DB::raw("MONTH(order_date)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'quarterly':
                $start = $today->copy()->startOfYear();
                $results = DB::table('sale_orders')
                    ->selectRaw("QUARTER(order_date) as label, SUM(total_amount) as revenue")
                    ->where('order_date', '>=', $start)
                    ->where('status', 'closed')
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
                    ->where('status', 'closed')
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

}
