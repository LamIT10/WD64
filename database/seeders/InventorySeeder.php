<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('inventory')->insert([
            [
                'product_variant_id' => 1,
                'supplier_id' => 1,
                'warehouse_zone_id' => 1,
                'quantity_on_hand' => 25,
                'quantity_reserved' => 0,
                'quantity_in_transit' => 0,
                'unit_id' => 1,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 2,
                'supplier_id' => 1, // Sửa từ 2 thành 1 để khớp với SupplierProductVariantSeeder
                'warehouse_zone_id' => 2,
                'quantity_on_hand' => 30,
                'quantity_reserved' => 2,
                'quantity_in_transit' => 1,
                'unit_id' => 1,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 3,
                'supplier_id' => 2, // Đổi thành 2 để khớp
                'warehouse_zone_id' => 3,
                'quantity_on_hand' => 20,
                'quantity_reserved' => 5,
                'quantity_in_transit' => 0,
                'unit_id' => 2,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 4,
                'supplier_id' => 2, // Đổi thành 2 để khớp
                'warehouse_zone_id' => 1,
                'quantity_on_hand' => 50,
                'quantity_reserved' => 10,
                'quantity_in_transit' => 3,
                'unit_id' => 2,
                'status' => 'reserved',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 5,
                'supplier_id' => 3, // Sửa thành 3 để khớp
                'warehouse_zone_id' => 2,
                'quantity_on_hand' => 12,
                'quantity_reserved' => 0,
                'quantity_in_transit' => 1,
                'unit_id' => 3,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 6,
                'supplier_id' => 4, // Sửa thành 4 để khớp
                'warehouse_zone_id' => 3,
                'quantity_on_hand' => 35,
                'quantity_reserved' => 5,
                'quantity_in_transit' => 0,
                'unit_id' => 3,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 7,
                'supplier_id' => 4, // Sửa thành 4 để khớp
                'warehouse_zone_id' => 2,
                'quantity_on_hand' => 18,
                'quantity_reserved' => 0,
                'quantity_in_transit' => 0,
                'unit_id' => 1,
                'status' => 'damaged',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 8,
                'supplier_id' => 3, // Sửa thành 3 để khớp
                'warehouse_zone_id' => 1,
                'quantity_on_hand' => 60,
                'quantity_reserved' => 20,
                'quantity_in_transit' => 10,
                'unit_id' => 2,
                'status' => 'reserved',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 9,
                'supplier_id' => 3, // Sửa thành 3 để khớp
                'warehouse_zone_id' => 3,
                'quantity_on_hand' => 40,
                'quantity_reserved' => 0,
                'quantity_in_transit' => 0,
                'unit_id' => 1,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_variant_id' => 10,
                'supplier_id' => 2, // Giữ nguyên
                'warehouse_zone_id' => 2,
                'quantity_on_hand' => 22,
                'quantity_reserved' => 3,
                'quantity_in_transit' => 1,
                'unit_id' => 3,
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
