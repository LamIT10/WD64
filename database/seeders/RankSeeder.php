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
                'customer_id' => 1,
                'name' => 'Đồng',
                'min_total_spent' => 0.00,
                'discount_percent' => 2.00,
                'credit_percent' => 5.00,
                'note' => 'Hạng khách hàng cơ bản',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 2,
                'name' => 'Bạc',
                'min_total_spent' => 10000000.00,
                'discount_percent' => 5.00,
                'credit_percent' => 10.00,
                'note' => 'Hạng khách hàng trung cấp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 3,
                'name' => 'Vàng',
                'min_total_spent' => 50000000.00,
                'discount_percent' => 8.00,
                'credit_percent' => 15.00,
                'note' => 'Hạng khách hàng cao cấp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 4,
                'name' => 'Đồng',
                'min_total_spent' => 0.00,
                'discount_percent' => 2.00,
                'credit_percent' => 5.00,
                'note' => 'Hạng khách hàng cơ bản',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
