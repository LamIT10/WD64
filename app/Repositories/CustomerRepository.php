<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Rank;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        $this->handleModel = $customer;
    }

    public function getAll($perPage = 10, array $filters = [])
    {
        $query = $this->handleModel::with('rank');

        // Xử lý tìm kiếm tổng quát
        if (!empty($filters['search']['search'])) {
            $search = $filters['search']['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
            // Loại bỏ 'search' để không áp dụng likeTextFilter trong filterData
            unset($filters['search']['search']);
        }

        // Áp dụng các bộ lọc khác từ BaseRepository
        $query = $this->filterData($query, $filters);

        return $query->paginate($perPage);
    }

    public function createCustomer(array $data)
    {
        try {
            DB::beginTransaction();
            $newCustomer = [
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'password' => !empty($data['password']) ? Hash::make($data['password']) : null,
                'address' => $data['address'] ?? null,
                'current_debt' => $data['current_debt'] ?? 0.00,
                'total_spent' => $data['total_spent'] ?? 0.00,
                'max_debt_limit' => 0.00,
                'status' => $data['status'] ?? 'active',
                'rank_id' => $data['rank_id'] ?? null,
            ];

            if (!empty($data['avatar'])) {
                $newCustomer['avatar'] = $this->handleUploadOneFile($data['avatar']);
            }

            if (empty($newCustomer['rank_id'])) {
                $defaultRank = Rank::where('name', 'Sắt')->where('status', 'active')->first();
                if (!$defaultRank) {
                    $defaultRank = Rank::create([
                        'name' => 'Sắt',
                        'min_total_spent' => 0,
                        'discount_percent' => 0,
                        'credit_percent' => 0,
                        'note' => 'Hạng mặc định',
                        'status' => 'active',
                    ]);
                }
                $newCustomer['rank_id'] = $defaultRank->id;
            }

            $newCustomer['rank_id'] = $this->assignRankBasedOnTotalSpent($newCustomer['total_spent'], $newCustomer['rank_id']);
            $customer = $this->handleModel::create($newCustomer);
            if (!$customer) {
                throw new \Exception('Không thể tạo khách hàng');
            }

            $customer->max_debt_limit = $this->calculateMaxDebtLimit($customer);
            $this->updateCustomerStatus($customer);
            $customer->save();

            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi tạo khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi tạo khách hàng: ' . $th->getMessage());
        }
    }

    public function updateCustomer(Customer $customer, array $data)
    {
        try {
            DB::beginTransaction();
            $newCustomer = [
                'name' => $data['name'] ?? $customer->name,
                'phone' => $data['phone'] ?? $customer->phone,
                'email' => $data['email'] ?? $customer->email,
                'address' => $data['address'] ?? $customer->address,
                'current_debt' => $data['current_debt'] ?? $customer->current_debt,
                'total_spent' => $data['total_spent'] ?? $customer->total_spent,
                'rank_id' => $data['rank_id'] ?? $customer->rank_id,
            ];

            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }

            if (!empty($data['avatar'])) {
                $newCustomer['avatar'] = $this->handleUpdateFile($data['avatar'], $customer->avatar);
            } elseif ($customer->avatar && !Storage::disk('public')->exists($customer->avatar)) {
                $newCustomer['avatar'] = null;
            }

            if (!empty($data['rank_id'])) {
                $rank = Rank::where('id', $data['rank_id'])->where('status', 'active')->first();
                if (!$rank) {
                    throw new \Exception('Hạng khách hàng không hợp lệ hoặc không tồn tại');
                }
            }

            $newCustomer['rank_id'] = $this->assignRankBasedOnTotalSpent($newCustomer['total_spent'], $newCustomer['rank_id']);
            $updated = $customer->update($newCustomer);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật khách hàng');
            }

            $customer->max_debt_limit = $this->calculateMaxDebtLimit($customer);
            $this->updateCustomerStatus($customer);
            $customer->save();

            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi cập nhật khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi cập nhật khách hàng: ' . $th->getMessage());
        }
    }

    public function deleteCustomer(Customer $customer)
    {
        try {
            if ($customer->avatar) {
                $this->deleteFile($customer->avatar);
            }
            if (!$customer->delete()) {
                throw new \Exception('Không thể xóa khách hàng');
            }
            return $customer;
        } catch (\Throwable $th) {
            Log::error('Lỗi xóa khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi xóa khách hàng: ' . $th->getMessage());
        }
    }

    protected function assignRankBasedOnTotalSpent($totalSpent, $currentRankId)
    {
        if ($totalSpent < 0) {
            $defaultRank = Rank::where('name', 'Sắt')->where('status', 'active')->first();
            return $defaultRank ? $defaultRank->id : $currentRankId;
        }

        $rank = Cache::remember('active_ranks', 3600, function () {
            return Rank::where('status', 'active')
                ->orderBy('min_total_spent', 'desc')
                ->get();
        })->firstWhere('min_total_spent', '<=', $totalSpent);

        if (!$rank) {
            $defaultRank = Rank::where('name', 'Sắt')->where('status', 'active')->first();
            return $defaultRank ? $defaultRank->id : $currentRankId;
        }
        return $rank->id;
    }

    protected function calculateMaxDebtLimit(Customer $customer)
    {
        if (!$customer->rank || $customer->total_spent < 0) {
            Log::warning('Không thể tính giới hạn công nợ', [
                'customer_id' => $customer->id,
                'rank' => $customer->rank,
                'total_spent' => $customer->total_spent,
            ]);
            return 0.00;
        }
        return $customer->total_spent * ($customer->rank->credit_percent / 100);
    }

    protected function updateCustomerStatus(Customer $customer)
    {
        $validStatuses = ['active', 'inactive', 'debt_exceeded'];
        $currentStatus = in_array($customer->status, $validStatuses) ? $customer->status : 'active';

        $newStatus = $currentStatus;
        if ($customer->current_debt > $customer->max_debt_limit) {
            $newStatus = 'debt_exceeded';
        } elseif ($currentStatus === 'debt_exceeded' && $customer->current_debt <= $customer->max_debt_limit) {
            $newStatus = 'active';
        }

        if ($newStatus !== $currentStatus) {
            Log::info('Cập nhật trạng thái khách hàng', [
                'customer_id' => $customer->id,
                'old_status' => $currentStatus,
                'new_status' => $newStatus,
            ]);
            $customer->status = $newStatus;
        }
    }
}