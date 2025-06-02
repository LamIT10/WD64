<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRankRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần kiểm tra quyền
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('ranks')->ignore($this->rank?->id)],
            'min_total_spent' => 'required|numeric|min:0',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'credit_percent' => 'required|numeric|min:0|max:100',
            'note' => 'nullable|string|max:1000',
        ];
    }
}