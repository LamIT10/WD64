<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('inventory_locations')->insert([
            [
                'product_id' => 1, // iPhone 15 Pro
                'zone_id' => 1, // Khu A
                'custom_location_name' => 'A-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2, // Samsung Galaxy S24
                'zone_id' => 1, // Khu A
                'custom_location_name' => 'A-01-002',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 3, // MacBook Pro M3
                'zone_id' => 3, // Khu C
                'custom_location_name' => 'C-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 4, // Áo sơ mi nam
                'zone_id' => 2, // Khu B
                'custom_location_name' => 'B-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 5, // Giày thể thao Nike
                'zone_id' => 2, // Khu B
                'custom_location_name' => 'B-02-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
