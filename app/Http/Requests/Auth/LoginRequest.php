<?php

namespace App\Http\Requests\Auth;

use Anhskohbo\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng gửi request đăng nhập
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // return [
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        //     'g-recaptcha-response' => ['required', new CaptchaRule],
        // ];

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    
        if (!app()->environment('local', 'testing')) {
            $rules['g-recaptcha-response'] = ['required', new CaptchaRule];
        }
    
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh reCAPTCHA.',
            'g-recaptcha-response' => 'Xác minh reCAPTCHA thất bại.',
        ];
    }

}