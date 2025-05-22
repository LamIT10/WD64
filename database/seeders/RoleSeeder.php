<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'description' => 'Quản trị viên với toàn quyền truy cập hệ thống',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Manager',
                'description' => 'Quản lý kho với quyền hạn cao',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Inventory Clerk',
                'description' => 'Nhân viên kiểm kê kho',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'description' => 'Nhân viên bán hàng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Shipping',
                'description' => 'Nhân viên vận chuyển',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Receiving',
                'description' => 'Nhân viên nhận hàng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
