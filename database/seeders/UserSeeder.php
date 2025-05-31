<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'position' => 'Quản trị hệ thống',
                'fullname' => 'Nguyễn Văn A',
                'gender' => 'male',
                'avatar' => 'avatars/admin.png',
                'address' => 'Hà Nội',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => 'Tài khoản quản trị',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'quanly',
                'email' => 'manager@example.com',
                'password' => Hash::make('quanly123'),
                'position' => 'Quản lý kho',
                'fullname' => 'Trần Thị B',
                'gender' => 'female',
                'avatar' => 'avatars/manager.png',
                'address' => 'Đà Nẵng',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => 'Phụ trách kho miền Trung',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'kiemke',
                'email' => 'inventory@example.com',
                'password' => Hash::make('kiemke123'),
                'position' => 'Nhân viên kiểm kê',
                'fullname' => 'Lê Văn C',
                'gender' => 'male',
                'avatar' => 'avatars/inventory.png',
                'address' => 'TP.HCM',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'banhang',
                'email' => 'sales@example.com',
                'password' => Hash::make('banhang123'),
                'position' => 'Nhân viên bán hàng',
                'fullname' => 'Phạm Thị D',
                'gender' => 'female',
                'avatar' => 'avatars/sales.png',
                'address' => 'Cần Thơ',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'vanchuyen',
                'email' => 'shipping@example.com',
                'password' => Hash::make('vanchuyen123'),
                'position' => 'Nhân viên vận chuyển',
                'fullname' => 'Nguyễn Văn E',
                'gender' => 'male',
                'avatar' => 'avatars/shipping.png',
                'address' => 'Hải Phòng',
                'status' => 'inactive',
                'last_login_at' => null,
                'note' => 'Tạm nghỉ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'nhanhang',
                'email' => 'receiving@example.com',
                'password' => Hash::make('nhanhang123'),
                'position' => 'Nhân viên nhận hàng',
                'fullname' => 'Trần Văn F',
                'gender' => 'female',
                'avatar' => 'avatars/receiving.png',
                'address' => 'Huế',
                'status' => 'active',
                'last_login_at' => $now,
                'note' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
