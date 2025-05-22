<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('customer_transactions')->insert([
            [
                'customer_id' => 1,
                'sales_order_id' => 1,
                'paid_amount' => 75000000.00,
                'remaining_amount' => 0.00,
                'transaction_date' => '2024-05-17',
                'credit_due_date' => '2024-05-17',
                'description' => 'Thanh toán đầy đủ đơn hàng SO-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 2,
                'sales_order_id' => 2,
                'paid_amount' => 20000000.00,
                'remaining_amount' => 16000000.00,
                'transaction_date' => '2024-05-20',
                'credit_due_date' => '2024-06-19',
                'description' => 'Thanh toán một phần đơn hàng SO-002',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 1,
                'sales_order_id' => null,
                'paid_amount' => 5000000.00,
                'remaining_amount' => 0.00,
                'transaction_date' => '2024-05-12',
                'credit_due_date' => '2024-05-12',
                'description' => 'Thanh toán công nợ cũ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
