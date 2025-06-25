<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\InventoryAudit;
use App\Models\Rank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class InventoryAuditRepository extends BaseRepository
{
    public function __construct(InventoryAudit $inventoryAudit)
    {
        $this->handleModel = $inventoryAudit;
    }

    public function getAll($perPage = 10)
    {
        return $this->handleModel::with('rank')->paginate($perPage);
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
            ];

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

}