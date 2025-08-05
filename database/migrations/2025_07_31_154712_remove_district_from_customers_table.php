<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDistrictFromCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('district');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('district')->nullable()->after('province');
        });
    }
}
