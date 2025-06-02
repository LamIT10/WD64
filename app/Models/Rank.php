<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'name', 'min_total_spent', 
        'discount_percent', 'credit_percent', 'note'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
