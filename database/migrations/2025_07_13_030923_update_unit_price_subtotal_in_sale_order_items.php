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
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->integer('unit_price')->nullable()->change();
            $table->integer('subtotal')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->integer('unit_price')->change();
            $table->integer('subtotal')->change();
        });
    }
};
