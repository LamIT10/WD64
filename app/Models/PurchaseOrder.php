<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'user_id', 'order_date', 
        'order_status', 'total_amount', 'approved_at'
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplierTransactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function receiving()
    {
        return $this->hasMany(Receiving::class);
    }
    public function goodReceipts(){
        return $this->hasMany(GoodReceipt::class, 'purchase_order_id');
    }
}
