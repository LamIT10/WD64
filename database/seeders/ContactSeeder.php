<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('contacts')->insert([
            // Supplier contacts
            [
                'type' => 'supplier',
                'entity_id' => 1, // Apple Authorized
                'name' => 'Nguyễn Quang Hải',
                'position' => 'Account Manager',
                'phone' => '0901234567',
                'email' => 'hai.nguyen@apple-auth.vn',
                'notes' => 'Phụ trách khu vực miền Nam, có thể liên hệ 24/7',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'supplier',
                'entity_id' => 1, // Apple Authorized
                'name' => 'Trần Thị Lan',
                'position' => 'Technical Support',
                'phone' => '0901234568',
                'email' => 'lan.tran@apple-auth.vn',
                'notes' => 'Hỗ trợ kỹ thuật và bảo hành sản phẩm',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'supplier',
                'entity_id' => 2, // Samsung Vietnam
                'name' => 'Lê Văn Minh',
                'position' => 'Sales Director',
                'phone' => '0902345678',
                'email' => 'minh.le@samsung.vn',
                'notes' => 'Có thẩm quyền quyết định giá và chính sách',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'supplier',
                'entity_id' => 3, // Dell Technologies
                'name' => 'Phạm Thị Hương',
                'position' => 'Business Developer',
                'phone' => '0903456789',
                'email' => 'huong.pham@dell.com',
                'notes' => 'Phụ trách phát triển kinh doanh B2B',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Customer contacts
            [
                'type' => 'customer',
                'entity_id' => 1, // Trần Văn An
                'name' => 'Trần Văn An',
                'position' => 'CEO',
                'phone' => '0987654321',
                'email' => 'an.tran@company.vn',
                'notes' => 'Khách hàng VIP, thường mua số lượng lớn',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'customer',
                'entity_id' => 2, // Lê Thị Bình
                'name' => 'Lê Thị Bình',
                'position' => 'Procurement Manager',
                'phone' => '0976543210',
                'email' => 'binh.le@enterprise.vn',
                'notes' => 'Thanh toán đúng hạn, quan tâm đến chất lượng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'customer',
                'entity_id' => 3, // Phạm Minh Cường
                'name' => 'Phạm Minh Cường',
                'position' => 'IT Director',
                'phone' => '0965432109',
                'email' => 'cuong.pham@tech.vn',
                'notes' => 'Chuyên về thiết bị công nghệ, có hiểu biết sâu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
