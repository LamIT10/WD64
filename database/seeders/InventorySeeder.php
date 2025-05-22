<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('inventory')->insert([
            [
                'product_variant_id' => 1,
                'quantity' => 25,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 2,
                'quantity' => 15,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 3,
                'quantity' => 20,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 4,
                'quantity' => 12,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 5,
                'quantity' => 8,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 6,
                'quantity' => 30,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 7,
                'quantity' => 25,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 8,
                'quantity' => 18,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 9,
                'quantity' => 22,
                'unit_id' => 1, // Cái
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Một số sản phẩm bị hỏng
            [
                'product_variant_id' => 1,
                'quantity' => 2,
                'unit_id' => 1, // Cái
                'status' => 'damaged',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
