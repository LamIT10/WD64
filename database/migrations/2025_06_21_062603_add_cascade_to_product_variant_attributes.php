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
        Schema::table('product_variant_attributes', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['attribute_value_id']);
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variant_attributes', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['attribute_value_id']);
            $table->foreign('variant_id')->references('id')->on('product_variants');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');
        });
    }
};
