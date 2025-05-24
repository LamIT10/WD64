<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'zone_id', 'custom_location_name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function zone()
    {
        return $this->belongsTo(WarehouseZone::class, 'zone_id');
    }

}
