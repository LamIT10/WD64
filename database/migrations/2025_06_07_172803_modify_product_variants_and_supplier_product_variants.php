<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa cột sale_price trong bảng Supplier_Product_Variants
        Schema::table('supplier_product_variants', function (Blueprint $table) {
            $table->dropColumn('sale_price');
        });

        // Thêm cột sale_price vào bảng Product_Variants để lưu giá bán chung
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('sale_price', 15, 2)->nullable()->after('barcode'); // Thêm sau cột 'barcode'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // thêm lại cột sale_price vào bảng Supplier_Product_Variants
        Schema::table('supplier_product_variants', function (Blueprint $table) {
            $table->decimal('sale_price', 15, 2)->nullable();
        });

        // xóa cột sale_price trong bảng Product_Variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('sale_price');
        });
    }
};
