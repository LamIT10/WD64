<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WarehouseZoneRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WarehouseZoneController extends Controller
{
    protected $warehouseZoneRepository;

    public function __construct(WarehouseZoneRepository $warehouseZoneRepository)
    {
        $this->warehouseZoneRepository = $warehouseZoneRepository;
    }

    public function index()
    {
        $zones = $this->warehouseZoneRepository->getAll();

        return Inertia::render('admin/WarehouseZones/List', [
            'zones' => $zones,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:warehouse_zones,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên khu vực là bắt buộc.',
            'name.unique' => 'Tên khu vực đã tồn tại.',
            'name.max' => 'Tên khu vực không được vượt quá 50 ký tự.',
        ]);

        $zone = $this->warehouseZoneRepository->store($data);

        return $this->returnInertia($zone, 'Thêm Mới thành công', 'admin.warehouse-zones.index');
    }

    public function edit(string $id)
    {
        $zone = $this->warehouseZoneRepository->edit($id);

        return Inertia::render('admin/WarehouseZones/Edit', [
            'zone' => $zone,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:warehouse_zones,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên khu vực là bắt buộc.',
            'name.unique' => 'Tên khu vực đã tồn tại.',
            'name.max' => 'Tên khu vực không được vượt quá 50 ký tự.',
        ]);

        $zone = $this->warehouseZoneRepository->update($id, $data);

        return $this->returnInertia($zone, 'Cập nhật thành công', 'admin.warehouse-zones.index');
    }


    public function destroy(string $id)
    {
        $result = $this->warehouseZoneRepository->destroy($id);

        return $this->returnInertia($result, 'Xóa thành công', 'admin.warehouse-zones.index');
    }
}
