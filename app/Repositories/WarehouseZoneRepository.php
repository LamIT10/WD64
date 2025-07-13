<?php

namespace App\Repositories;

use App\Models\WarehouseZone;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseZoneRepository extends BaseRepository
{
    public function __construct(WarehouseZone $warehouseZone)
    {
        $this->handleModel = $warehouseZone;
    }

    public function getAll()
    {
        try {
            return $this->handleModel->paginate(10);
        } catch (\Throwable $th) {
            Log::error('Lỗi Danh Sách  ' . $th->getMessage());
        }
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();

            $zone = $this->handleModel->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();
            return $zone;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi tạo khu vực kho: ' . $th->getMessage());
            return $this->returnError($th->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            return $this->handleModel->findOrFail($id);
        } catch (\Throwable $th) {
            Log::error("Lỗi khi lấy dữ liệu khu vực kho: " . $th->getMessage());
            return $this->returnError('Không tìm thấy khu vực kho');
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();

            $zone = $this->handleModel->find($id);
            if (!$zone) {
                throw new Exception("Không tìm thấy khu vực kho ID: $id");
            }

            $zone->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();
            return $zone;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật khu vực kho: ' . $th->getMessage());
            return $this->returnError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $zone = $this->handleModel->with(['inventories', 'locations'])->find($id);

            if (!$zone) {
                throw new Exception('Không tìm thấy khu vực kho với ID: ' . $id);
            }

            if ($zone->inventories->count() > 0) {
                throw new Exception('Không thể xóa: Khu vực này đang có tồn kho liên kết.');
            }

            if ($zone->locations->count() > 0) {
                throw new Exception('Không thể xóa: Khu vực này đang có vị trí hàng tồn tại.');
            }

            $zone->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi xóa khu vực kho: ' . $th->getMessage());
            return $this->returnError($th->getMessage());
        }
    }
}
