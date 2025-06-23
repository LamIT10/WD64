<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'min_stock', 'description', 'category_id', 'expiration_date', 'production_date', 'default_unit_id'];


    protected $casts = [
        'expiration_date' => 'date',
        'production_date' => 'date',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function unitConversions()
    {
        return $this->hasMany(ProductUnitConversion::class);
    }

    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocation::class);
    }
    public function supplierVariants()
    {
        return $this->hasManyThrough(SupplierProductVariant::class, ProductVariant::class);
    }
    public function defaultUnit()
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }
}
