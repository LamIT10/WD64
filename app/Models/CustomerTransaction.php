<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'paid_amount',
        'transaction_date',
        'credit_due_date',
        'description',
        'type',
        
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'credit_due_date' => 'date',
    ];

 

    public function salesOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    
}
