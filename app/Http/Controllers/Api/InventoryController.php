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
        $month = $request->input('month'); // định dạng YYYY-MM

        if (!$month) {
            $month = date('Y-m');
        }

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $variants = ProductVariant::with(['product.defaultUnit'])->get();

        $data = $variants->map(function ($variant) use ($startDate, $endDate) {
            // Lấy tồn cuối kỳ (số lượng hiện tại trong kho)
            $inventory = Inventory::where('product_variant_id', $variant->id)->first();
            $currentQty = $inventory ? $inventory->quantity_on_hand : 0;
            Log::info('Total: ' . $currentQty);

            // Tổng nhập từ trước tới nay (không dùng cho tồn đầu kỳ, chỉ dùng tính initial_qty)
            $totalReceivedAll = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->sum('quantity_received');
            // Log thử giá trị để kiểm tra
            Log::info('Variant ID: ' . $variant->id . ', Total Received All: ' . $totalReceivedAll);

            // Tổng xuất từ trước tới nay (không dùng cho tồn đầu kỳ, chỉ dùng tính initial_qty)
            $totalShippedAll = $variant->salesOrderItems()
                ->sum('quantity_shipped');

            Log::info('Variant ID: ' . $variant->id . ', Total Received All: ' . $totalShippedAll);

            // Số lượng ban đầu khi tạo sp (dùng cho mục đích thống kê)
            $initialQty = $currentQty + $totalShippedAll - $totalReceivedAll;

            // return [
            //     'id' => $variant->id,
            //     'code' => $variant->code,
            //     'name' => $variant->product->name,
            //     'unit' => $variant->product->defaultUnit->name ?? '',
            //     'current_qty' => $currentQty, // Tồn cuối kỳ
            //     'totalReceivedAll' => $totalReceivedAll, // Tồn cuối kỳ
            //     'totalShippedAll' => $totalShippedAll, // Tồn cuối kỳ
            //     'initial_qty' => $initialQty, // Số lượng ban đầu khi tạo sp
            // ];

            // Nhập trong kỳ (từ đầu tháng đến cuối tháng)
            $receivedInPeriod = GoodReceiptItem::where('product_variant_id', $variant->id)
                ->whereHas('goodReceipt', fn($q) => $q->whereBetween('receipt_date', [$startDate, $endDate]))
                ->get(['quantity_received', 'unit_price', 'subtotal']);
            $receivedQty = $receivedInPeriod->sum('quantity_received');
            $receivedTotal = $receivedInPeriod->sum('subtotal');

            // Xuất trong kỳ (từ đầu tháng đến cuối tháng)
            $shippedInPeriod = $variant->salesOrderItems()
                ->whereHas('salesOrder', fn($query) => $query->whereBetween('order_date', [$startDate, $endDate]))
                ->get(['quantity_shipped', 'unit_price']);
            $shippedQty = $shippedInPeriod->sum('quantity_shipped');
            $shippedTotal = $shippedInPeriod->sum(fn($item) => $item->quantity_shipped * $item->unit_price);

            // Tính tồn đầu kỳ: Tồn cuối - Nhập trong kỳ + Xuất trong kỳ
            // Giải thích:
            // - Tồn cuối là số lượng hiện tại trong kho
            // - Nhập trong kỳ đã cộng vào kho, nên phải trừ ra để quay về đầu kỳ
            // - Xuất trong kỳ đã trừ khỏi kho, nên phải cộng lại để quay về đầu kỳ

            $openingQty = $currentQty - $receivedQty + $shippedQty;

            // Đơn giá xuất (có thể lấy giá trung bình, ở đây lấy sale_price)
            $unitPrice = $variant->sale_price ?? 0;

            // Giá trị tồn đầu kỳ = tồn đầu kỳ * đơn giá
            $openingValue = $openingQty * $unitPrice;

            // Giá trị tồn cuối kỳ = tồn cuối kỳ * đơn giá
            $closingQty = $currentQty + $receivedQty - $shippedQty;
            $closingValue = $closingQty * $unitPrice;

            return [
                'month' => $startDate->format('Y-m'),
                'item_code' => $variant->code,
                'item_name' => $variant->product->name,
                'unit' => $variant->product->defaultUnit->name ?? '',
                'currentQty' => $currentQty,      // Tồn đầu kỳ
                'opening_qty' => $openingQty,      // Tồn đầu kỳ
                'opening_value' => $openingValue,  // Giá trị tồn đầu kỳ
                'received_qty' => $receivedQty,    // Nhập trong kỳ
                'received_value' => $receivedTotal,// Thành tiền nhập trong kỳ
                'shipped_qty' => $shippedQty,      // Xuất trong kỳ
                'shipped_value' => $shippedTotal,  // Thành tiền xuất trong kỳ
                'closing_qty' => $closingQty,      // Tồn cuối kỳ
                'closing_value' => $closingValue,  // Giá trị tồn cuối kỳ
                'unit_price' => $unitPrice,        // Đơn giá xuất
                'initial_qty' => $initialQty,      // Số lượng ban đầu khi tạo sp
            ];
        });

        return response()->json([
            'data' => $data,
            'month' => $month,
        ]);
    }
}
