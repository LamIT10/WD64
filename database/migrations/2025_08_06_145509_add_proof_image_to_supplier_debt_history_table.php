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
        Schema::table('supplier_debt_history', function (Blueprint $table) {
            // Thêm cột proof_image kiểu string (để lưu tên file hoặc đường dẫn)
            $table->string('proof_image')->nullable();  // 'column_name' là tên cột trước cột proof_image
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_debt_history', function (Blueprint $table) {
            // Xóa cột proof_image
            $table->dropColumn('proof_image');
        });
    }
};
