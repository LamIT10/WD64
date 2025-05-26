<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'audit_date', 'status', 'notes'
    ];

    protected $casts = [
        'audit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryAuditItem::class, 'audit_id');
    }
}
