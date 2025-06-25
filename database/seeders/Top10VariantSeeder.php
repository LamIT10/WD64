<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Top10VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Tạo 5 sản phẩm
        for ($i = 1; $i <= 5; $i++) {
            $productId = DB::table('products')->insertGetId([
                'name' => "Sản phẩm $i",
                'code' => "SP$i",
                'min_stock' => rand(10, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mỗi sản phẩm có 3 biến thể
            for ($j = 1; $j <= 3; $j++) {
                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'code' => "SP$i-V$j",
                    'barcode' => Str::random(10),
                    'sale_price' => rand(100000, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tạo từ 3 đến 7 đơn hàng mỗi biến thể
                for ($k = 1; $k <= rand(3, 7); $k++) {
                    $saleOrderId = DB::table('sale_orders')->insertGetId([
                        'customer_id' => rand(1, 5), // giả sử bạn có sẵn 5 customer
                        'order_date' => Carbon::now()->subDays(rand(0, 30)),
                        'status' => 'closed',
                        'total_amount' => 0, // cập nhật lại bên dưới
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $qty = rand(1, 20);
                    $price = DB::table('product_variants')->where('id', $variantId)->value('sale_price');
                    $subtotal = $qty * $price;

                    DB::table('sale_order_items')->insert([
                        'sales_order_id' => $saleOrderId,
                        'product_variant_id' => $variantId,
                        'quantity_ordered' => $qty,
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                        'quantity_shipped' => $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // cập nhật tổng đơn hàng
                    DB::table('sale_orders')->where('id', $saleOrderId)->increment('total_amount', $subtotal);
                }
            }
        }
    
    }
}
