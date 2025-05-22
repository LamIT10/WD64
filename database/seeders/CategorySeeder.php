<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        // Tạo danh mục chính
        DB::table('categories')->insert([
            [
                'name' => 'Thực phẩm',
                'parent_id' => null,
                'description' => 'Các loại thực phẩm',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Đồ uống',
                'parent_id' => null,
                'description' => 'Các loại đồ uống',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Gia vị',
                'parent_id' => null,
                'description' => 'Các loại gia vị',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Tạo danh mục con
        DB::table('categories')->insert([
            [
                'name' => 'Thực phẩm khô',
                'parent_id' => 1, // Thuộc danh mục Thực phẩm
                'description' => 'Các loại thực phẩm khô bảo quản lâu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thực phẩm đông lạnh',
                'parent_id' => 1, // Thuộc danh mục Thực phẩm
                'description' => 'Các loại thực phẩm đông lạnh',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nước ngọt',
                'parent_id' => 2, // Thuộc danh mục Đồ uống
                'description' => 'Các loại nước giải khát có gas',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
