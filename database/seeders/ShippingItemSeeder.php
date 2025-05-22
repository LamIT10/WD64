<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('shipping_items')->insert([
            // Shipping 1 items
            [
                'shipping_id' => 1,
                'product_variant_id' => 1, // iPhone 15 Pro Purple
                'unit_id' => 1, // Cái
                'quantity_shipped' => 3,
                'notes' => 'Đóng gói cẩn thận, có bọc chống sốc',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Shipping 2 items
            [
                'shipping_id' => 2,
                'product_variant_id' => 3, // Samsung Galaxy S24 Black
                'unit_id' => 1, // Cái
                'quantity_shipped' => 2,
                'notes' => 'Giao hàng trong ngày',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
