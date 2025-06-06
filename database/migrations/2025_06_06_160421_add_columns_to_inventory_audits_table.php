<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->boolean('is_adjusted')->default(false)->after('status');

            $table->unsignedBigInteger('approved_by')->nullable()->after('user_id');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('adjusted_at')->nullable()->after('is_adjusted');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->dropColumn('is_adjusted');

            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');

            $table->dropColumn('adjusted_at');
        });
    }
};
