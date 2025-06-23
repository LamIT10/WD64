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
        Schema::create('good_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_order_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('code', 100)->nullable();
            $table->date('receipt_date')->nullable()->comment('Ngày nhận hàng');
            $table->text('note')->nullable()->comment('Ghi chú phiếu');
            $table->integer('status')->default(0)->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnDelete()->comment('Người duyệt');
            $table->dateTime('approved_at')->nullable()->comment('Thời gian duyệt');

            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete()->comment('Người tạo phiếu');

            $table->decimal('total_amount', 15, 2)
                ->nullable()
                ->comment('Tổng tiền của phiếu nhập');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipts');
    }
};
