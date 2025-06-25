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
        Schema::create('good_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_receipt_id')
                  ->nullable()
                  ->constrained('good_receipts')
                  ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('unit_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Đơn vị tính');

            $table->integer('quantity_expected')
                  ->nullable()
                  ->comment('Số lượng dự kiến theo PO (xem còn bao nhiêu chưa nhập)');

            $table->integer('quantity_received')
                  ->nullable()
                  ->comment('Số lượng nhận thực tế');

            $table->decimal('unit_price', 15, 2)
                  ->nullable()
                  ->comment('Giá nhập mỗi đơn vị');

            $table->decimal('subtotal', 15, 2)
                  ->nullable()
                  ->comment('Thành tiền mỗi biến thể');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipt_items');
    }
};
