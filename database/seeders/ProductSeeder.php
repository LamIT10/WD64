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
                'min_stock' => 100,
                'supplier_id' => 1,
                'description' => 'Gạo nếp cái hoa vàng chất lượng cao',
                'category_id' => 4, // Thực phẩm khô
                'expiration_date' => Carbon::now()->addMonths(12),
                'production_date' => Carbon::now()->subMonths(1),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Coca Cola',
                'min_stock' => 200,
                'supplier_id' => 2,
                'description' => 'Nước giải khát có gas Coca Cola',
                'category_id' => 6, // Nước ngọt
                'expiration_date' => Carbon::now()->addMonths(6),
                'production_date' => Carbon::now()->subWeeks(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bột Ngọt',
                'min_stock' => 50,
                'supplier_id' => 3,
                'description' => 'Bột ngọt tinh khiết',
                'category_id' => 3, // Gia vị
                'expiration_date' => Carbon::now()->addYears(2),
                'production_date' => Carbon::now()->subMonths(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thịt Bò Đông Lạnh',
                'min_stock' => 100,
                'supplier_id' => 4,
                'description' => 'Thịt bò Úc đông lạnh',
                'category_id' => 5, // Thực phẩm đông lạnh
                'expiration_date' => Carbon::now()->addMonths(6),
                'production_date' => Carbon::now()->subWeeks(1),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bột Ớt',
                'min_stock' => 30,
                'supplier_id' => 3,
                'description' => 'Bột ớt khô cay nồng',
                'category_id' => 3, // Gia vị
                'expiration_date' => Carbon::now()->addYears(1),
                'production_date' => Carbon::now()->subMonths(3),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
