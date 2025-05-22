<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleOrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('sale_order_items')->insert([
            // Order 1 items
            [
                'sales_order_id' => 1,
                'product_variant_id' => 1, // iPhone 15 Pro Purple
                'quantity_ordered' => 3,
                'unit_id' => 1, // Cái
                'unit_price' => 25000000.00,
                'subtotal' => 75000000.00,
                'quantity_shipped' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Order 2 items
            [
                'sales_order_id' => 2,
                'product_variant_id' => 3, // Samsung Galaxy S24 Black
                'quantity_ordered' => 2,
                'unit_id' => 1, // Cái
                'unit_price' => 18000000.00,
                'subtotal' => 36000000.00,
                'quantity_shipped' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Order 3 items (pending)
            [
                'sales_order_id' => 3,
                'product_variant_id' => 5, // MacBook Pro M3 Silver
                'quantity_ordered' => 1,
                'unit_id' => 1, // Cái
                'unit_price' => 55000000.00,
                'subtotal' => 55000000.00,
                'quantity_shipped' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Order 4 items
            [
                'sales_order_id' => 4,
                'product_variant_id' => 6, // Áo sơ mi nam M
                'quantity_ordered' => 4,
                'unit_id' => 1, // Cái
                'unit_price' => 500000.00,
                'subtotal' => 2000000.00,
                'quantity_shipped' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
