<?php

namespace App\Repositories;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Rank;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        $this->handleModel = $customer;
    }

    public function getAll($perPage = 10, array $filters = [])
    {
        $query = $this->handleModel::with('rank');
        $query = $this->filterData($query, $filters);
        return $query->paginate($perPage);
    }

    public function createCustomer(array $data)
    {
        try {
            DB::beginTransaction();
            $newCustomer = [
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'address' => $data['address'] ?? '',
                'current_debt' => $data['current_debt'] ?? 0,
                'total_spent' => $data['total_spent'] ?? 0,
                'max_debt_limit' => 0,
                'status' => $data['status'] ?? 'active',
            ];
            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }
            if (!empty($data['avatar'])) {
                $newCustomer['avatar'] = app(Controller::class)->handleUploadOneFile($data['avatar'], 'public', 'avatars');
            }
            $newCustomer['rank_id'] = $data['rank_id'] ?? null;
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
            if ($newCustomer['current_debt'] > $customer->max_debt_limit) {
                throw new \Exception('Số nợ hiện tại vượt quá giới hạn công nợ');
            }
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
                'status' => $data['status'] ?? $customer->status,
                'rank_id' => $data['rank_id'] ?? $customer->rank_id,
            ];
            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }
            if (!empty($data['avatar'])) {
                $newCustomer['avatar'] = app(Controller::class)->handleUpdateFile($data['avatar'], $customer->avatar, 'public', 'avatars');
            }
            if (!empty($data['rank_id'])) {
                $rank = Rank::where('id', $data['rank_id'])->where('status', 'active')->first();
                if (!$rank) {
                    throw new \Exception('Hạng khách hàng không hợp lệ hoặc không hoạt động');
                }
            }
            $newCustomer['rank_id'] = $this->assignRankBasedOnTotalSpent($newCustomer['total_spent'], $newCustomer['rank_id']);
            $updated = $customer->update($newCustomer);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật khách hàng');
            }
            $customer->max_debt_limit = $this->calculateMaxDebtLimit($customer);
            if ($newCustomer['current_debt'] > $customer->max_debt_limit) {
                throw new \Exception('Số nợ hiện tại vượt quá giới hạn công nợ');
            }
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
            DB::beginTransaction();
            $customer = $this->handleModel->findOrFail($customer->id);
            if ($customer->avatar) {
                app(Controller::class)->deleteFile($customer->avatar, 'public');
            }
            if (!$customer->delete()) {
                throw new \Exception('Không thể xóa khách hàng');
            }
            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi xóa khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi xóa khách hàng: ' . $th->getMessage());
        }
    }

    protected function assignRankBasedOnTotalSpent($totalSpent, $currentRankId)
    {
        $rank = Cache::remember('active_ranks', 3600, function () {
            return Rank::where('status', 'active')
                ->orderBy('min_total_spent', 'desc')
                ->get();
        })->firstWhere('min_total_spent', '<=', $totalSpent);
        return $rank ? $rank->id : $currentRankId;
    }

    protected function calculateMaxDebtLimit(Customer $customer)
    {
        if (!$customer->rank) {
            return 0;
        }
        return $customer->total_spent * ($customer->rank->credit_percent / 100);
    }
}