<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryAuditItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('inventory_audit_items')->insert([
            // Audit 1 items
            [
                'audit_id' => 1,
                'product_variant_id' => 1, // iPhone 15 Pro Purple
                'expected_quantity' => 20,
                'actual_quantity' => 20,
                'discrepancy_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'audit_id' => 1,
                'product_variant_id' => 2, // iPhone 15 Pro Gold
                'expected_quantity' => 15,
                'actual_quantity' => 15,
                'discrepancy_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Audit 2 items (with discrepancies)
            [
                'audit_id' => 2,
                'product_variant_id' => 3, // Samsung Galaxy S24 Black
                'expected_quantity' => 25,
                'actual_quantity' => 23,
                'discrepancy_reason' => 'Thiếu 2 sản phẩm - có thể do lỗi nhập liệu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'audit_id' => 2,
                'product_variant_id' => 6, // Áo sơ mi nam M
                'expected_quantity' => 35,
                'actual_quantity' => 30,
                'discrepancy_reason' => 'Thiếu 5 sản phẩm - cần kiểm tra lại khu vực B',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
