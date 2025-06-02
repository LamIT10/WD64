<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần kiểm tra quyền
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'current_debt' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:6',
            'rank_id' => 'nullable|exists:ranks,id',
        ];
    }
}