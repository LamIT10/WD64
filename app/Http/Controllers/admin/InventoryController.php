<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodReceiptItem;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\WarehouseZone;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->handleRepository = $dashboardRepository;
    }
    
    public function index()
    {
        $query = request()->all();

        $data = $this->handleRepository->getDataForDashBoard($query);

        $zones = WarehouseZone::pluck('name');
        return Inertia::render('admin/Inventory/Index', [
            'zones' => $zones,
            'cardData' => [
            'count_product' => $data['count_product'],
            'product_is_out_of_stock' => $data['product_is_out_of_stock'],
            ],
        ]);
    }

    public function statistics(Request $request){
        $month = $request->input('month');

        if (!$month) {
            $month = date('Y-m');
        }

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $variants = ProductVariant::with(['product.defaultUnit'])->get();

        $data = $variants->map(function ($variant) use ($startDate, $endDate) {
            // Lấy số lượng hiện tại trong kho
            $inventory = Inventory::where('product_variant_id', $variant->id)->first();
            $currentQty = $inventory ? $inventory->quantity_on_hand : 0;

            // Tổng nhập từ trước đến hiện tại
            $totalReceivedAll = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->sum('quantity_received');

            // Tổng xuất từ trước đến hiện tại
            $totalShippedAll = $variant->salesOrderItems()
                ->sum('quantity_shipped');

            // Số lượng ban đầu khi tạo sản phẩm (tính ngược)
            $openingStock = $currentQty - $totalReceivedAll + $totalShippedAll;

            // 2. Tổng nhập đến hết tháng trước (ví dụ: hết 31/05 nếu xem tháng 6)
            $totalReceivedBefore = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->where('receipt_date', '<', $startDate))
                ->sum('quantity_received');

            // 3. Tổng xuất đến hết tháng trước
            $totalShippedBefore = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($q) => $q->where('order_date', '<', $startDate))
                ->sum('quantity_shipped');

            // 4. Tồn đầu kỳ tháng cần xem
            $openingQty = $openingStock + $totalReceivedBefore - $totalShippedBefore;

            // 5. Nhập trong kỳ (trong tháng cần xem)
            $receivedInPeriod = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->whereBetween('receipt_date', [$startDate, $endDate]))
                ->get(['quantity_received', 'unit_price', 'subtotal']);
            $receivedQty = $receivedInPeriod->sum('quantity_received');
            $receivedTotal = $receivedInPeriod->sum('subtotal');

            // 6. Xuất trong kỳ (trong tháng cần xem)
            $shippedInPeriod = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($q) => $q->whereBetween('order_date', [$startDate, $endDate]))
                ->get(['quantity_shipped', 'unit_price']);
            $shippedQty = $shippedInPeriod->sum('quantity_shipped');
            $shippedTotal = $shippedInPeriod->sum(fn($item) => $item->quantity_shipped * $item->unit_price);

            // 7. Tồn cuối kỳ = tồn đầu kỳ + nhập trong kỳ - xuất trong kỳ
            $closingQty = $openingQty + $receivedQty - $shippedQty;

            // 8. Đơn giá xuất (có thể lấy giá trung bình, ở đây lấy sale_price)
            $unitPrice = $variant->sale_price ?? 0;

            // 9. Giá trị tồn đầu kỳ = tồn đầu kỳ * đơn giá
            $openingValue = $openingQty * $unitPrice;

            // 10. Giá trị tồn cuối kỳ = tồn cuối kỳ * đơn giá
            $closingValue = $closingQty * $unitPrice;

            return [
                'month' => $startDate->format('Y-m'),
                'item_code' => $variant->code,
                'item_name' => $variant->product->name,
                'unit' => $variant->product->defaultUnit->name ?? '',
                'opening_qty' => $openingQty,      // Tồn đầu kỳ
                'opening_value' => $openingValue,  // Giá trị tồn đầu kỳ
                'received_qty' => $receivedQty,    // Nhập trong kỳ
                'received_value' => $receivedTotal,// Thành tiền nhập trong kỳ
                'shipped_qty' => $shippedQty,      // Xuất trong kỳ
                'shipped_value' => $shippedTotal,  // Thành tiền xuất trong kỳ
                'closing_qty' => $closingQty,      // Tồn cuối kỳ
                'closing_value' => $closingValue,  // Giá trị tồn cuối kỳ
                'unit_price' => $unitPrice,        // Đơn giá xuất
            ];
        });

        return Inertia::render('admin/Inventory/Statistics', [
            'data' => $data,
            'month' => $month,
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function history()
    {
        return Inertia::render('admin/Inventory/History');
    }
    
}
