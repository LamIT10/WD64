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
            // Gạo Nếp variants
            [
                'product_id' => 1,
                'barcode' => 'GN001-01',
                'sale_price' => 25000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 1,
                'barcode' => 'GN001-02',
                'sale_price' => 27000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Coca Cola variants
            [
                'product_id' => 2,
                'barcode' => 'CC001-01',
                'sale_price' => 12000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'barcode' => 'CC001-02',
                'sale_price' => 13000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Bột Ngọt variants
            [
                'product_id' => 3,
                'barcode' => 'BN001-01',
                'sale_price' => 6000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Thịt Bò Đông Lạnh variants
            [
                'product_id' => 4,
                'barcode' => 'TB001-01',
                'sale_price' => 180000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 4,
                'barcode' => 'TB001-02',
                'sale_price' => 190000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Bột Ớt variants
            [
                'product_id' => 5,
                'barcode' => 'BO001-01',
                'sale_price' => 4000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 5,
                'barcode' => 'BO001-02',
                'sale_price' => 4500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Nước Suối Lavie variants
            [
                'product_id' => 6,
                'barcode' => 'NSL001-01',
                'sale_price' => 9000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
