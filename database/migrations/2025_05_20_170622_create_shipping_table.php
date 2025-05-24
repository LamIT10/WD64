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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_order_id')->nullable()->constrained('sale_orders');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('ship_date')->nullable();
            $table->string('carrier', 100)->nullable();
            $table->string('tracking_number', 50)->nullable();
            $table->enum('status', ['shipped', 'delivered', 'returned'])->default('shipped');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
