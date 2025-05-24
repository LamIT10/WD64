<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceivingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('receiving')->insert([
            [
                'purchase_order_id' => 1,
                'sale_order_id' => null,
                'user_id' => 5, // Receiving Staff
                'receive_date' => '2024-05-16',
                'status' => 'completed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'purchase_order_id' => 2,
                'sale_order_id' => null,
                'user_id' => 5, // Receiving Staff
                'receive_date' => '2024-05-19',
                'status' => 'partial',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'purchase_order_id' => null,
                'sale_order_id' => 3,
                'user_id' => 5, // Receiving Staff
                'receive_date' => '2024-05-21',
                'status' => 'issues',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
