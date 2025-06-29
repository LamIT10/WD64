<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressDeliveryToSaleOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->string('address_delivery')->nullable()->after('total_amount');
        });
    }

    public function down()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn('address_delivery');
        });
    }
}