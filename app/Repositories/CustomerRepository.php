<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CustomerRepository
{
    public function getAllWithRanks()
    {
        return Customer::with('ranks')->get();
    }

    public function createCustomer(array $data): void
    {
        DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            Customer::create($data);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi tạo khách hàng: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updateCustomer(Customer $customer, array $data): void
    {
        DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $customer->update($data);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi cập nhật khách hàng: ' . $th->getMessage());
            throw $th;
        }
    }

    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }

    public function storeRank(Customer $customer, array $data): void
    {
        $customer->ranks()->create($data);
    }
}
