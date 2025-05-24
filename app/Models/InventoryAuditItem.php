<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAuditItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id', 'product_variant_id', 'expected_quantity', 
        'actual_quantity', 'discrepancy_reason'
    ];

    public function audit()
    {
        return $this->belongsTo(InventoryAudit::class, 'audit_id');
    }
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
