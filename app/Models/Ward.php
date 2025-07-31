<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'wards';

    protected $fillable = [
        'ward_code',
        'name',
        'province_code',
        'created_at',
        'updated_at'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }
}
