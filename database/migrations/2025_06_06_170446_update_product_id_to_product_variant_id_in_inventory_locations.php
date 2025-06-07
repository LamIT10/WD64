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
        Schema::table('inventory_locations', function (Blueprint $table) {
            // Xóa khóa ngoại và cột product_id cũ
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            // Thêm cột product_variant_id
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_locations', function (Blueprint $table) {
            // Xóa khóa ngoại và cột product_variant_id
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');

            // Thêm lại cột product_id
            $table->foreignId('product_id')->nullable()->constrained('products');
        });
    }
};
