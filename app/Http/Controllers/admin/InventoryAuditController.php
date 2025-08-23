<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WarehouseZone;
use App\Models\InventoryLocation;
use App\Services\NotificationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryAuditController extends Controller
{
    public function index()
    {
        $audits = InventoryAudit::with([
            'items.productVariant.product',
            'items.productVariant.inventory',
            'items.productVariant.inventoryLocations',
            'items.productVariant.attributes.attribute',
            'user',
            'approvedBy',
            'images',
        ])->latest('id')
            ->paginate(20);

        foreach ($audits as $audit) {
            $variantIds = $audit->items->pluck('product_variant_id');
            // Lấy danh sách zone (A, B, C...) mà các sản phẩm thuộc về
            $zoneIds = InventoryLocation::whereIn('product_variant_id', $variantIds)
                ->whereNotNull('zone_id')
                ->distinct()
                ->pluck('zone_id');
            $zones = WarehouseZone::whereIn('id', $zoneIds)->pluck('name');
            $audit->audited_zones = $zones;

            // Lấy custom_location_name (học theo logic show)
            $locations = InventoryLocation::whereIn('product_variant_id', $variantIds)
                ->whereNotNull('custom_location_name')
                ->distinct()
                ->pluck('custom_location_name');
            $audit->audited_locations = $locations;
        }

        return Inertia::render('admin/InventoryAudit/Index', [
            'audits' => $audits,
        ]);
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
            'images',
        ])->findOrFail($id);

        $variantIds = $audit->items->pluck('product_variant_id');
        // Lấy danh sách zone (A, B, C...) mà các sản phẩm thuộc về
        $zoneIds = InventoryLocation::whereIn('product_variant_id', $variantIds)
            ->whereNotNull('zone_id')
            ->distinct()
            ->pluck('zone_id');
        $zones = WarehouseZone::whereIn('id', $zoneIds)->pluck('name');
        $audit->audited_zones = $zones;

        $grid = $zones;

        foreach ($audit->items as $item) {
            // Lấy zone cho từng item
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

        // dd(Auth::id());

        return Inertia::render('admin/InventoryAudit/Show', [
            'audit' => $audit,
            'zones' => $zones,
        ]);
    }



    public function create()
    {
        // Lấy tất cả zone
        $nameZones = WarehouseZone::pluck('name');

        // Số cột tối đa (ví dụ: 6)
        $maxCols = 9;
        $grid = [];
        foreach ($nameZones as $nameZone) {
            $row = [];
            // Lấy các location của zone này
            for ($i = 0; $i <= $maxCols; $i++) {
                $label = $nameZone . $i;
                // Kiểm tra xem location có tồn tại trong cơ sở dữ liệu không
                $location = InventoryLocation::where('custom_location_name', $label)->first();
                $row[] = [
                    'exist' => $location ? true : false,
                    'label' => $label,
                ];
            }
            $grid[$nameZone] = $row;
        }
        return Inertia::render('admin/InventoryAudit/Create', [
            'grid' => $grid,
            'zones' => $nameZones,
        ]);
    }

    public function store(Request $request)
    {
        // Debug: Log dữ liệu nhận được
        Log::info('Store request data:', $request->all());

        $auditData = $request->validate([
            'notes' => 'nullable|string|max:255',
            'audit_date' => 'required|date',
            'status' => 'required|string|in:completed,issues',
            'items' => 'required|array',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.expected_quantity' => 'required|numeric',
            'items.*.actual_quantity' => 'required|numeric',
            'items.*.discrepancy_reason' => 'nullable|string|max:255',
        ]);
        try {
            DB::transaction(function () use ($auditData, $request) {
                $audit = InventoryAudit::create([
                    'notes' => $auditData['notes'],
                    'audit_date' => $auditData['audit_date'],
                    'status' => $auditData['status'],
                    'user_id' => $request->user()->id
                ]);
                $audit->save();

                // Tạo mã code tự động (KK + id)
                $audit->code = 'KK' . $audit->id;
                $audit->save();

                // Tạo nhiều bản ghi con thông qua quan hệ
                $audit->items()->createMany($auditData['items']);

                // Nhận và lưu nhiều ảnh upload
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = $file->store('inventory_audits', 'public');
                        $audit->images()->create([
                            'url' => '/storage/' . $path,
                        ]);
                    }
                }

                // Gửi thông báo khi tạo phiếu kiểm kho mới
                app(NotificationService::class)->create(
                    'inventory_audit_created',
                    'Tạo phiếu kiểm kho mới',
                    "Phiếu kiểm kho #{$audit->code} đã được tạo thành công.",
                    ['audit_id' => $audit->id]
                );
                $actor = Auth::user();
                app(NotificationService::class)->notifyAll(
                    'inventory_audit_created',
                    'Tạo phiếu kiểm kho mới',
                    "Phiếu kiểm kho #{$audit->code} đã được tạo thành công. bởi {$actor->name} ",
                    ['audit_id' => $audit->id]
                );
            });

            return redirect()->route('admin.inventory-audit.index')->with('success', 'Đã lưu kiểm kho thành công.');
        } catch (\Exception $e) {
            Log::error('Error saving inventory audit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi lưu: ' . $e->getMessage()]);
        }
    }
}
