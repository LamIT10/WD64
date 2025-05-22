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
        Schema::create('receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_id')->constrained('receiving');
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->integer('quantity_received');
            $table->enum('condition', ['good', 'damaged', 'incorrect'])->default('good');
            $table->string('quality_inspection_result', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving_items');
    }
};
