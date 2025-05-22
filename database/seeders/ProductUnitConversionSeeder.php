<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductUnitConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('product_unit_conversions')->insert([
            [
                'product_id' => 1,
                'from_unit_id' => 1, // Cái
                'to_unit_id' => 3, // Thùng
                'conversion_factor' => 0.1000, // 10 cái = 1 thùng
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'from_unit_id' => 1, // Cái
                'to_unit_id' => 3, // Thùng
                'conversion_factor' => 0.1000, // 10 cái = 1 thùng
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 4,
                'from_unit_id' => 1, // Cái
                'to_unit_id' => 2, // Bộ
                'conversion_factor' => 0.5000, // 2 cái = 1 bộ
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 5,
                'from_unit_id' => 1, // Cái
                'to_unit_id' => 3, // Thùng
                'conversion_factor' => 0.0833, // 12 cái = 1 thùng
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
