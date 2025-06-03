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
        Schema::table('products', function (Blueprint $table) {
            $table->string('code', 50);
            $table->decimal('sale_price', 15, 2)->default(0.00)->nullable();
            $table->decimal('cost_price', 15, 2)->default(0.00)->nullable();
            $table->integer('min_threshold_stock')->default(0);
            $table->dropForeign(['supplier_id']); // xóa khóa ngoại
            $table->dropColumn('supplier_id');   // xóa cột
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['code', 'cost_price', 'default_sale_price', 'min_threshold_stock', 'supplier_id']);
        });
    }
};
