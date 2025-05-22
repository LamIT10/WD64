<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('product_variants')->insert([
            // iPhone 15 Pro variants
            [
                'product_id' => 1,
                'stock' => 25,
                'price' => 28000000.00,
                'sale_price' => 32000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 1,
                'stock' => 15,
                'price' => 32000000.00,
                'sale_price' => 36000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Samsung Galaxy S24 variants
            [
                'product_id' => 2,
                'stock' => 20,
                'price' => 18000000.00,
                'sale_price' => 22000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'stock' => 12,
                'price' => 22000000.00,
                'sale_price' => 26000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // MacBook Pro M3 variant
            [
                'product_id' => 3,
                'stock' => 8,
                'price' => 45000000.00,
                'sale_price' => 52000000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Áo sơ mi nam variants
            [
                'product_id' => 4,
                'stock' => 30,
                'price' => 200000.00,
                'sale_price' => 350000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 4,
                'stock' => 25,
                'price' => 200000.00,
                'sale_price' => 350000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Giày thể thao Nike variants
            [
                'product_id' => 5,
                'stock' => 18,
                'price' => 1800000.00,
                'sale_price' => 2500000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 5,
                'stock' => 22,
                'price' => 1800000.00,
                'sale_price' => 2500000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
