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
            $table->enum('status', ['pending', 'shipped', 'completed'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            if (Schema::hasColumn('sale_orders', 'pay_before')) {
                $table->dropColumn('pay_before');
            }
            if (Schema::hasColumn('sale_orders', 'pay_after')) {
                $table->dropColumn('pay_after');
            }
            $table->enum('status', ['pending', 'shipped', 'closed'])->default('pending')->change();
        });
    }
}