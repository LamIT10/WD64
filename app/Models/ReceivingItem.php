<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_id',
        'product_variant_id',
        'unit_id',
        'quantity_received',
        'condition',
        'quality_inspection_result'
    ];

    public function receiving()
    {
        return $this->belongsTo(Receiving::class);
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
