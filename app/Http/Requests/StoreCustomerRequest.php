<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|max:50|confirmed',
            'address' => 'nullable|string',
            'current_debt' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng.',
            'name.max' => 'Tên khách hàng không được vượt quá 100 ký tự.',
            'contact_person.max' => 'Người liên hệ không được vượt quá 50 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 100 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.max' => 'Mật khẩu không được vượt quá 50 ký tự.',
            'current_debt.numeric' => 'Công nợ phải là số.',
        ];
    }
}
