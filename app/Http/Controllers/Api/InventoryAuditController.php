<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseApiController;
use App\Models\InventoryAudit;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\WarehouseZone;
use App\Repositories\InventoryAuditRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

use function Laravel\Prompts\error;

class InventoryAuditController extends BaseApiController
{
    protected $inventoryAuditRepository;

    public function __construct(InventoryAuditRepository $inventoryAuditRepository)
    {
        $this->inventoryAuditRepository = $inventoryAuditRepository;
    }

    public function index()
    {
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
        $variantIds = InventoryLocation::whereIn('zone_id', function ($query) use ($zones) {
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
                $zoneLocation = $variant->inventoryLocations->first(function ($loc) use ($zones) {
                    return $loc->zone && in_array($loc->zone->name, $zones);
                });
                if (!$zoneLocation)
                    continue;
                $attributes = $variant->attributes->map(function ($attrVal) {
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
                    'quantity_reserved' => optional($variant->inventory->first())->quantity_reserved,
                    'quantity_in_transit' => optional($variant->inventory->first())->quantity_in_transit,
                    'code' => $variant->code ?? $product->code,
                    'unit' => optional(optional($variant->inventory->first())->unit)->symbol,
                    'zone' => $zoneLocation->zone->name,
                    'custom_location_name' => $zoneLocation->custom_location_name,
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

    public function update(Request $request)
    {
        $auditId = $request->input('audit_id');
        $isReject = $request->input('reject', false);

        if (!$auditId) {
            return response()->json([
                'message' => 'Thiếu audit_id!'
            ], 400);
        }

        $audit = InventoryAudit::find($auditId);
        if (!$audit) {
            return response()->json(['message' => 'Không tìm thấy phiếu kiểm kho!'], 404);
        }

        if ($isReject) {
            if (!$request->user()) {
                return response()->json([['message' => 'Chưa đăng nhập!'], 'id' => Auth::id()], 401);
            }

            $audit->is_adjusted = 2; // hoặc $audit->status = 'rejected';
            $audit->adjusted_at = now();
            $audit->approved_by = $request->user()->id;
            $audit->save();

            return response()->json([
                'message' => 'Đã từ chối phiếu kiểm kho!',
                'success' => true,
                'error' => $request->user()->id
            ]);
        }

        return response()->json([
            'message' => 'Không có hành động nào được thực hiện!',
            'success' => false
        ], 400);
    }

    public function show($id)
    {
        $audit = InventoryAudit::with([
            'items.productVariant.product',
            'items.productVariant.inventory',
            'items.productVariant.inventoryLocations',
            'items.productVariant.attributes.attribute',
            'user',
            'approvedBy', 
        ])->findOrFail($id);

        $variantIds = $audit->items->pluck('product_variant_id');

        $zoneIds = InventoryLocation::whereIn('product_variant_id', $variantIds)
            ->whereNotNull('zone_id')
            ->distinct()
            ->pluck('zone_id');
        $zones = WarehouseZone::whereIn('id', $zoneIds)->pluck('name');
        $audit->audited_zones = $zones;

        foreach ($audit->items as $item) {
            $location = InventoryLocation::where('product_variant_id', $item->product_variant_id)
                ->whereNotNull('zone_id')
                ->first();
            $item->zone = $location ? optional($location->zone)->name : null;
            $item->custom_location_name = $location ? $location->custom_location_name : "";

            $unit = null;
            if ($item->productVariant && $item->productVariant->inventory && count($item->productVariant->inventory) > 0) {
                $inv = $item->productVariant->inventory->first();
                $unit = $inv->unit_id ? optional($inv->unit)->name : null;
            }
            if (!$unit && $item->productVariant && $item->productVariant->product) {
                $unit = $item->productVariant->product->unit ?? null;
            }
            $item->unit = $unit;

            $attributes = [];
            if ($item->productVariant && $item->productVariant->attributes) {
                foreach ($item->productVariant->attributes as $attrVal) {
                    $attributes[] = [
                        'attribute' => $attrVal->attribute->name ?? null,
                        'value' => $attrVal->name
                    ];
                }
            }
            $item->attributes = $attributes;
        }

        return response()->json([
            'audit' => $audit,
            'zones' => $zones,
        ]);
    }
}