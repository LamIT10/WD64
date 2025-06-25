<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('units')->insert([
            ['name' => 'Cái', 'symbol' => 'cái', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thùng', 'symbol' => 'thùng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kg', 'symbol' => 'kg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hộp', 'symbol' => 'hộp', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gói', 'symbol' => 'gói', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lốc', 'symbol' => 'lốc', 'created_at' => $now, 'updated_at' => $now], // ID = 6
        ]);
    }
}