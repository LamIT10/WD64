<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost_type', 'amount', 'cost_date', 'description'
    ];

    protected $casts = [
        'cost_date' => 'date',
    ];
}
