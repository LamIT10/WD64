<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string|max:12',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'identity_number' => 'nullable|string|max:20',
            'note' => 'nullable|string|max:500',
            'employee_code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('users', 'employee_code')->whereNotNull('employee_code'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ tên đầy đủ',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'email.unique' => 'Email đã được sử dụng',
            'phone.max' => 'Số điện thoại không được vượt quá 12 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];
    }
}
