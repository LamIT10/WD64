<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'min_total_spent' => 'required|integer',
            'discount_percent' => 'required|integer',
            'credit_percent' => 'required|integer',
            'note' => 'nullable|string',
        ];
    }
}
