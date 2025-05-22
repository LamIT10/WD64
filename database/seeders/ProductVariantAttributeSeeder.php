<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('product_variant_attributes')->insert([
            // iPhone 15 Pro - Đen 128GB
            [
                'variant_id' => 1,
                'attribute_value_id' => 1, // Đen
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 1,
                'attribute_value_id' => 9, // 128GB
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // iPhone 15 Pro - Đen 256GB
            [
                'variant_id' => 2,
                'attribute_value_id' => 1, // Đen
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 2,
                'attribute_value_id' => 10, // 256GB
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Samsung Galaxy S24 - Trắng 128GB
            [
                'variant_id' => 3,
                'attribute_value_id' => 2, // Trắng
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 3,
                'attribute_value_id' => 9, // 128GB
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Samsung Galaxy S24 - Xanh 256GB
            [
                'variant_id' => 4,
                'attribute_value_id' => 3, // Xanh
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 4,
                'attribute_value_id' => 10, // 256GB
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // MacBook Pro M3 - Xám 512GB
            [
                'variant_id' => 5,
                'attribute_value_id' => 1, // Đen (Xám)
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 5,
                'attribute_value_id' => 11, // 512GB
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Áo sơ mi - Trắng M
            [
                'variant_id' => 6,
                'attribute_value_id' => 2, // Trắng
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 6,
                'attribute_value_id' => 6, // M
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 6,
                'attribute_value_id' => 12, // Cotton
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Áo sơ mi - Xanh L
            [
                'variant_id' => 7,
                'attribute_value_id' => 3, // Xanh
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 7,
                'attribute_value_id' => 7, // L
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 7,
                'attribute_value_id' => 12, // Cotton
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Giày Nike - Đen 42
            [
                'variant_id' => 8,
                'attribute_value_id' => 1, // Đen
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 8,
                'attribute_value_id' => 14, // Da thật
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Giày Nike - Trắng 43
            [
                'variant_id' => 9,
                'attribute_value_id' => 2, // Trắng
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'variant_id' => 9,
                'attribute_value_id' => 14, // Da thật
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
