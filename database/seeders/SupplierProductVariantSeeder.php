<?php

    namespace Database\Seeders;

    use Carbon\Carbon;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class SupplierProductVariantSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run()
        {
            $now = Carbon::now();

            DB::table('supplier_product_variants')->insert([
                // Nhà cung cấp 1 cung cấp Gạo Nếp
                [
                    'supplier_id' => 1,
                    'product_variant_id' => 1, // GN001-01
                    'cost_price' => 20000.00,
                    'min_order_quantity' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'supplier_id' => 1,
                    'product_variant_id' => 2, // GN001-02
                    'cost_price' => 22000.00,
                    'min_order_quantity' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                // Nhà cung cấp 2 cung cấp Coca Cola
                [
                    'supplier_id' => 2,
                    'product_variant_id' => 3, // CC001-01
                    'cost_price' => 10000.00,
                    'min_order_quantity' => 20,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'supplier_id' => 2,
                    'product_variant_id' => 4, // CC001-02
                    'cost_price' => 11000.00,
                    'min_order_quantity' => 20,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                // Nhà cung cấp 3 cung cấp Bột Ngọt
                [
                    'supplier_id' => 3,
                    'product_variant_id' => 5, // BN001-01
                    'cost_price' => 5000.00,
                    'min_order_quantity' => 15,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                // Nhà cung cấp 4 cung cấp Thịt Bò Đông Lạnh
                [
                    'supplier_id' => 4,
                    'product_variant_id' => 6, // TB001-01
                    'cost_price' => 160000.00,
                    'min_order_quantity' => 5,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'supplier_id' => 3,
                    'product_variant_id' => 7, // TB001-02
                    'cost_price' => 170000.00,
                    'min_order_quantity' => 5,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                // Nhà cung cấp 3 cung cấp Bột Ớt
                [
                    'supplier_id' => 3,
                    'product_variant_id' => 8, // BO001-01
                    'cost_price' => 3500.00,
                    'min_order_quantity' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'supplier_id' => 4,
                    'product_variant_id' => 9, // BO001-02
                    'cost_price' => 4000.00,
                    'min_order_quantity' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                // Nhà cung cấp 2 cung cấp Nước Suối Lavie
                [
                    'supplier_id' => 2,
                    'product_variant_id' => 10, // NSL001-01
                    'cost_price' => 8000.00,
                    'min_order_quantity' => 20,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }