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
        Schema::create('supplier_debt_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_transaction_id')->constrained('supplier_transactions')->onDelete('cascade');
            $table->string('new_value')->nullable()->comment('New due date or payment_amount updated');
            $table->enum('update_type', ['payment', 'due_date'])->comment('Type of update: payment or due_date');
            $table->text('note')->nullable()->comment('Additional notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_debt_history');
    }
};