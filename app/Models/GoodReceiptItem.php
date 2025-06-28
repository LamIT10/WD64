<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodReceiptItem extends Model
{
    protected $table = 'good_receipt_items';
    protected $fillable = [
        'good_receipt_id',
        'product_variant_id',
        'quantity_received',
        'unit_price',
        'unit_id',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    public function goodReceipt()
    {
        return $this->belongsTo(GoodReceipt::class, 'good_receipt_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
        public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
