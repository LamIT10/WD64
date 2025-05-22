<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'symbol'];

    public function productUnitConversions()
    {
        return $this->hasMany(ProductUnitConversion::class, 'from_unit_id')
            ->orWhere('to_unit_id', $this->id);
    }
}
