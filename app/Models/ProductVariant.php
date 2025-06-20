<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'barcode'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attributes', 'variant_id', 'attribute_value_id');
    }
    public function supplierVariants()
    {
        return $this->hasMany(SupplierProductVariant::class);
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocation::class, 'product_variant_id');
    }
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SaleOrderItem::class);
    }

    public function receivingItems()
    {
        return $this->hasMany(ReceivingItem::class);
    }

    public function shippingItems()
    {
        return $this->hasMany(ShippingItem::class);
    }

    public function inventoryAuditItems()
    {
        return $this->hasMany(InventoryAuditItem::class);
    }

    public function damagedExpiredProducts()
    {
        return $this->hasMany(DamagedExpiredProduct::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
