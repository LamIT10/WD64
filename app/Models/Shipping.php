<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'sale_order_id', 'user_id', 'ship_date', 
        'carrier', 'tracking_number', 'status'
    ];

    protected $casts = [
        'ship_date' => 'date',
    ];

    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ShippingItem::class);
    }
}
