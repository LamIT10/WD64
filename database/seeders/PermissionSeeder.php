<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('permissions')->insert([
            [
                'code' => 'view_dashboard',
                'name' => 'Xem trang tổng quan',
                'description' => 'Quyền xem trang tổng quan của hệ thống',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'manage_inventory',
                'name' => 'Quản lý kho',
                'description' => 'Quyền quản lý kho hàng',
                'created_at' => $now, 
                'updated_at' => $now,
            ],
            [
                'code' => 'manage_orders',
                'name' => 'Quản lý đơn hàng',
                'description' => 'Quyền quản lý đơn đặt hàng mua và bán',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'manage_users',
                'name' => 'Quản lý người dùng',
                'description' => 'Quyền quản lý người dùng hệ thống',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'manage_finance',
                'name' => 'Quản lý tài chính',
                'description' => 'Quyền quản lý giao dịch tài chính',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'generate_reports',
                'name' => 'Tạo báo cáo',
                'description' => 'Quyền tạo và xem các báo cáo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
