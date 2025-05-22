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
        Schema::create('receiving', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
            $table->foreignId('sale_order_id')->nullable()->constrained('sale_orders');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('receive_date')->nullable();
            $table->enum('status', ['completed', 'partial', 'issues'])->default('partial');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivings');
    }
};
