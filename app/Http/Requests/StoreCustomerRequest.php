<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'current_debt' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:6',
            'rank_id' => 'nullable|exists:ranks,id',
        ];
    }
}