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
        $now = now();
        $data = [];
        foreach (PermissionConstant::all() as $item) {
            foreach ($item['permissions'] as $permission) {
               $data[] = [
                'name' => $permission['name'],
                'description' => $permission['description'],
                'group_permission' => $item['group_permission'],
                "group_description" => $item['group_description'],
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
               ];
            }
        }
        DB::table('permissions')->insert($data);
    }
}
