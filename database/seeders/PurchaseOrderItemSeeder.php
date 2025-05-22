<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('purchase_order_items')->insert([
            // Order 1 items
            [
                'purchase_order_id' => 1,
                'product_variant_id' => 1, // iPhone 15 Pro Purple
                'quantity_ordered' => 15,
                'unit_id' => 1, // Cái
                'unit_price' => 25000000.00,
                'subtotal' => 375000000.00,
                'quantity_received' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'purchase_order_id' => 1,
                'product_variant_id' => 2, // iPhone 15 Pro Gold
                'quantity_ordered' => 10,
                'unit_id' => 1, // Cái
                'unit_price' => 25000000.00,
                'subtotal' => 250000000.00,
                'quantity_received' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Order 2 items
            [
                'purchase_order_id' => 2,
                'product_variant_id' => 3, // Samsung Galaxy S24 Black
                'quantity_ordered' => 20,
                'unit_id' => 1, // Cái
                'unit_price' => 18000000.00,
                'subtotal' => 360000000.00,
                'quantity_received' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'purchase_order_id' => 2,
                'product_variant_id' => 4, // Samsung Galaxy S24 White
                'quantity_ordered' => 15,
                'unit_id' => 1, // Cái
                'unit_price' => 18000000.00,
                'subtotal' => 270000000.00,
                'quantity_received' => 12,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Order 3 items (pending)
            [
                'purchase_order_id' => 3,
                'product_variant_id' => 6, // Áo sơ mi nam M
                'quantity_ordered' => 50,
                'unit_id' => 1, // Cái
                'unit_price' => 500000.00,
                'subtotal' => 25000000.00,
                'quantity_received' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
