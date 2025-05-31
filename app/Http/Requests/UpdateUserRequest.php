<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|email|unique:users,email,' . $this->route('user'),
            'phone' => 'nullable|string|max:12',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'nullable|in:active,inactive,suspended',
            'note' => 'nullable|string|max:500',
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
            
        ];
    }
}
