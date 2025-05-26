<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'amount', 'transaction_date', 'description', 'related_type'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];
}
