<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function locations()
    {
        return $this->hasMany(InventoryLocation::class);
    }
}
