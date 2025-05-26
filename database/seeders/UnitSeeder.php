<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Lít',
                'symbol' => 'l',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mililít',
                'symbol' => 'ml',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cái',
                'symbol' => 'cái',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thùng',
                'symbol' => 'thùng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
