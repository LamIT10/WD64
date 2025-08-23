<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả user gọi request này
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password'      => ['required'],
            'new_password'          => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải ít nhất 8 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu không khớp.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->current_password, Auth::user()->password)) {
                $validator->errors()->add('current_password', 'Mật khẩu hiện tại không đúng.');
            }
        });
    }
}
