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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9}$/',
                Rule::unique('users', 'phone')->ignore($this->route('user')),
            ],
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'identity_number' => [
                'nullable',
                'string',
                'max:12',
                Rule::unique('users', 'identity_number')->ignore($this->route('user')),
            ],
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
                Rule::unique('users', 'employee_code')->ignore($this->route('user'))->whereNotNull('employee_code'),
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
            'employee_code.unique' => 'Mã nhân viên đã tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'avatar.image' => 'Ảnh đại diện phải là một hình ảnh hợp lệ',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif hoặc svg',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'identity_number.unique' => 'Số CMND/CCCD đã tồn tại',
            'identity_number.max' => 'Số CMND/CCCD không được vượt quá 12 ký tự',
        ];
    }
}
