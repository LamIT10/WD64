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
                'zone_id' => 1,
                'product_variant_id' => 1,
                'custom_location_name' => 'A-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'zone_id' => 1,
                'product_variant_id' => 2,
                'custom_location_name' => 'A-01-002',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'zone_id' => 3,
                'product_variant_id' => 3,
                'custom_location_name' => 'C-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'zone_id' => 2,
                'product_variant_id' => 4,
                'custom_location_name' => 'B-01-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'zone_id' => 2,
                'product_variant_id' => 5,
                'custom_location_name' => 'B-02-001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
