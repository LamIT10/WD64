<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'password' => 'nullable|string|max:50|confirmed',
            'address' => 'nullable|string',
            'current_debt' => 'nullable|numeric',
        ];
    }
}
