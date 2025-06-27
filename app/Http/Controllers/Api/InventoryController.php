<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseZone;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
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
}
