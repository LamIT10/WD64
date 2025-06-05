<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh theo logic phân quyền
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:customers,email,' . $this->customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'total_spent' => 'nullable|numeric|min:0',
            'current_debt' => 'nullable|numeric|min:0',
            'rank_id' => 'nullable|exists:ranks,id',
            'status' => 'nullable|in:active,inactive,blocked',
        ];
    }
}