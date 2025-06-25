<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address', 'current_debt'];


    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'entity_id')->where('type', 'supplier');
    }
    public function supplierVariants()
    {
        return $this->hasMany(SupplierProductVariant::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class,'supplier_product_variants','supplier_id', 'product_variant_id');
    }
}
