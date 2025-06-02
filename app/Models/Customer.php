<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'password',
        'address',
        'current_debt'
    ];

    protected $hidden = ['password'];

    public function salesOrders()
    {
        return $this->hasMany(SaleOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function ranks()
    {
        return $this->hasMany(Rank::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'entity_id')->where('type', 'customer');
    }
}
