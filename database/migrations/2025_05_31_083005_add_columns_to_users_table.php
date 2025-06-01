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
        Schema::table('users', function (Blueprint $table) {
            $table->string('position')->nullable(); // Chức vụ
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Giới tính
            $table->string('avatar')->nullable(); // Đường dẫn ảnh đại diện
            $table->text('address')->nullable(); // Địa chỉ
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // Trạng thái
            $table->timestamp('last_login_at')->nullable(); // Lần đăng nhập cuối
            $table->text('note')->nullable(); // Ghi chú
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn([
                'position',
                'gender',
                'avatar',
                'address',
                'status',
                'last_login_at',
                'note',
            ]);
        });
    }
};
