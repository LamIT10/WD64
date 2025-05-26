<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnitConversion extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'from_unit_id', 'to_unit_id', 'conversion_factor'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }
}
