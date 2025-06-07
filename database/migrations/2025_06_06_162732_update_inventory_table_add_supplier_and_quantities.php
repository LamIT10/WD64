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
            // Xoá cột quantity cũ
            $table->dropColumn('quantity');

            // Thêm các cột mới
            $table->unsignedBigInteger('supplier_id')->nullable()->after('product_variant_id');
            $table->unsignedBigInteger('warehouse_zone_id')->nullable()->after('supplier_id');
            $table->integer('quantity_on_hand')->nullable()->after('warehouse_zone_id');
            $table->integer('quantity_reserved')->nullable()->after('quantity_on_hand');
            $table->integer('quantity_in_transit')->nullable()->after('quantity_reserved');

            // Foreign key nếu cần
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('warehouse_zone_id')->references('id')->on('warehouse_zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Thêm lại cột quantity
            $table->integer('quantity')->default(0);

            // Xoá các cột đã thêm
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['warehouse_zone_id']);
            $table->dropColumn([
                'supplier_id',
                'warehouse_zone_id',
                'quantity_on_hand',
                'quantity_reserved',
                'quantity_in_transit'
            ]);
        });
    }
};
