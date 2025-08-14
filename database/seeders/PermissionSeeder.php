<?php

namespace Database\Seeders;

use App\Constant\ExtendsionConstant;
use App\Constant\PermissionConstant;
use App\Constant\RoleConstant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = now();
        $listPermissionByConst = [];
        foreach (PermissionConstant::all() as $item) {
            foreach ($item['permissions'] as $permission) {
                $listPermissionByConst[] = $permission['name'];
                if (!Permission::where('name', $permission['name'])->first()) {
                    $newPermission = [
                        'name' => $permission['name'],
                        'description' => $permission['description'],
                        'group_permission' => $item['group_permission'],
                        "group_description" => $item['group_description'],
                        'guard_name' => 'web',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                 Permission::insert($newPermission);
                }
            }
        }
        foreach (Permission::all() as $key => $value) {
           if(!in_array($value['name'], $listPermissionByConst)){
               Permission::where('name', $value['name'])->delete();
           }
        }
 
        foreach (ExtendsionConstant::getConstantsAsArray(RoleConstant::class) as $value) {
            if (!Role::where('name', $value['value'])->first()) {
                $newRole = [
                    'description' => $value['text'] ?? null,
                    'name' => $value['value'],
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                Role::create($newRole);
            }
        }
        $admin = Role::where('name', 'admin')->first();
        // gán quyền mặc định toàn bộ permission cho role admin
        $newPermissionIds = Permission::pluck(('id'));
        $admin->syncPermissions($newPermissionIds);


        // nếu tài khoản admin tồn tại thì xoá 
        $user = User::where('name', 'admin')->first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'position' => 'Quản trị hệ thống',
                'gender' => 'male',
                'phone' => '0388954747',
                'avatar' => 'avatars/admin.png',
                'address' => 'Hà Nội',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => 'Tài khoản quản trị',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // tạo admin mặc định

            $user = User::where('id', $userId)->first();
        }
        $user->syncRoles($admin->id);
    }
}
