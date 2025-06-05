<?php

namespace App\Repositories;

use App\Models\Rank;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankRepository extends BaseRepository
{
    public function __construct(Rank $rank)
    {
        $this->handleModel = $rank;
    }

    public function getAll($perPage = 10, array $filters = [])
    {
        $query = $this->handleModel::query();
        $query = $this->filterData($query, $filters);
        return $query->paginate($perPage);
    }

    public function createRank(array $data)
    {
        try {
            DB::beginTransaction();
            if (Rank::where('name', $data['name'])->exists()) {
                throw new \Exception('Tên hạng đã tồn tại');
            }
            $data['status'] = $data['status'] ?? 'active';
            $rank = $this->handleModel::create($data);
            if (!$rank) {
                throw new \Exception('Không thể tạo hạng');
            }
            Cache::forget('active_ranks'); // Xóa cache khi tạo mới
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi tạo hạng: ' . $th->getMessage());
            return $this->returnError('Lỗi tạo hạng: ' . $th->getMessage());
        }
    }

    public function updateRank(Rank $rank, array $data)
    {
        try {
            DB::beginTransaction();
            if (Rank::where('name', $data['name'])->where('id', '!=', $rank->id)->exists()) {
                throw new \Exception('Tên hạng đã tồn tại');
            }
            $data['status'] = $data['status'] ?? $rank->status;
            $updated = $rank->update($data);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật hạng');
            }
            Cache::forget('active_ranks'); // Xóa cache khi cập nhật
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi cập nhật hạng: ' . $th->getMessage());
            return $this->returnError('Lỗi cập nhật hạng: ' . $th->getMessage());
        }
    }

    public function deleteRank(Rank $rank)
    {
        try {
            DB::beginTransaction();
            $rank = $this->handleModel->findOrFail($rank->id);
            if (!$rank->delete()) {
                throw new \Exception('Không thể xóa hạng');
            }
            Cache::forget('active_ranks'); // Xóa cache khi xóa
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi xóa hạng: ' . $th->getMessage());
            return $this->returnError('Lỗi xóa hạng: ' . $th->getMessage());
        }
    }
}