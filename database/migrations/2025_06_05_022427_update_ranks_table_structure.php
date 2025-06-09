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
        Schema::table('ranks', function (Blueprint $table) {
            if (Schema::hasColumn('ranks', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }

            $table->integer('min_total_spent')->change();
            $table->integer('discount_percent')->change();
            $table->integer('credit_percent')->change();

            if (!Schema::hasColumn('ranks', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ranks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->decimal('min_total_spent', 15, 2)->change();
            $table->decimal('discount_percent', 5, 2)->change();
            $table->decimal('credit_percent', 5, 2)->change();
        });
    }
};
