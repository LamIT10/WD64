<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('settings')->insert([
            [
                'name' => 'Kho Hàng ABC',
                'location' => '123 Đường Hàng Kho, Quận 1, TP.HCM',
                'logo' => 'logo_abc.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
