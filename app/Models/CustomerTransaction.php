<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sales_order_id',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }
}
