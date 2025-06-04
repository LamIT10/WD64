<?php

namespace App\Repositories;

use App\Models\Rank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankRepository extends BaseRepository
{
    public function __construct(Rank $rank)
    {
        $this->handleModel = $rank;
    }

    public function getAll($perPage = 10)
    {
        return $this->handleModel::paginate($perPage);
    }

    public function createRank(array $data)
    {
        try {
            DB::beginTransaction();
            $rank = $this->handleModel::create($data);
            if (!$rank) {
                throw new \Exception('Không thể tạo hạng');
            }
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
            $updated = $rank->update($data);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật hạng');
            }
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
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi xóa hạng: ' . $th->getMessage());
            return $this->returnError('Lỗi xóa hạng: ' . $th->getMessage());
        }
    }
}