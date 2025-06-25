<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          // ✅ Thêm credit_due_date vào bảng sale_orders
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->date('credit_due_date')->nullable()->after('total_amount');
        });

        // ✅ Di chuyển dữ liệu từ customer_transactions sang sale_orders
        DB::statement("
            UPDATE sale_orders
            JOIN (
                SELECT sales_order_id, MAX(credit_due_date) AS latest_due_date
                FROM customer_transactions
                WHERE credit_due_date IS NOT NULL
                GROUP BY sales_order_id
            ) AS t ON sale_orders.id = t.sales_order_id
            SET sale_orders.credit_due_date = t.latest_due_date
        ");

        // ✅ Xoá cột credit_due_date khỏi bảng customer_transactions
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropColumn('credit_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('customer_transactions', function (Blueprint $table) {
            $table->date('credit_due_date')->nullable()->after('transaction_date');
        });

        // ✅ Copy dữ liệu ngược lại từ sale_orders vào customer_transactions (optional)
        // Chỉ thực hiện cho bản ghi có transaction mới nhất
        DB::statement("
            UPDATE customer_transactions ct
            JOIN (
                SELECT id, sales_order_id
                FROM (
                    SELECT *,
                        ROW_NUMBER() OVER (PARTITION BY sales_order_id ORDER BY transaction_date DESC) AS rn
                    FROM customer_transactions
                ) AS sorted
                WHERE rn = 1
            ) AS latest ON ct.id = latest.id
            JOIN sale_orders so ON so.id = latest.sales_order_id
            SET ct.credit_due_date = so.credit_due_date
        ");

        // ✅ Xoá credit_due_date khỏi sale_orders
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn('credit_due_date');
        });
    }
};
