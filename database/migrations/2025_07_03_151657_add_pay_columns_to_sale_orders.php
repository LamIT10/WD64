<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayColumnsToSaleOrders extends Migration
{
    public function up()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->decimal('pay_before', 15, 2)->default(0)->after('total_amount');
            $table->decimal('pay_after', 15, 2)->default(0)->after('pay_before');
        });
    }

    public function down()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn(['pay_before', 'pay_after']);
        });
    }
}