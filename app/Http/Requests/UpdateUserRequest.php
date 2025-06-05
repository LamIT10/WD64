<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:12',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'identity_number' => 'nullable|string|max:20',
            'note' => 'nullable|string|max:500',
            'role' => "nullable",
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'employee_code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('users', 'employee_code')->ignore($this->route('user')) ->whereNotNull('employee_code'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'phone.max' => 'Số điện thoại không được vượt quá 12 ký tự',
            'employee_code.unique' => 'Mã nhân viên đã tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
        ];
    }
}
