<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceivingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('receiving_items')->insert([
            // Receiving 1 items
            [
                'receiving_id' => 1,
                'product_variant_id' => 1, // iPhone 15 Pro Purple
                'unit_id' => 1, // Cái
                'quantity_received' => 15,
                'condition' => 'good',
                'quality_inspection_result' => 'Passed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'receiving_id' => 1,
                'product_variant_id' => 2, // iPhone 15 Pro Gold
                'unit_id' => 1, // Cái
                'quantity_received' => 10,
                'condition' => 'good',
                'quality_inspection_result' => 'Passed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Receiving 2 items
            [
                'receiving_id' => 2,
                'product_variant_id' => 3, // Samsung Galaxy S24 Black
                'unit_id' => 1, // Cái
                'quantity_received' => 20,
                'condition' => 'good',
                'quality_inspection_result' => 'Passed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'receiving_id' => 2,
                'product_variant_id' => 4, // Samsung Galaxy S24 White
                'unit_id' => 1, // Cái
                'quantity_received' => 12,
                'condition' => 'good',
                'quality_inspection_result' => 'Partial - 3 units missing',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Receiving 3 items (with issues)
            [
                'receiving_id' => 3,
                'product_variant_id' => 5, // MacBook Pro M3 Silver
                'unit_id' => 1, // Cái
                'quantity_received' => 1,
                'condition' => 'damaged',
                'quality_inspection_result' => 'Screen cracked',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
