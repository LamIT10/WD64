<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitRepository extends BaseRepository
{
    public function __construct(Unit $unit)
    {
        $this->handleModel = $unit;
    }
    public function getAll()
    {
        try {
            return $this->handleModel->paginate(20);
        } catch (\Throwable $th) {
            Log::error('Lỗi Danh Sách  ' . $th->getMessage());
        }
    }
    public function store($data)
    {
        try {
            DB::beginTransaction();

            $newUnit = [
                'name' => $data['name'] ?? null,
                'symbol' => $data['symbol'] ?? null,
            ];

            $unit = $this->handleModel->create($newUnit);

            if (!$unit) {
                throw new Exception('Lỗi, thêm đơn vị không thành công.');
            }

            DB::commit();
            return $unit;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi tạo đơn vị: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $unit = $this->handleModel->findOrFail($id);

            $isUsedInProducts = $unit->products()->exists();
            $isUsedInConversions = $unit->conversionsFrom()->exists() || $unit->conversionsTo()->exists();

            if ($isUsedInProducts || $isUsedInConversions) {
                throw new Exception('Không thể xoá: Đơn vị đang được sử dụng trong sản phẩm hoặc quy đổi đơn vị.');
            }

            $unit->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi xóa đơn vị: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
