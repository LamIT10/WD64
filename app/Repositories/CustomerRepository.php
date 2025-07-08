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

        if (!empty($filters['search']['search'])) {
            $search = $filters['search']['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
            unset($filters['search']['search']);
        }

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
                'status' => $data['status'] ?? 'active',
                'rank_id' => $data['rank_id'] ?? null,
            ];

            if (!empty($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
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

            $customer = $this->handleModel::create($newCustomer);
            if (!$customer) {
                throw new \Exception('Không thể tạo khách hàng');
            }

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
                'status' => $data['status'] ?? $customer->status,
                'rank_id' => $data['rank_id'] ?? $customer->rank_id,
            ];

            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }

            if (!empty($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
                $newCustomer['avatar'] = $this->handleUpdateFile($data['avatar'], $customer->avatar);
            }

            if (!empty($data['rank_id'])) {
                $rank = Rank::where('id', $data['rank_id'])->where('status', 'active')->first();
                if (!$rank) {
                    throw new \Exception('Hạng khách hàng không hợp lệ hoặc không tồn tại');
                }
            }

            $updated = $customer->update($newCustomer);
            if (!$updated) {
                throw new \Exception('Không thể cập nhật khách hàng');
            }

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
            if ($customer->avatar) {
                $this->deleteFile($customer->avatar);
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
}