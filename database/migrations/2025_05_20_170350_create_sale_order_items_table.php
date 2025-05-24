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
        Schema::create('sale_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sale_orders');
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->integer('quantity_ordered');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->integer('quantity_shipped')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_order_items');
    }
};
