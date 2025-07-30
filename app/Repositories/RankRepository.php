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
            unset($filters['search']['search']);
        }

        // Áp dụng các bộ lọc khác từ BaseRepository
        $query = $this->filterData($query, $filters);
        $query->orderBy('id', 'asc'); // Sắp xếp theo ID để hiển thị bậc

        Log::info('Câu truy vấn getAll:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);
        return $query->paginate($perPage);
    }

    public function createRank(array $data)
    {
        try {
            DB::beginTransaction();
            Log::info('Dữ liệu đầu vào createRank:', $data);

            // Kiểm tra trùng tên rank
            if ($this->handleModel::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->exists()) {
                throw new \Exception('Tên hạng "' . $data['name'] . '" đã tồn tại');
            }

            // Kiểm tra discount_percent và credit_percent
            if ($data['discount_percent'] < 1 || $data['discount_percent'] > 100) {
                throw new \Exception('Phần trăm chiết khấu phải nằm trong khoảng từ 1 đến 100');
            }
            if ($data['credit_percent'] < 1 || $data['credit_percent'] > 100) {
                throw new \Exception('Phần trăm tín dụng phải nằm trong khoảng từ 1 đến 100');
            }
            if ($data['min_total_spent'] < 1) {
                throw new \Exception('Tổng chi tiêu tối thiểu phải lớn hơn 0');
            }

            // Kiểm tra min_total_spent, discount_percent, credit_percent
            $lastRank = $this->handleModel::orderBy('id', 'desc')->first();
            if ($lastRank) {
                if ($data['min_total_spent'] <= $lastRank->min_total_spent) {
                    throw new \Exception('Tổng chi tiêu tối thiểu phải lớn hơn hạn mức của hạng "' . $lastRank->name . '" (' . number_format($lastRank->min_total_spent, 0, ',', '.') . 'đ)');
                }
                if ($data['discount_percent'] <= $lastRank->discount_percent) {
                    throw new \Exception('Phần trăm chiết khấu phải lớn hơn giá trị của hạng "' . $lastRank->name . '" (' . $lastRank->discount_percent . '%)');
                }
                if ($data['credit_percent'] <= $lastRank->credit_percent) {
                    throw new \Exception('Phần trăm tín dụng phải lớn hơn giá trị của hạng "' . $lastRank->name . '" (' . $lastRank->credit_percent . '%)');
                }
            }

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

        // Kiểm tra trùng tên rank chỉ khi tên thay đổi
        $originalName = strtolower($rank->name);
        $newName = strtolower($data['name']);
        if ($originalName !== $newName) {
            if ($this->handleModel::whereRaw('LOWER(name) = ?', [$newName])
                ->where('id', '!=', $rank->id)
                ->exists()) {
                throw new \Exception('Tên hạng "' . $data['name'] . '" đã tồn tại');
            }
        }

        // Kiểm tra discount_percent và credit_percent
        if ($data['discount_percent'] < 1 || $data['discount_percent'] > 100) {
            throw new \Exception('Phần trăm chiết khấu phải nằm trong khoảng từ 1 đến 100');
        }
        if ($data['credit_percent'] < 1 || $data['credit_percent'] > 100) {
            throw new \Exception('Phần trăm tín dụng phải nằm trong khoảng từ 1 đến 100');
        }
        if ($data['min_total_spent'] < 1) {
            throw new \Exception('Tổng chi tiêu tối thiểu phải lớn hơn 0');
        }

        // Kiểm tra min_total_spent, discount_percent, credit_percent
        $previousRank = $this->handleModel::where('id', '<', $rank->id)->orderBy('id', 'desc')->first();
        $nextRank = $this->handleModel::where('id', '>', $rank->id)->orderBy('id', 'asc')->first();

        Log::info('Kiểm tra thứ tự:', [
            'previousRank' => $previousRank ? $previousRank->toArray() : 'Không có',
            'currentRank' => $rank->toArray(),
            'nextRank' => $nextRank ? $nextRank->toArray() : 'Không có',
            'newData' => $data,
        ]);

        if ($previousRank) {
            if ($data['min_total_spent'] <= $previousRank->min_total_spent) {
                DB::rollBack();
                return $this->returnError('Tổng chi tiêu tối thiểu phải lớn hơn hạn mức của hạng "' . $previousRank->name . '" (' . number_format($previousRank->min_total_spent, 0, ',', '.') . 'đ)');
            }
            if ($data['discount_percent'] <= $previousRank->discount_percent) {
                throw new \Exception('Phần trăm chiết khấu phải lớn hơn giá trị của hạng "' . $previousRank->name . '" (' . $previousRank->discount_percent . '%)');
            }
            if ($data['credit_percent'] <= $previousRank->credit_percent) {
                throw new \Exception('Phần trăm tín dụng phải lớn hơn giá trị của hạng "' . $previousRank->name . '" (' . $previousRank->credit_percent . '%)');
            }
        }

        if ($nextRank) {
            if ($data['min_total_spent'] >= $nextRank->min_total_spent) {
                throw new \Exception('Tổng chi tiêu tối thiểu phải nhỏ hơn hạn mức của hạng "' . $nextRank->name . '" (' . number_format($nextRank->min_total_spent, 0, ',', '.') . 'đ)');
            }
            if ($data['discount_percent'] >= $nextRank->discount_percent) {
                throw new \Exception('Phần trăm chiết khấu phải nhỏ hơn giá trị của hạng "' . $nextRank->name . '" (' . $nextRank->discount_percent . '%)');
            }
            if ($data['credit_percent'] >= $nextRank->credit_percent) {
                throw new \Exception('Phần trăm tín dụng phải nhỏ hơn giá trị của hạng "' . $nextRank->name . '" (' . $nextRank->credit_percent . '%)');
            }
            
        }

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
        Log::error('Lỗi cập nhật hạng: ' . $th->getMessage(), [
            'trace' => $th->getTraceAsString(),
            'data' => $data,
            'rank' => $rank->toArray(),
        ]);
        return $this->returnError('Lỗi cập nhật hạng: ' . $th->getMessage());
    }
}

    public function hideRank(Rank $rank)
    {
        try {
            DB::beginTransaction();
            Log::info('Toggle trạng thái hạng:', ['rank_id' => $rank->id, 'current_status' => $rank->status]);

            if (strtolower($rank->name) === 'sắt') {
                throw new \Exception('Không thể thay đổi trạng thái hạng mặc định "Sắt"');
            }

            // Toggle trạng thái
            $newStatus = $rank->status === 'active' ? 'inactive' : 'active';
            $updated = $rank->update(['status' => $newStatus]);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật trạng thái hạng');
            }

            // Nếu chuyển sang inactive, chuyển khách hàng sang hạng "Sắt"
            if ($newStatus === 'inactive' && $rank->customers()->exists()) {
                $defaultRank = Rank::where('name', 'Sắt')->where('status', 'active')->first();
                if (!$defaultRank) {
                    throw new \Exception('Không tìm thấy hạng mặc định "Sắt"');
                }
                $rank->customers()->update(['rank_id' => $defaultRank->id]);
            }

            Log::info('Cập nhật trạng thái hạng thành công:', ['rank_id' => $rank->id, 'new_status' => $newStatus]);
            DB::commit();
            return $rank;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi cập nhật trạng thái hạng: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return $this->returnError('Lỗi cập nhật trạng thái hạng: ' . $th->getMessage());
        }
    }
}