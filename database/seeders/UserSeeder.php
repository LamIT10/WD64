<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role_id' => 1, // Admin
                'email' => 'admin@example.com',
                'phone' => '0909123456',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'quanly',
                'password' => Hash::make('quanly123'),
                'role_id' => 2, // Manager
                'email' => 'manager@example.com',
                'phone' => '0908123457',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'kiemke',
                'password' => Hash::make('kiemke123'),
                'role_id' => 3, // Inventory Clerk
                'email' => 'inventory@example.com',
                'phone' => '0907123458',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'banhang',
                'password' => Hash::make('banhang123'),
                'role_id' => 4, // Sales
                'email' => 'sales@example.com',
                'phone' => '0906123459',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'vanchuyen',
                'password' => Hash::make('vanchuyen123'),
                'role_id' => 5, // Shipping
                'email' => 'shipping@example.com',
                'phone' => '0905123460',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'nhanhang',
                'password' => Hash::make('nhanhang123'),
                'role_id' => 6, // Receiving
                'email' => 'receiving@example.com',
                'phone' => '0904123461',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
