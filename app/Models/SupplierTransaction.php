<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'paid_amount',
        'remaining_amount',
        'transaction_date',
        'credit_due_date',
        'description'
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
}
