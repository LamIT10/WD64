<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodReceiptItem;
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
        $month = $request->input('month'); // định dạng YYYY-MM

        if (!$month) {
            $month = date('Y-m');
        }

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $variants = ProductVariant::with(['product.defaultUnit'])->get();

        $data = $variants->map(function ($variant) use ($startDate, $endDate) {
            // Tổng nhập trước kỳ
            $totalReceivedBefore = $variant->receivingItems()
                ->whereHas('receiving', fn ($query) => $query->where('receive_date', '<', $startDate))
                ->sum('quantity_received');

            // Tổng xuất trước kỳ
            $totalShippedBefore = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn ($query) => $query->where('order_date', '<', $startDate))
                ->sum('quantity_shipped');

            // Tồn đầu kỳ
            $openingQty = $totalReceivedBefore - $totalShippedBefore;

            // Nhập trong kỳ
            $receivedInPeriod = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->whereBetween('receipt_date', [$startDate, $endDate]))
                ->get(['quantity_received', 'unit_price', 'subtotal']);

            $receivedQty = $receivedInPeriod->sum('quantity_received');
            $receivedTotal = $receivedInPeriod->sum('subtotal');

            // Xuất trong kỳ
            $shippedInPeriod = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn ($query) => $query->whereBetween('order_date', [$startDate, $endDate]))
                ->get(['quantity_shipped', 'unit_price']);

            $shippedQty = $shippedInPeriod->sum('quantity_shipped');
            $shippedTotal = $shippedInPeriod->sum(fn ($item) => $item->quantity_shipped * $item->unit_price);

            // Đơn giá xuất
            $unitPrice = $variant->sale_price ?? 0;

            // Tính tồn cuối
            $closingQty = $openingQty + $receivedQty - $shippedQty;
            $openingValue = $openingQty * $unitPrice;
            $closingValue = $closingQty * $unitPrice;

            return [
                'month' => $startDate->format('Y-m'),
                'item_code' => $variant->code,
                'item_name' => $variant->product->name,
                'unit' => $variant->product->defaultUnit->name ?? '',
                'opening_qty' => $openingQty,
                'opening_value' => $openingValue,
                'received_qty' => $receivedQty,
                'received_value' => $receivedTotal,
                'shipped_qty' => $shippedQty,
                'shipped_value' => $shippedTotal,
                'closing_qty' => $closingQty,
                'closing_value' => $closingValue,
                'unit_price' => $unitPrice,
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

    
}
