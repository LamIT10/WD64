<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('shipping')->insert([
            [
                'sale_order_id' => 1,
                'user_id' => 6, // Shipping Staff
                'ship_date' => '2024-05-18',
                'carrier' => 'Giao Hàng Nhanh',
                'tracking_number' => 'GHN123456789',
                'status' => 'delivered',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sale_order_id' => 2,
                'user_id' => 6, // Shipping Staff
                'ship_date' => '2024-05-21',
                'carrier' => 'Viettel Post',
                'tracking_number' => 'VTP987654321',
                'status' => 'shipped',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
