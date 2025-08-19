<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLog extends Model
{
    protected $table = 'purchase_order_logs';
    protected $fillable = [
        'purchase_order_id',
        'created_by',
        'refused_by',
        'updated_by',
        'approved_by',
        'end_by',
        'create_grn_by',
        'type',
        'detail'
    ];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function refusedBy()
    {
        return $this->belongsTo(User::class, 'refused_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function endBy()
    {
        return $this->belongsTo(User::class, 'end_by');
    }
    public function createGrnBy()
    {
        return $this->belongsTo(User::class, 'create_grn_by');
    }
}
