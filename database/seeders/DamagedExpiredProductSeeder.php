<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DamagedExpiredProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('damaged_expired_products')->insert([
            [
                'product_variant_id' => 6, // Áo sơ mi nam M
                'quantity' => 3,
                'status' => 'damaged',
                'reported_date' => '2024-05-10',
                'reported_by' => 3, // Inventory Clerk
                'reason' => 'Bị ướt do rò rỉ nước mưa vào kho',
                'action_taken' => 'sold_at_discount',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 7, // Áo sơ mi nam L
                'quantity' => 2,
                'status' => 'damaged',
                'reported_date' => '2024-05-12',
                'reported_by' => 2, // Manager
                'reason' => 'Bị rách do xử lý không cẩn thận khi di chuyển',
                'action_taken' => 'discarded',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 8, // Áo sơ mi nam XL
                'quantity' => 1,
                'status' => 'expired',
                'reported_date' => '2024-05-20',
                'reported_by' => 3, // Inventory Clerk
                'reason' => 'Sản phẩm thời trang đã quá mùa, không còn xu hướng',
                'action_taken' => 'sold_at_discount',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
