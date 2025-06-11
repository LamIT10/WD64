<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 'phone', 'email', 'password', 'address', 'current_debt', 'rank_id',
    'avatar', 'total_spent', 'max_debt_limit', 'status'
    ];

    protected $casts = [
    'current_debt' => 'decimal:2',
    'total_spent' => 'decimal:2',
    'max_debt_limit' => 'decimal:2',
    ];

    protected $hidden = ['password'];

    public function salesOrders()
    {
        return $this->hasMany(SaleOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'entity_id')->where('type', 'customer');
    }
}
