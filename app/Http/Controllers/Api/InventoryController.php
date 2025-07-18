<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoodReceiptItem;
use App\Models\Inventory;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\WarehouseZone;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function stats()
    {
        // tổng số lượng sản phẩm trong tình trạng số lượng tồn kho nhỏ hơn min_stock
        $productIsOutOfStock = Product::select('products.*')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->join('inventory', 'inventory.product_variant_id', '=', 'product_variants.id')
            ->selectRaw('products.*, SUM(inventory.quantity_on_hand) as total_qty')
            ->groupBy('products.id', 'products.min_stock')
            ->havingRaw('total_qty < products.min_stock')
            ->get();

        return response()->json([
            'count_product' => Product::count(),
            'product_is_out_of_stock' => $productIsOutOfStock->count()
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        $auditId = $request->input('audit_id');
        if (!$auditId) {
            return response()->json([
                'message' => 'Thiếu audit_id!'
            ], 400);
        }

        // Ví dụ: cập nhật trạng thái phiếu kiểm kho, cập nhật tồn kho thực tế, ...
        $audit = InventoryAudit::find($auditId);
        if (!$audit) {
            return response()->json(['message' => 'Không tìm thấy phiếu kiểm kho!'], 404);
        }
        // ... Xử lý đồng bộ ...

        // Kiểm tra đã đồng bộ chưa
        if ($audit->is_adjusted != 0) {
            return response()->json(['message' => 'Phiếu đã được đồng bộ trước đó!'], 400);
        }

        // Lấy danh sách item kiểm kho
        $items = InventoryAuditItem::where('audit_id', $auditId)->get();

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                // Cập nhật tồn kho thực tế cho từng product_variant
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
                if ($inventory) {
                    $inventory->quantity_on_hand = $item->actual_quantity;
                    $inventory->save();
                }
            }

            // Cập nhật trạng thái phiếu kiểm kho
            $audit->approved_by = $request->user()->id;
            $audit->is_adjusted = 1;
            $audit->adjusted_at = now();
            $audit->save();

            DB::commit();
            return response()->json([
                'message' => 'Đồng bộ thành công!',
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi đồng bộ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statistics(Request $request)
    {
        // Nhận nhiều tháng, ví dụ: 2025-02,2025-03,2025-04
        $months = $request->input('months');
        if ($months) {
            $months = is_array($months) ? $months : explode(',', $months);
            sort($months); // Đảm bảo theo thứ tự thời gian
            $firstMonth = $months[0];
            $lastMonth = $months[count($months) - 1];
        } else {
            $month = $request->input('month') ?: date('Y-m');
            $months = [$month];
            $firstMonth = $lastMonth = $month;
        }

        // Tính khoảng thời gian tổng hợp
        $startDate = Carbon::parse($firstMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($lastMonth . '-01')->endOfMonth();

        $variants = ProductVariant::with(['product.defaultUnit'])->get();

        $data = $variants->map(function ($variant) use ($startDate, $endDate, $months) {
            // Nếu sản phẩm được tạo sau kỳ báo cáo thì không hiển thị
            if (!$variant->created_at || Carbon::parse($variant->created_at)->gt($endDate)) {
                return null;
            }

            // 1. Số lượng ban đầu khi tạo sản phẩm (tính ngược, có tính cả điều chỉnh kiểm kho)
            $inventory = Inventory::where('product_variant_id', $variant->id)->first();
            $currentQty = $inventory ? $inventory->quantity_on_hand : 0;
            $totalReceivedAll = GoodReceiptItem::where('product_variant_id', $variant->id)->sum('quantity_received');
            $totalShippedAll = $variant->salesOrderItems()->sum('quantity_shipped');
            $totalAdjustedAll = InventoryAuditItem::where('product_variant_id', $variant->id)
                ->whereHas('audit', function ($q) {
                    $q->where('is_adjusted', 1);
                })
                ->sum(DB::raw('actual_quantity - expected_quantity'));
            $openingStock = $currentQty - $totalReceivedAll + $totalShippedAll - $totalAdjustedAll;

            // 2. Tổng nhập đến hết tháng trước đầu tiên
            $beforeStart = $startDate->copy()->startOfMonth();
            $totalReceivedBefore = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->where('receipt_date', '<', $beforeStart))
                ->sum('quantity_received');
            $totalShippedBefore = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($q) => $q->where('order_date', '<', $beforeStart))
                ->sum('quantity_shipped');
            $totalAdjustedBefore = InventoryAuditItem::where('product_variant_id', $variant->id)
                ->whereHas('audit', function ($q) use ($beforeStart) {
                    $q->where('is_adjusted', 1)
                      ->where('adjusted_at', '<', $beforeStart);
                })
                ->sum(DB::raw('actual_quantity - expected_quantity'));
            $openingQty = $openingStock + $totalReceivedBefore - $totalShippedBefore + $totalAdjustedBefore;

            // 3. Tổng nhập trong các tháng đã chọn
            $receivedQty = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->whereBetween('receipt_date', [$startDate, $endDate]))
                ->sum('quantity_received');
            $receivedTotal = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->whereBetween('receipt_date', [$startDate, $endDate]))
                ->sum('subtotal');

            // 4. Tổng xuất trong các tháng đã chọn
            $shippedQty = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($q) => $q->whereBetween('order_date', [$startDate, $endDate]))
                ->sum('quantity_shipped');
            $shippedTotal = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($q) => $q->whereBetween('order_date', [$startDate, $endDate]))
                ->sum(DB::raw('quantity_shipped * unit_price'));
            // 4.5. Tổng điều chỉnh trong các tháng đã chọn
            $adjustedQty = InventoryAuditItem::where('product_variant_id', $variant->id)
                ->whereHas('audit', function ($q) use ($startDate, $endDate) {
                    $q->where('is_adjusted', 1)
                        ->whereBetween('adjusted_at', [$startDate, $endDate]);
                })
                ->sum(DB::raw('actual_quantity - expected_quantity'));
            $adjustedValue = $adjustedQty * ($variant->sale_price ?? 0);
            // 5. Tồn cuối kỳ = tồn đầu kỳ + nhập - xuất
            $closingQty = $openingQty + $receivedQty - $shippedQty + $adjustedQty;

            $unitPrice = $variant->sale_price ?? 0;
            $openingValue = $openingQty * $unitPrice;
            $closingValue = $closingQty * $unitPrice;

            return [
                'months' => $months,
                'item_code' => $variant->code,
                'item_name' => $variant->product->name,
                'unit' => $variant->product->defaultUnit->name ?? '',
                'opening_qty' => $openingQty,
                'opening_value' => $openingValue,
                'increase_qty' => $adjustedQty,
                'received_qty' => $receivedQty,
                'received_value' => $receivedTotal,
                'shipped_qty' => $shippedQty,
                'shipped_value' => $shippedTotal,
                'closing_qty' => $closingQty,
                'closing_value' => $closingValue,
                'unit_price' => $unitPrice,
            ];
        })->filter()->values(); // Loại bỏ các phần tử null khỏi collection

        return response()->json([
            'data' => $data,
            'months' => $months,
        ]);
    }
}
