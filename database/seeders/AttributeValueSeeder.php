<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            // Màu sắc
            [
                'attribute_id' => 1,
                'name' => 'Đen',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 1,
                'name' => 'Trắng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 1,
                'name' => 'Xanh',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 1,
                'name' => 'Đỏ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Kích thước
            [
                'attribute_id' => 2,
                'name' => 'S',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 2,
                'name' => 'M',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 2,
                'name' => 'L',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 2,
                'name' => 'XL',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Dung lượng
            [
                'attribute_id' => 3,
                'name' => '128GB',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 3,
                'name' => '256GB',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 3,
                'name' => '512GB',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Chất liệu
            [
                'attribute_id' => 4,
                'name' => 'Cotton',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 4,
                'name' => 'Polyester',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'attribute_id' => 4,
                'name' => 'Da thật',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
