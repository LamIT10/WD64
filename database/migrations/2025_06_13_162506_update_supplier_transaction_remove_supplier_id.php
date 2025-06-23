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
            // Sử dụng tên ràng buộc chính xác
            $table->dropForeign('supplier_transactions_supplier_id_foreign');
            $table->dropColumn('supplier_id');
            $table->dropColumn('remaining_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->decimal('remaining_amount', 15, 2)->nullable();
        });
    }
};