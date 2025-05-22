<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('sale_orders')->insert([
            [
                'customer_id' => 1,
                'order_date' => '2024-05-16',
                'expected_ship_date' => '2024-05-18',
                'status' => 'shipped',
                'total_amount' => 75000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 2,
                'order_date' => '2024-05-19',
                'expected_ship_date' => '2024-05-21',
                'status' => 'shipped',
                'total_amount' => 36000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 3,
                'order_date' => '2024-05-21',
                'expected_ship_date' => '2024-05-23',
                'status' => 'pending',
                'total_amount' => 55000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 1,
                'order_date' => '2024-05-22',
                'expected_ship_date' => '2024-05-24',
                'status' => 'pending',
                'total_amount' => 2000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
