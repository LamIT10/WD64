<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('attribute_values')->insert([
        ['attribute_id' => 1, 'name' => '500g', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => '1kg', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 2, 'name' => 'Original', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 2, 'name' => 'Spicy', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 4, 'name' => '250ml', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => 'M', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => 'L', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => 'XL', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => '128GB', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => '256GB', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 1, 'name' => '512GB', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 2, 'name' => 'Cotton', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 2, 'name' => 'Xanh', 'created_at' => $now, 'updated_at' => $now],
        ['attribute_id' => 2, 'name' => 'Da thật', 'created_at' => $now, 'updated_at' => $now],
    ]);
    }
}