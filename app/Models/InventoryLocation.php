<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;

    protected $fillable = ['product_variant_id', 'zone_id', 'custom_location_name'];


    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function zone()
    {
        return $this->belongsTo(WarehouseZone::class, 'zone_id');
    }
}
