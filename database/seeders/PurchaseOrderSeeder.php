<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('purchase_orders')->insert([
            [
                'supplier_id' => 1,
                'user_id' => 2, // Manager
                'order_date' => '2024-05-15',
                'status' => 'received',
                'total_amount' => 150000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'user_id' => 2, // Manager
                'order_date' => '2024-05-18',
                'status' => 'received',
                'total_amount' => 90000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 3,
                'user_id' => 1, // Admin
                'order_date' => '2024-05-20',
                'status' => 'pending',
                'total_amount' => 25000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 1,
                'user_id' => 2, // Manager
                'order_date' => '2024-05-22',
                'status' => 'pending',
                'total_amount' => 75000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
