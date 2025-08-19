<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->string('proof_image')->nullable()->after('description'); // Link ảnh chứng từ
            $table->unsignedBigInteger('created_id')->nullable()->after('proof_image'); // ID người tạo
        });
    }

    public function down(): void
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropColumn('proof_image');
            $table->dropColumn('created_id');
        });
    }
};
