<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;

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
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required'], // Chỉ kiểm tra required
        ];
    }

    /**
     * Xác minh reCAPTCHA sau khi validation.
     */
    protected function passedValidation()
    {
        if (!NoCaptcha::verifyResponse($this->input('g-recaptcha-response'))) {
            $this->failedRecaptcha();
        }
    }

    /**
     * Xử lý khi reCAPTCHA không hợp lệ.
     */
    protected function failedRecaptcha()
    {
        $this->validator->errors()->add('g-recaptcha-response', 'Xác minh reCAPTCHA thất bại.');
        throw new \Illuminate\Validation\ValidationException($this->validator);
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
        ];
    }
}