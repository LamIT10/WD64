<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        // Admin role gets all permissions
        for ($permissionId = 1; $permissionId <= 6; $permissionId++) {
            DB::table('role_permissions')->insert([
                'role_id' => 1, // Admin
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Manager role gets most permissions
        for ($permissionId = 1; $permissionId <= 5; $permissionId++) {
            DB::table('role_permissions')->insert([
                'role_id' => 2, // Manager
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Inventory Clerk permissions
        DB::table('role_permissions')->insert([
            [
                'role_id' => 3, // Inventory Clerk
                'permission_id' => 1, // view_dashboard
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 3, // Inventory Clerk
                'permission_id' => 2, // manage_inventory
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Sales permissions
        DB::table('role_permissions')->insert([
            [
                'role_id' => 4, // Sales
                'permission_id' => 1, // view_dashboard
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 4, // Sales
                'permission_id' => 3, // manage_orders
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Shipping permissions
        DB::table('role_permissions')->insert([
            [
                'role_id' => 5, // Shipping
                'permission_id' => 1, // view_dashboard
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 5, // Shipping
                'permission_id' => 3, // manage_orders
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Receiving permissions
        DB::table('role_permissions')->insert([
            [
                'role_id' => 6, // Receiving
                'permission_id' => 1, // view_dashboard
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 6, // Receiving
                'permission_id' => 2, // manage_inventory
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
