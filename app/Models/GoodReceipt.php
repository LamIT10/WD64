<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodReceipt extends Model
{
    protected $table = 'good_receipts';
    protected $fillable = [
        'purchase_order_id',
        'code',
        'note',
        'receipt_date',
        'status',
        'created_at',
        'updated_at',
        'approved_by',
        'created_by',
        'approved_at',
        'total_amount',
    ];
    protected $casts = [
    'receipt_date' => 'date',
];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function items()
    {
        return $this->hasMany(GoodReceiptItem::class, 'good_receipt_id');
    }
    public function createBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function supplierTransaction(){
        return $this->hasOne(SupplierTransaction::class, 'goods_receipt_id');
    }
    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }
}
