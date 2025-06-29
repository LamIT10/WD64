<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndCreditDueDateToCustomerTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->enum('type', ['debt', 'payment', 'adjustment'])->default('payment')->after('sales_order_id');
            $table->date('credit_due_date')->nullable()->after('transaction_date');
        });
    }

    public function down()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('credit_due_date');
        });
    }
}
