<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('customers')->insert([
            [
                'name' => 'Nguyễn Văn Nam',
                'phone' => '0912345678',
                'email' => 'nguyenvannam@gmail.com',
                'password' => Hash::make('123456'),
                'address' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
                'current_debt' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
                'rank_id' => 1,
            ],
            [
                'name' => 'Trần Thị Hoa',
                'phone' => '0912345679',
                'email' => 'tranthihoa@gmail.com',
                'password' => Hash::make('123456'),
                'address' => '456 Đường Nguyễn Huệ, Quận 3, TP.HCM',
                'current_debt' => 2500000.00,
                'created_at' => $now,
                'updated_at' => $now,
                'rank_id' => 2,
            ],
            [
                'name' => 'Công ty TNHH Minh Anh',
                'phone' => '0281112233',
                'email' => 'info@minhanh.com',
                'password' => Hash::make('123456'),
                'address' => '789 Đường Võ Văn Tần, Quận 10, TP.HCM',
                'current_debt' => 15000000.00,
                'created_at' => $now,
                'updated_at' => $now,
                'rank_id' => 3,
            ],
            [
                'name' => 'Phạm Thanh Tùng',
                'phone' => '0912345680',
                'email' => 'phamthanhtung@yahoo.com',
                'password' => Hash::make('123456'),
                'address' => '321 Đường Pasteur, Quận 1, TP.HCM',
                'current_debt' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
                'rank_id' => 1,
            ],
        ]);
    }
}
