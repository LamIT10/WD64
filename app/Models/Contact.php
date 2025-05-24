<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'entity_id',
        'name',
        'position',
        'phone',
        'email',
        'notes'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'entity_id')
            ->where('type', 'supplier');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'entity_id')
            ->where('type', 'customer');
    }
}
