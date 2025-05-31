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
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required|min:6'],
        ];

        // Chỉ kiểm tra reCAPTCHA nếu không phải môi trường test
        if (env('APP_ENV') !== 'testing') {
            $rules['g-recaptcha-response'] = ['required'];
        }

        return $rules;
    }

    /**
     * Xác minh reCAPTCHA sau khi validation.
     */
    protected function passedValidation()
    {
        // Bỏ qua kiểm tra reCAPTCHA nếu đang ở môi trường test
        if (env('APP_ENV') === 'testing') {
            return;
        }
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
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh reCAPTCHA.',
        ];
    }
}