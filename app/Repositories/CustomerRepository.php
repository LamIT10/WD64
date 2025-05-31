<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        $this->handleModel = $customer;
    }
    public function getAllWithRanks($perPage = 10)
    {
        return $this->handleModel::with('ranks')->paginate($perPage);
    }

    public function createCustomer(array $data)
    {
        try {
            DB::beginTransaction();
            $newCustomer = [];
            $newCustomer['name'] = $data['name'] ?? '';
            $newCustomer['contact_person'] = $data['contact_person'] ?? '';
            $newCustomer['phone'] = $data['phone'] ?? '';
            $newCustomer['email'] = $data['email'] ?? '';
            $newCustomer['address'] = $data['address'] ?? '';
            $newCustomer['current_debt'] = $data['current_debt'] ?? 0;
            
            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            }

            $customer = $this->handleModel::create($newCustomer);
            if(!$customer) {
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
            $newCustomer = [];
            $newCustomer['name'] = $data['name'] ?? '';
            $newCustomer['contact_person'] = $data['contact_person'] ?? '';
            $newCustomer['phone'] = $data['phone'] ?? '';
            $newCustomer['email'] = $data['email'] ?? '';
            $newCustomer['address'] = $data['address'] ?? '';
            $newCustomer['current_debt'] = $data['current_debt'] ?? 0;

            if (!empty($data['password'])) {
                $newCustomer['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
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
            $customer = $this->handleModel->findOrFail($customer->id);
            $customer->ranks()->delete();
            if (!$customer->delete()) {
                throw new \Exception('Không thể xoá khách hàng có liên kết với bảng xếp hạng');
            }
            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi xoá khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi xoá khách hàng: ' . $th->getMessage());
        }        
    }

    public function storeRank(Customer $customer, array $data)
    {
        try {
            $customer->ranks()->create($data);
            if (!$customer->ranks()->exists()) {
                throw new \Exception('Không thể lưu xếp hạng khách hàng');
            }
            DB::commit();
            return $customer->ranks;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi lưu xếp hạng khách hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi lưu xếp hạng khách hàng: ' . $th->getMessage());
        }
    }
}
