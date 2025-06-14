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

            // Lấy các inventory_locations tương ứng với các sản phẩm kiểm kê
            $locations = InventoryLocation::whereIn('product_variant_id', $variantIds)
                ->whereNotNull('custom_location_name')
                ->distinct()
                ->pluck('custom_location_name');

            // Gắn vào audit
            $audit->audited_locations = $locations;
        }

        return Inertia::render('admin/InventoryAudit/Index', [
            'audits' => $audits,
        ]);
    }

    public function show($id)
    {
        $audit = InventoryAudit::with(['items.productVariant.product', 'user'])
            ->findOrFail($id);

        $variantIds = $audit->items->pluck('product_variant_id');
        $locations = InventoryLocation::whereIn('product_variant_id', $variantIds)
            ->whereNotNull('custom_location_name')
            ->distinct()
            ->pluck('custom_location_name');
        $audit->audited_locations = $locations;

        // Lấy grid giống như hàm create
        $nameZones = WarehouseZone::pluck('name');
        $maxCols = 9;
        $grid = [];
        foreach ($nameZones as $nameZone) {
            $row = [];
            for ($i = 0; $i <= $maxCols; $i++) {
                $label = $nameZone . $i;
                // Chỉ kiểm tra các custom_location_name đã được kiểm kê trong audit này
                $row[] = [
                    'exist' => $audit->audited_locations && $audit->audited_locations->contains($label),
                    'label' => $label,
                ];
            }
            $grid[$nameZone] = $row;
        }

        return Inertia::render('admin/InventoryAudit/Show', [
            'audit' => $audit,
            'grid' => $grid,
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
