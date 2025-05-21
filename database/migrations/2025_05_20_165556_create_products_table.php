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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('min_stock');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->date('expiration_date')->nullable();
            $table->date('production_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
