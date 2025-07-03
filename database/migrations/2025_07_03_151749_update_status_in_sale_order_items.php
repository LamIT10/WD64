<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusInSaleOrderItems extends Migration
{
    public function up()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->enum('status', ['pending', 'shipped', 'completed'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->enum('status', ['pending', 'shipped', 'closed'])->default('pending')->change();
        });
    }
}