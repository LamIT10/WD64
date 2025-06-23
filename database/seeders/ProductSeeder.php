<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('products')->insert([
            [
                'name' => 'Gạo Nếp',
                'code' => 'GN001',
                'min_stock' => 100,
                'description' => 'Gạo nếp cái hoa vàng chất lượng cao',
                'category_id' => 4,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addMonths(12),
                'production_date' => Carbon::now()->subMonths(1),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Coca Cola',
                'code' => 'CC001',
                'min_stock' => 200,
                'description' => 'Nước giải khát có gas Coca Cola',
                'category_id' => 6,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addMonths(6),
                'production_date' => Carbon::now()->subWeeks(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bột Ngọt',
                'code' => 'BN001',
                'min_stock' => 50,
                'description' => 'Bột ngọt tinh khiết',
                'category_id' => 3,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addYears(2),
                'production_date' => Carbon::now()->subMonths(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thịt Bò Đông Lạnh',
                'code' => 'TB001',
                'min_stock' => 100,
                'description' => 'Thịt bò Úc đông lạnh',
                'category_id' => 5,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addMonths(6),
                'production_date' => Carbon::now()->subWeeks(1),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bột Ớt',
                'code' => 'BO001',
                'min_stock' => 30,
                'description' => 'Bột ớt khô cay nồng',
                'category_id' => 3,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addYears(1),
                'production_date' => Carbon::now()->subMonths(3),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nước Suối Lavie',
                'code' => 'NSL001',
                'min_stock' => 150,
                'description' => 'Nước suối tinh khiết Lavie',
                'category_id' => 6,
                'default_unit_id' => 5, // Cái
                'expiration_date' => Carbon::now()->addMonths(12),
                'production_date' => Carbon::now()->subWeeks(1),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
