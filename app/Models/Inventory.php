<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = ['product_variant_id', 'supplier_id', 'warehouse_zone_id', 'quantity_on_hand', 'quantity_reserved', 'quantity_in_transit', 'unit_id', 'status'];


    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouseZone()
    {
        return $this->belongsTo(WarehouseZone::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
