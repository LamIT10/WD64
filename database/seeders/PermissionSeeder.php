<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table("permissions")->delete();
        $now = now(); // hoặc Carbon::now();
        $data = [];
        foreach (PermissionConstant::all() as $item) {
            $data[] = [
                "name" => $item,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('permissions')->insert($data);
    }
}
