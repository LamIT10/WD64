<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('warehouse_zones')->insert([
            [
                'name' => 'Khu A - Điện tử',
                'description' => 'Khu vực lưu trữ các sản phẩm điện tử',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khu B - Thời trang',
                'description' => 'Khu vực lưu trữ quần áo, giày dép',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khu C - Hàng cao cấp',
                'description' => 'Khu vực bảo quản hàng hóa có giá trị cao',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khu D - Hàng lỗi/hết hạn',
                'description' => 'Khu vực cách ly hàng hóa có vấn đề',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
