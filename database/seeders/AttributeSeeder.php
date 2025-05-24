<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('attributes')->insert([
            [
                'name' => 'Kích thước',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Màu sắc',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Trọng lượng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dung tích',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
