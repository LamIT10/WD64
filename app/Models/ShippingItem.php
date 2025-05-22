<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_id',
        'product_variant_id',
        'unit_id',
        'quantity_shipped',
        'notes'
    ];

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
