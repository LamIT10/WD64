<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_audits', 'code')) {
                $table->string('code')->nullable()->after('id'); // KHÔNG unique ở đây
            }
        });

        // Bước 2: Gán giá trị code tạm cho các dòng cũ để tránh duplicate
        DB::table('inventory_audits')->whereNull('code')->update([
            'code' => DB::raw("CONCAT('AUD-', id)")
        ]);

        // Bước 3: Thêm ràng buộc unique sau khi dữ liệu đã hợp lệ
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->unique('code');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
