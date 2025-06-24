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

    public function getAll($perPage = 10, array $filters = [])
    {
        $query = $this->handleModel::query();

        // Xử lý tìm kiếm tổng quát
        if (!empty($filters['search']['search'])) {
            $search = $filters['search']['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
            // Loại bỏ 'search' để không áp dụng likeTextFilter trong filterData
            unset($filters['search']['search']);
        }

        // Áp dụng các bộ lọc khác từ BaseRepository
        $query = $this->filterData($query, $filters);
        $query->orderBy('created_at', 'desc');

        Log::info('Câu truy vấn getAll:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);
        return $query->paginate($perPage);
    }

    public function createRank(array $data)
    {
        try {
            DB::beginTransaction();
            Log::info('Dữ liệu đầu vào createRank:', $data);

            $newRank = [
                'name' => $data['name'],
                'min_total_spent' => $data['min_total_spent'],
                'discount_percent' => $data['discount_percent'],
                'credit_percent' => $data['credit_percent'],
                'note' => $data['note'] ?? null,
                'status' => $data['status'],
            ];

            $rank = $this->handleModel::create($newRank);
            if (!$rank) {
                throw new \Exception('Không thể tạo hạng');
            }

            Log::info('Hạng vừa tạo:', ['rank' => $rank->toArray()]);
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi tạo hạng: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return $this->returnError('Lỗi tạo hạng: ' . $th->getMessage());
        }
    }

    public function updateRank(Rank $rank, array $data)
    {
        try {
            DB::beginTransaction();
            Log::info('Dữ liệu đầu vào updateRank:', ['data' => $data, 'current_rank' => $rank->toArray()]);

            $updatedRank = [
                'name' => $data['name'],
                'min_total_spent' => $data['min_total_spent'],
                'discount_percent' => $data['discount_percent'],
                'credit_percent' => $data['credit_percent'],
                'note' => $data['note'] ?? null,
                'status' => $data['status'],
            ];

            Log::info('Dữ liệu sẽ cập nhật:', ['updatedRank' => $updatedRank]);
            $updated = $rank->update($updatedRank);
            if (!$updated) {
                Log::warning('Không có thay đổi hoặc lỗi khi cập nhật:', ['rank_id' => $rank->id]);
                throw new \Exception('Không thể cập nhật hạng');
            }

            Log::info('Hạng sau cập nhật:', ['rank' => $rank->fresh()->toArray()]);
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi cập nhật hạng: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return $this->returnError('Lỗi cập nhật hạng: ' . $th->getMessage());
        }
    }

    public function deleteRank(Rank $rank)
    {
        try {
            if (!$rank->delete()) {
                throw new \Exception('Không thể xóa hạng');
            }
            return $rank;
        } catch (\Throwable $th) {
            Log::error('Lỗi xóa hạng: ' . $th->getMessage());
            return $this->returnError('Lỗi xóa hạng: ' . $th->getMessage());
        }
    }
}