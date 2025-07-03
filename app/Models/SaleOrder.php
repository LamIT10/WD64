<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'expected_ship_date',
        'status',
        'total_amount',
        'address_delivery',
        'credit_due_date',
        'pay_before',
        'pay_after',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_ship_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleOrderDetails()
    {
        return $this->hasMany(SaleOrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(SaleOrderItem::class, 'sales_order_id');
    }
    public function customerTransactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }
    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class, 'sales_order_id');
    }
    public function latestTransaction()
    {
        return $this->hasOne(CustomerTransaction::class, 'sales_order_id')->latestOfMany();
    }

    public function shipping()
    {
        return $this->hasMany(Shipping::class);
    }

    public function receiving()
    {
        return $this->hasMany(Receiving::class);
    }
}