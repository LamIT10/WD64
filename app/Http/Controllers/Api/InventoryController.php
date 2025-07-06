<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use App\Models\Product;
use App\Models\WarehouseZone;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if ($audit->is_adjusted != 0 ) {
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
}
