<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('supplier_product_variants')->insert([
            [
                'supplier_id' => 1,
                'product_variant_id' => 1,
                'cost_price' => 120000,
                'min_order_quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 2,
                'product_variant_id' => 2,
                'cost_price' => 98000,
                'min_order_quantity' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 1,
                'product_variant_id' => 3,
                'cost_price' => 135000,
                'min_order_quantity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

