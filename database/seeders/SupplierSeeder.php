<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('suppliers')->insert([
            [
                'id' => 1,
                'name' => 'Công ty TNHH Thực Phẩm ABC',
                'contact_person' => 'Nguyễn Văn A',
                'phone' => '0909111222',
                'email' => 'contact@abcfoods.com',
                'address' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
                'current_debt' => 5000000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Công ty CP Đồ Uống XYZ',
                'contact_person' => 'Trần Thị B',
                'phone' => '0908222333',
                'email' => 'sales@xyzbev.com',
                'address' => '456 Đường Nguyễn Huệ, Quận 1, TP.HCM',
                'current_debt' => 3000000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Công ty Gia Vị DEF',
                'contact_person' => 'Lê Văn C',
                'phone' => '0907333444',
                'email' => 'info@defspices.com',
                'address' => '789 Đường Hai Bà Trưng, Quận 3, TP.HCM',
                'current_debt' => 2000000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Công ty TNHH Thực Phẩm Đông Lạnh MNO',
                'contact_person' => 'Phan Thị D',
                'phone' => '0906444555',
                'email' => 'contact@mnofoods.com',
                'address' => '101 Đường 3/2, Quận 10, TP.HCM',
                'current_debt' => 1500000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
