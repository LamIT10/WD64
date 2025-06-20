<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'product_variant_id',
        'quantity_ordered',
        'unit_id',
        'unit_price',
        'subtotal',
        'quantity_shipped'
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

   
}
