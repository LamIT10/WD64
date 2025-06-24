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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('status');

            $table->integer('order_status')->default(0);

            $table->timestamp('approved_at')->nullable()->after('order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('approved_at');
            $table->dropColumn('order_status');
            $table->enum('status', ['pending', 'received', 'closed'])->default('pending')->after('id');
        });
    }
};
