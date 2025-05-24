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
        Schema::create('shipping_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_id')->constrained('shipping');
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->integer('quantity_shipped');
            $table->text('notes')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_items');
    }
};
