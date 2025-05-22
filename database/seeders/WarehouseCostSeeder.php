<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        $now = Carbon::now();
        
        DB::table('warehouse_costs')->insert([
            [
                'cost_type' => 'Tiền điện',
                'amount' => 1500000.00,
                'cost_date' => '2024-05-01',
                'description' => 'Tiền điện tháng 4/2024',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'cost_type' => 'Tiền nước',
                'amount' => 500000.00,
                'cost_date' => '2024-05-01',
                'description' => 'Tiền nước tháng 4/2024',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'cost_type' => 'Bảo dưỡng thiết bị',
                'amount' => 3000000.00,
                'cost_date' => '2024-05-10',
                'description' => 'Bảo dưỡng hệ thống điều hòa kho',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'cost_type' => 'Vật tư tiêu hao',
                'amount' => 800000.00,
                'cost_date' => '2024-05-15',
                'description' => 'Mua thùng carton, băng keo đóng gói',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'cost_type' => 'Lương nhân viên kho',
                'amount' => 25000000.00,
                'cost_date' => '2024-05-01',
                'description' => 'Lương tháng 4/2024 cho nhân viên kho',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
