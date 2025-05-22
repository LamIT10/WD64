<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('inventory_audits')->insert([
            [
                'user_id' => 3, // Inventory Clerk
                'audit_date' => '2024-05-01',
                'status' => 'completed',
                'notes' => 'Kiểm kê định kỳ đầu tháng 5',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 3, // Inventory Clerk
                'audit_date' => '2024-05-15',
                'status' => 'completed',
                'notes' => 'Kiểm kê giữa tháng - phát hiện một số sai lệch',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 1, // Admin
                'audit_date' => '2024-05-22',
                'status' => 'pending',
                'notes' => 'Kiểm kê đột xuất sau khi nhận hàng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
