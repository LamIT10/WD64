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
        Schema::table('inventory', function (Blueprint $table) {
            // Xoá ràng buộc khóa ngoại hiện tại
            $table->dropForeign(['product_variant_id']);

            // Thêm lại với onDelete('cascade')
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
        Schema::table('inventory', function (Blueprint $table) {
            // Xoá ràng buộc vừa thêm
            $table->dropForeign(['product_variant_id']);

            // Thêm lại khóa ngoại như cũ (không cascade)
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants');
        });
    }
};
