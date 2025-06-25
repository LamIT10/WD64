<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseApiController;
use App\Models\InventoryAudit;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Repositories\InventoryAuditRepository;
use Illuminate\Http\Request;

class InventoryAuditController extends BaseApiController
{
    protected $inventoryAuditRepository;

    public function __construct(InventoryAuditRepository $inventoryAuditRepository)
    {
        $this->inventoryAuditRepository = $inventoryAuditRepository;
    }

    public function index(){
        $audits = InventoryAudit::with(['items.productVariant.product', 'user'])
            ->latest('id')
            ->paginate(20);

        foreach ($audits as $audit) {
            $variantIds = $audit->items->pluck('product_variant_id');

            // Lấy các inventory_locations tương ứng với các sản phẩm kiểm kê
            $locations = InventoryLocation::whereIn('product_variant_id', $variantIds)
                ->whereNotNull('custom_location_name')
                ->distinct()
                ->pluck('custom_location_name');

            // Gắn vào audit
            $audit->audited_locations = $locations;
        }
        
        return response()->json([
            'data' => $audits,
            'message' => 'Inventory audits retrieved successfully',
            'status' => true
        ], 200);
    }

    public function showInformationCreate(Request $request)
    {
        $zones = $request->input('zones', []);
        if (!is_array($zones) || empty($zones)) {
            return response()->json([
                'data' => [],
                'message' => 'No zones provided',
                'status' => false,
                'zones' => $zones
            ], 200);
        }
        // Lấy tất cả product_variant_id thuộc các zone này
        $variantIds = InventoryLocation::whereIn('zone_id', function($query) use ($zones) {
            $query->select('id')->from('warehouse_zones')->whereIn('name', $zones);
        })->pluck('product_variant_id');

        $products = Product::with([
            'productVariants.attributes.attribute',
            'productVariants.inventory.unit',
            'productVariants.inventoryLocations.zone',
        ])
        ->whereHas('productVariants.inventoryLocations.zone', function ($query) use ($zones) {
            $query->whereIn('name', $zones);
        })
        ->paginate(100);

        $result = [];
        foreach ($products as $product) {
            foreach ($product->productVariants as $variant) {
                // Lấy zone cho biến thể này
                $zoneLocation = $variant->inventoryLocations->first(function($loc) use ($zones) {
                    return $loc->zone && in_array($loc->zone->name, $zones);
                });
                if (!$zoneLocation) continue;
                $attributes = $variant->attributes->map(function($attrVal) {
                    return [
                        'attribute' => $attrVal->attribute->name ?? null,
                        'value' => $attrVal->name
                    ];
                });
                $result[] = [
                    'id_product_variant' => $variant->id,
                    'name_product' => $product->name,
                    'variant_attributes' => $attributes,
                    'quantity_on_hand' => optional($variant->inventory->first())->quantity_on_hand,
                    'code' => $variant->barcode ?? $product->code,
                    'unit' => optional(optional($variant->inventory->first())->unit)->symbol,
                    'zone' => $zoneLocation->zone->name,
                ];
            }
        }

        return response()->json([
            'data' => $result,
            'message' => 'Information retrieved successfully',
            'status' => true,
            'zones' => $zones
        ], 200);
    }

}