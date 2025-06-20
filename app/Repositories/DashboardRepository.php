<?php

namespace App\Repositories;

use App\Models\Category;
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
        $totalPurchaseInMonth = PurchaseOrder::select(['order_date', 'total_amount']);
        if (!empty($query['fromDatePurchase']) || !empty($query['toDatePurchase'])) {
            if (!empty($query['fromDatePurchase'])) {
                $totalPurchaseInMonth->where('order_date', ">=", $query['fromDatePurchase']);
            }
            if (!empty($query['toDatePurchase'])) {
                $totalPurchaseInMonth->where('order_date', ">=", $query['fromDatePurchase']);
            }
        } else {
            $totalPurchaseInMonth->whereMonth('order_date', $currentMonth)
                ->whereYear('order_date', $currentYear);
        }

        // Đếm tổng số đơn
        $countPurchaseInMonth = (clone $totalPurchaseInMonth)->count();

        // Đếm theo từng trạng thái
        $countPurchaseInMonthPending = (clone $totalPurchaseInMonth)->where('status', 'pending')->count();
        $countPurchaseInMonthReceived = (clone $totalPurchaseInMonth)->where('status', 'received')->count();
        $countPurchaseInMonthClosed = (clone $totalPurchaseInMonth)->where('status', 'closed')->count();
        $sumValuePurchaseInMonth = (clone $totalPurchaseInMonth)->sum('total_amount');
        // lấy ra thay các đơn hàng được tạo trong 7 ngày
        // dd($sevenDayAgo->startOfDay());
        $purchaseChangeInSevenDay = PurchaseOrder::select(DB::raw("Date(order_date) as date"),DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('order_date', [$sevenDayAgo->startOfDay(), $current->endOfDay()])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get()->toArray();
        
        // lấy ra thông kê đơn xuất theo tháng đơn xuất
        $totalSaleOrderInMonth = SaleOrder::select(['order_date', 'total_amount']);
        if (!empty($query['fromDateSaleOrder']) || !empty($query['toDateSaleOrder'])) {
            if (!empty($query['fromDateSaleOrder'])) {
                $totalSaleOrderInMonth->where('order_date', ">=", $query['fromDateSaleOrder']);
            }
            if (!empty($query['toDateSaleOrder'])) {
                $totalSaleOrderInMonth->where('order_date', ">=", $query['fromDateSaleOrder']);
            }
        } else {
            $totalSaleOrderInMonth->whereMonth('order_date', $currentMonth)
                ->whereYear('order_date', $currentYear);
        }

        // Đếm tổng số đơn
        $countSaleOrderInMonth = (clone $totalSaleOrderInMonth)->count();

        // Đếm theo từng trạng thái
        $countSaleOrderInMonthPending = (clone $totalSaleOrderInMonth)->where('status', 'pending')->count();
        $countSaleOrderInMonthShipped = (clone $totalSaleOrderInMonth)->where('status', 'shipped')->count();
        $countSaleOrderInMonthClosed = (clone $totalSaleOrderInMonth)->where('status', 'closed')->count();

        // Tổng giá trị
        $sumValueSaleOrderInMonth = (clone $totalSaleOrderInMonth)->sum('total_amount');
        // lấy ra thay các đơn hàng được tạo trong 7 ngày
        $saleOrderChangeInSevenDay = SaleOrder::select(DB::raw("Date(order_date) as date"),DB::raw('COUNT(*) as total_orders'))
        ->whereBetween('order_date', [$sevenDayAgo->startOfDay(), $current->endOfDay()])
        ->groupBy(DB::raw('DATE(order_date)'))
        ->orderBy('date', 'asc')
        ->get()->toArray();



        $data = [
            'count_product' => Product::get()->count(), // số lượng sản phẩm trong kho
            'sum_product_inventory_value' => $this->sumProductInventoryValue,
            'product_is_out_of_stock' => $productIsOutOfStock,
            'statistical_purchase' => [
                'count_purchase_in_month' => $countPurchaseInMonth,
                'count_purchase_in_month_pending' => $countPurchaseInMonthPending,
                'count_purchase_in_month_received' => $countPurchaseInMonthReceived,
                'count_purchase_in_month_closed' => $countPurchaseInMonthClosed,
                'sum_value_purchase_in_month' => $this->formatNumberInt($sumValuePurchaseInMonth) . " ₫",
                'purchase_change_in_seven_day' => $purchaseChangeInSevenDay,
            ],
            'statistical_sale_order' => [
                'count_sale_order_in_month' => $countSaleOrderInMonth,
                'count_sale_order_in_month_pending' => $countSaleOrderInMonthPending,
                'count_sale_order_in_month_shipped' => $countSaleOrderInMonthShipped,
                'count_sale_order_in_month_closed' => $countSaleOrderInMonthClosed,
                'sum_value_sale_order_in_month' => $this->formatNumberInt($sumValueSaleOrderInMonth). " ₫",
                'sale_order_change_in_seven_day' => $saleOrderChangeInSevenDay,
            ]
        ];
        // dd($data);
        return $data;
    }
}
