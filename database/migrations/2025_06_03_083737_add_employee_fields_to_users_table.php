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
            $table->string('employee_code')->nullable()->after('id'); // Mã nhân viên
            $table->string('identity_number')->nullable()->after('employee_code'); // CMND/CCCD
            $table->date('birthday')->nullable()->after('identity_number'); // Ngày sinh
            $table->date('start_date')->nullable()->after('birthday'); // Ngày bắt đầu làm việc
            $table->string('facebook')->nullable()->after('start_date'); // Facebook
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
               Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_code',
                'identity_number',
                'birthday',
                'start_date',
                'facebook',
            ]);
        });

    }
};
