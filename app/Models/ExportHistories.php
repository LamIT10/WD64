<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportHistories extends Model
{
    use HasFactory;

    protected $fillable = ['sale_order_id', 'action_name', 'content', 'created_id', 'created_at', 'updated_at'];


    public function User()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function SaleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }
}
