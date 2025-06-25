<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Đổi kiểu cột status thành string tạm thời để loại bỏ giá trị 'pending'
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->string('status_tmp')->nullable();
        });

        // Copy dữ liệu từ status sang status_tmp, loại bỏ 'pending' nếu cần
        DB::table('inventory_audits')->update([
            'status_tmp' => DB::raw("CASE WHEN status = 'pending' THEN 'completed' ELSE status END")
        ]);

        // Xóa cột status cũ
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Đổi tên lại cột status_tmp thành status và set lại enum mới
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->enum('status', ['completed', 'issues'])->default('completed')->after('audit_date');
            $table->dropColumn('status_tmp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->enum('status_tmp', ['pending', 'completed', 'issues'])->default('pending')->after('audit_date');
        });

        DB::table('inventory_audits')->update([
            'status_tmp' => DB::raw("CASE WHEN status = 'completed' THEN 'pending' ELSE status END")
        ]);

        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'issues'])->default('pending')->after('audit_date');
            $table->dropColumn('status_tmp');
        });
    }
};