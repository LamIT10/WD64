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
        Schema::table("permissions", function (Blueprint $table) {
            $table->string("group_permission", 100);
            $table->string("group_description", 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("permissions", function (Blueprint $table) {
            $table->dropColumn("group_permission");
            $table->dropColumn("group_description");
        });
    }
};
