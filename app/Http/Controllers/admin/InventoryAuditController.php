<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WarehouseZone;
use App\Models\InventoryLocation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InventoryAuditController extends Controller
{
    public function index()
    {
        $audits = InventoryAudit::with(['items.productVariant.product', 'user'])
            ->latest('id')
            ->paginate(20);

        foreach ($audits as $audit) {
            $variantIds = $audit->items->pluck('product_variant_id');
            // Lấy các zone tương ứng với các sản phẩm kiểm kê
            $zoneIds = InventoryLocation::whereIn('product_variant_id', $variantIds)
                ->whereNotNull('zone_id')
                ->distinct()
                ->pluck('zone_id');
            $zones = \App\Models\WarehouseZone::whereIn('id', $zoneIds)->pluck('name');
            $audit->audited_zones = $zones;
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
        ])->findOrFail($id);

        $variantIds = $audit->items->pluck('product_variant_id');
        // Lấy danh sách zone (A, B, C...) mà các sản phẩm thuộc về
        $zoneIds = InventoryLocation::whereIn('product_variant_id', $variantIds)
            ->whereNotNull('zone_id')
            ->distinct()
            ->pluck('zone_id');
        $zones = \App\Models\WarehouseZone::whereIn('id', $zoneIds)->pluck('name');
        $audit->audited_zones = $zones;

        // Không còn grid từng cell, chỉ trả về danh sách zone
        $grid = $zones;

        foreach ($audit->items as $item) {
            // Lấy zone cho từng item
            $location = InventoryLocation::where('product_variant_id', $item->product_variant_id)
                ->whereNotNull('zone_id')
                ->first();
            $item->zone = $location ? optional($location->zone)->name : null;

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

        return Inertia::render('admin/InventoryAudit/Show', [
            'audit' => $audit,
            'zones' => $zones,
        ]);
    }



    public function create()
    {
        // Lấy tất cả zone (A, B, C...)
        $zones = WarehouseZone::pluck('name');
        return Inertia::render('admin/InventoryAudit/Create', [
            'zones' => $zones,
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store inventory audit data
        // For example, you can validate and save the data here
        // InventoryAudit::create($request->all());

        // router.post(route('admin.inventory-audit.store'), {
        // notes: auditData.value.notes,
        // audit_date: auditData.value.audit_date,
        // items: auditData.value.items.map(item => ({
        // product_variant_id: item.product_variant_id,
        // expected_quantity: item.expected_quantity,
        // actual_quantity: item.actual_quantity,
        // discrepancy_reason: item.discrepancy_reason
        // }))
        // dd($request->all());
        // Trên là dữ liệu json gửi hãy nhaanjvaf thêm nó vào db
        $auditData = $request->validate([
            'notes' => 'nullable|string|max:255',
            'audit_date' => 'required|date',
            'status' => 'required|string|in:completed,issues', // Thêm dòng này
            'items' => 'required|array',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.expected_quantity' => 'required|numeric',
            'items.*.actual_quantity' => 'required|numeric',
            'items.*.discrepancy_reason' => 'nullable|string|max:255',
        ]);
        DB::transaction(function () use ($auditData, $request) {
            $audit = InventoryAudit::create([
                'notes' => $auditData['notes'],
                'audit_date' => $auditData['audit_date'],
                'status' => $auditData['status'],
                'user_id' => $request->user()->id
            ]);

            // Tạo nhiều bản ghi con thông qua quan hệ
            $audit->items()->createMany($auditData['items']);
        });

        // Trả về thông báo thành công
        // Bạn có thể sử dụng flash message để hiển thị thông báo thành công

        return redirect()->route('admin.inventory-audit.index')->with('success', 'Đã lưu kiểm kho thành công.');
    }

    

}
