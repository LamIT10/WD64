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
            'employee_code' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'employee_code')->whereNotNull('employee_code'),
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/', 'unique:users,phone'],
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'position' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'identity_number' => [
                'nullable',
                'string',
                'max:12',
                Rule::unique('users', 'identity_number'),
            ],
            'note' => 'nullable|string|max:500',
            'roles' => 'nullable'
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
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'phone.max' => 'Số điện thoại không được vượt quá 12 ký tự',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'employee_code.unique' => 'Mã nhân viên đã tồn tại',
            'avatar.image' => 'Ảnh đại diện phải là một hình ảnh hợp lệ',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif hoặc svg',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB',
            'identity_number.unique' => 'Số CMND/CCCD đã tồn tại',
            'identity_number.max' => 'Số CMND/CCCD không được vượt quá 12 ký tự',
        ];
    }
}
