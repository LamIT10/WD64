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
        Schema::create('damaged_expired_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->integer('quantity');
            $table->enum('status', ['damaged', 'expired']);
            $table->date('reported_date');
            $table->foreignId('reported_by')->nullable()->constrained('users');
            $table->text('reason')->nullable();
            $table->enum('action_taken', ['returned_to_supplier', 'discarded', 'sold_at_discount', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_expired_products');
    }
};
