<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('ranks')->insert([
            [
                'name' => 'Sắt',
                'min_total_spent' => 0,
                'discount_percent' => 0,
                'credit_percent' => 0,
                'note' => 'Hạng khách hàng mặc định',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Đồng',
                'min_total_spent' => 5000000,
                'discount_percent' => 2,
                'credit_percent' => 3,
                'note' => '	Hạng khách hàng cơ bản',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bạc',
                'min_total_spent' => 10000000,
                'discount_percent' => 5,
                'credit_percent' => 6,
                'note' => 'Hạng khách hàng trung cấp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Vàng',
                'min_total_spent' => 20000000,
                'discount_percent' => 8,
                'credit_percent' => 10,
                'note' => 'Hạng khách hàng cao cấp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kim Cương',
                'min_total_spent' => 50000000,
                'discount_percent' => 15,
                'credit_percent' => 17,
                'note' => 'Hạng khách hàng thượng hạng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
        ]);
    }
}
