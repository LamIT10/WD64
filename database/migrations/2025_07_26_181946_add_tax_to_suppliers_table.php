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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('tax', 30)->nullable()->comment('mã số thuế'); 
            $table->string('code', 30)->nullable()->comment('mã nhà cung cấp'); 
            $table->integer('status')->nullable(); 
            $table->integer('type')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['tax', 'type', 'status', 'code']);
        });
    }
};
