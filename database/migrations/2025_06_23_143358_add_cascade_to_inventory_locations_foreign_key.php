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
            $table->dropForeign(['product_variant_id']);

            // Sau đó tạo lại foreign key với ON DELETE CASCADE
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_locations', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);

            // Thêm lại foreign key không có cascade (nếu cần rollback)
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete(); // hoặc ->restrictOnDelete(); nếu bạn muốn chặn xoá
        });
    }
};
