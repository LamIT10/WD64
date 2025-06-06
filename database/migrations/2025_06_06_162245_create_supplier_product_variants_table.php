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
        Schema::create('supplier_product_variants', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('supplier_id'); // Nhà cung cấp
            $table->unsignedBigInteger('product_variant_id'); // Biến thể được cung cấp
            $table->decimal('cost_price', 15, 2); // Giá nhập
            $table->decimal('sale_price', 15, 2)->nullable(); // Giá bán đề xuất
            $table->integer('min_order_quantity')->nullable(); // Số lượng đặt hàng tối thiểu
            $table->timestamp('created_at')->useCurrent(); // Ngày tạo
            $table->timestamp('updated_at')->useCurrent(); // Ngày cập nhật

            // Ràng buộc khóa ngoại
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_product_variants');
    }
};
