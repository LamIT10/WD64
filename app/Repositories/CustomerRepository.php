<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Rank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        $this->handleModel = $customer;
    }

    public function getAll($perPage = 10)
    {
        return $this->handleModel::with('rank')->paginate($perPage);
    }

    public function createCustomer(array $data)
    {
        try {
            DB::beginTransaction();
<<<<<<< HEAD
            $newCustomer = [];
            $newCustomer['name'] = $data['name'] ?? '';
            $newCustomer['contact_person'] = $data['contact_person'] ?? '';
            $newCustomer['phone'] = $data['phone'] ?? '';
            $newCustomer['email'] = $data['email'] ?? '';
            $newCustomer['address'] = $data['address'] ?? '';
            $newCustomer['current_debt'] = $data['current_debt'] ?? 0;
=======
            $newCustomer = [
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'address' => $data['address'] ?? '',
                'current_debt' => $data['current_debt'] ?? 0,
            ];
>>>>>>> e95a910bb0222c4e62850a8e97151062ae75a25f

            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }

            // Gán hạng mặc định "Sắt"
            $defaultRank = Rank::where('name', 'Sắt')->first();
            if (!$defaultRank) {
                $defaultRank = Rank::create([
                    'name' => 'Sắt',
                    'min_total_spent' => 0,
                    'discount_percent' => 0,
                    'credit_percent' => 0,
                    'note' => 'Hạng mặc định',
                ]);
            }
            $newCustomer['rank_id'] = $defaultRank->id;
            dd($newCustomer);

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
<<<<<<< HEAD

=======
>>>>>>> e95a910bb0222c4e62850a8e97151062ae75a25f
        try {
            DB::beginTransaction();
            $newCustomer = [
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'address' => $data['address'] ?? '',
                'current_debt' => $data['current_debt'] ?? 0,
                'rank_id' => $data['rank_id'] ?? null,
            ];

            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
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
<<<<<<< HEAD
        $customer->delete();
    }

    public function storeRank(Customer $customer, array $data): void
    {
        $customer->ranks()->create($data);
    }
    public function getList()
    {
        try {
            return  $this->handleModel->get();
        } catch (\Throwable $th) {
            return  Log::error('Lỗi lấy toàn bộ danh sách khách hàng: ' . $th->getMessage());
        }
    }
}
=======
        try {
            DB::beginTransaction();
            $customer = $this->handleModel->findOrFail($customer->id);
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
>>>>>>> e95a910bb0222c4e62850a8e97151062ae75a25f
