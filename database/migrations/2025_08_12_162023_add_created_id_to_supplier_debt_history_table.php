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
        Schema::table('supplier_debt_history', function (Blueprint $table) {
            $table->unsignedBigInteger('created_id')->nullable(); // Thêm cột created_id sau cột id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_debt_history', function (Blueprint $table) {
            $table->dropColumn('created_id');
        });
    }
};
