<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('export_histories', function (Blueprint $table) {
            $table->id();

            // Đơn bán liên quan (giả sử bảng sale_orders đã tồn tại)
            $table->bigInteger('sale_order_id');
                 
            // Tên hành động (ví dụ: 'export_created', 'export_approved', 'printed', ...)
            $table->string('action_name', 100)->index();

            // Nội dung mô tả (chi tiết xuất kho, ghi chú, JSON, v.v.)
            $table->text('content')->nullable();

            // Người tạo lịch sử (giả sử bảng users đã tồn tại)
            $table->bigInteger('created_id'); // không cho xoá user nếu còn lịch sử; 
                                        // nếu muốn cho xoá user thì dùng ->nullable()->nullOnDelete()

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_histories');
    }
};
