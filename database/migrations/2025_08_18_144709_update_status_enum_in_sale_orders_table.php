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
            $table->enum('status', [
                'pending',
                'shipped',
                'completed',
                'cancelled',
                'returning',
                'returned'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'shipped',
                'completed',
                'cancelled'
            ])->default('pending')->change();
        });
    }
};
