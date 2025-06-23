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
}
