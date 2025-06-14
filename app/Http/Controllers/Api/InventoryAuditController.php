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
        $locations = $request->input('locations', []);
        if (!is_array($locations) || empty($locations)) {
            return response()->json([
                'data' => [],
                'message' => 'No locations provided',
                'status' => false,
                'custom_location_name' => $locations
            ], 200);
        }
        $products = Product::with([
            'productVariants.attributes.attribute',
            'productVariants.inventory.unit',
            'productVariants.inventoryLocations' => function ($query) use ($locations) {
                $query->whereIn('custom_location_name', $locations)
                      ->select('id', 'product_variant_id', 'custom_location_name');
            },
        ])
        ->whereHas('productVariants.inventoryLocations', function ($query) use ($locations) {
            $query->whereIn('custom_location_name', $locations);
        })
        ->paginate(20);

        $result = [];
        foreach ($products as $product) {
            foreach ($product->productVariants as $variant) {
                $variantLocations = $variant->inventoryLocations->whereIn('custom_location_name', $locations);
                if ($variantLocations->isEmpty()) continue;
                $attributes = $variant->attributes->map(function($attrVal) {
                    return [
                        'attribute' => $attrVal->attribute->name ?? null,
                        'value' => $attrVal->name
                    ];
                });
                foreach ($variantLocations as $location) {
                    $result[] = [
                        'id_product_variant' => $variant->id,
                        'name_product' => $product->name,
                        'variant_attributes' => $attributes,
                        'quantity_on_hand' => optional($variant->inventory->first())->quantity_on_hand,
                        'code' => $variant->barcode ?? $product->code,
                        'unit' => optional(optional($variant->inventory->first())->unit)->symbol,
                        'custom_location_name' => $location->custom_location_name,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $result,
            'message' => 'Information retrieved successfully',
            'status' => true,
            'custom_location_name' => $locations
        ], 200);
    }

}