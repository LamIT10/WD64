<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'symbol'];

    public function conversions()
    {
        return $this->hasMany(ProductUnitConversion::class);
    }
    public function conversionsFrom()
    {
        return $this->hasMany(ProductUnitConversion::class, 'from_unit_id');
    }
    public function conversionsTo()
    {
        return $this->hasMany(ProductUnitConversion::class, 'to_unit_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'default_unit_id');
    }
}
