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
            $table->integer('pay_before')->nullable()->change();
            $table->integer('pay_after')->nullable()->change();
            $table->integer('total_amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->integer('pay_before')->change();
            $table->integer('pay_after')->change();
            $table->integer('total_amount')->change();
        });
    }
};
