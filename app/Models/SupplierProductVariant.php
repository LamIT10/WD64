<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProductVariant extends Model
{
    protected $fillable = ['supplier_id', 'product_variant_id', 'cost_price', 'sale_price', 'min_order_quantity'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
