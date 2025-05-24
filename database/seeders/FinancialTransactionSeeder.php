<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('financial_transactions')->insert([
            // Revenue transactions
            [
                'type' => 'revenue',
                'amount' => 75000000.00,
                'transaction_date' => '2024-05-17',
                'description' => 'Doanh thu từ đơn hàng SO-001',
                'related_type' => 'sales_order',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'revenue',
                'amount' => 36000000.00,
                'transaction_date' => '2024-05-20',
                'description' => 'Doanh thu từ đơn hàng SO-002',
                'related_type' => 'sales_order',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Expense transactions
            [
                'type' => 'expense',
                'amount' => 625000000.00,
                'transaction_date' => '2024-05-16',
                'description' => 'Chi phí mua hàng PO-001',
                'related_type' => 'purchase_order',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'expense',
                'amount' => 300000000.00,
                'transaction_date' => '2024-05-19',
                'description' => 'Chi phí mua hàng PO-002 (thanh toán một phần)',
                'related_type' => 'purchase_order',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'expense',
                'amount' => 5000000.00,
                'transaction_date' => '2024-05-15',
                'description' => 'Chi phí vận chuyển',
                'related_type' => 'other',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'expense',
                'amount' => 2000000.00,
                'transaction_date' => '2024-05-18',
                'description' => 'Chi phí điện, nước kho bãi',
                'related_type' => 'other',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
