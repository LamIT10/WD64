<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDebtHistory extends Model
{
    protected $fillable = ['supplier_transaction_id', 'new_value', 'update_type', 'note','proof_image'];
    protected  $table = 'supplier_debt_history';
    public function supplierTransaction()
    {
        return $this->belongsTo(SupplierTransaction::class);
    }

  
}
