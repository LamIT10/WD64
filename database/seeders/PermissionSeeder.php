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
        // DB::table("roles")->delete();
        // DB::table("permissions")->delete();
        
        $now = now();
        $newPermissionIds = [];
        foreach (PermissionConstant::all() as $item) {
            foreach ($item['permissions'] as $permission) {
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
                    $newPermissionIds[] =  Permission::insertGetId($newPermission);
                }
            }
        }

 
        foreach (ExtendsionConstant::getConstantsAsArray(RoleConstant::class) as $value) {
            if (!Role::where('name', $value['value'])->first()) {
                $newRole = [
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
