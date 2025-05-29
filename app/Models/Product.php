<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'name', 'min_stock', 'supplier_id', 'description', 
        'category_id', 'expiration_date', 'production_date'
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'production_date' => 'date',
    ];

    public function images() {
        return $this->morphMany(Image::class,'imageable');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
}
