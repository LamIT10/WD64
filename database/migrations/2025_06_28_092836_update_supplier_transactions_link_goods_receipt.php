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
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
            $table->bigInteger('goods_receipt_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            // Thêm lại cột purchase_order_id
            $table->bigInteger('purchase_order_id')->nullable()->after('id');
    
            // Xóa cột goods_receipt_id
            $table->dropColumn('goods_receipt_id');
        });
    
        // Thêm lại foreign key sau khi cột đã được thêm
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->foreign('purchase_order_id')
                  ->references('id')
                  ->on('purchase_orders')
                  ->onDelete('set null');
        });
    }
};
