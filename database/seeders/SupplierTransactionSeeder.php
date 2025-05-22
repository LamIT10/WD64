<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('supplier_transactions')->insert([
            [
                'supplier_id' => 1,
                'purchase_order_id' => 1,
                'paid_amount' => 625000000.00,
                'remaining_amount' => 0.00,
                'transaction_date' => '2024-05-16',
                'credit_due_date' => '2024-06-15',
                'description' => 'Thanh toán đầy đủ đơn hàng PO-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'purchase_order_id' => 2,
                'paid_amount' => 300000000.00,
                'remaining_amount' => 330000000.00,
                'transaction_date' => '2024-05-19',
                'credit_due_date' => '2024-06-18',
                'description' => 'Thanh toán một phần đơn hàng PO-002',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 1,
                'purchase_order_id' => null,
                'paid_amount' => 10000000.00,
                'remaining_amount' => 0.00,
                'transaction_date' => '2024-05-10',
                'credit_due_date' => '2024-05-10',
                'description' => 'Thanh toán công nợ cũ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
