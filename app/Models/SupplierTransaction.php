<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'paid_amount',
        'remaining_amount',
        'transaction_date',
        'credit_due_date',
        'description',
        'goods_receipt_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'credit_due_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function supplierDebtHistories(){
        return $this->belongsTo(PurchaseOrder::class, "supplier_transaction_id");
    }
    public function goodReceipt(){
        return $this->belongsTo(GoodReceipt::class, 'goods_receipt_id');
    }
}
