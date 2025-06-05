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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'rank_id')) {
                $table->foreignId('rank_id')
                    ->nullable()
                    ->constrained('ranks')
                    ->nullOnDelete()
                    ->after('password');
            }

            if (!Schema::hasColumn('customers', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('current_debt');
            }

            if (!Schema::hasColumn('customers', 'avatar')) {
                $table->string('avatar', 255)->nullable()->after('address');
            }

            if (!Schema::hasColumn('customers', 'max_debt_limit')) {
                $table->decimal('max_debt_limit', 15, 2)->default(0)->after('current_debt');
            }

            if (!Schema::hasColumn('customers', 'total_spent')) {
                $table->decimal('total_spent', 15, 2)->default(0)->after('max_debt_limit');
            }
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['rank_id']);
            $table->dropColumn([
                'rank_id',
                'status',
                'avatar',
                'max_debt_limit',
                'total_spent'
            ]);
        });
    }
};
