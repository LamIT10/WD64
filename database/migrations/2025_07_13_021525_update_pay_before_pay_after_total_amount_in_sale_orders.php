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
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->integer('pay_before')->change();
            $table->integer('pay_after')->change();
            $table->integer('total_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->decimal('pay_before', 15, 2)->change();
            $table->decimal('pay_after', 15, 2)->change();
            $table->decimal('total_amount', 15, 2)->change();
        });
    }
};
