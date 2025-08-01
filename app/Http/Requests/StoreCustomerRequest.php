<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh theo logic phân quyền
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => [
                'required',
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/^\d{10,11}$/', $value)) {
                        $fail('Số điện thoại phải có 10-11 chữ số.');
                    }
                },
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('customers', 'email'),
            ],
            // 'password' => [
            //     'required',
            //     'string',
            //     'min:8',
            //     'confirmed',
            // ],
            'address' => 'nullable|string',
            'avatar' => [
                'nullable',
                'image',
                'max:2048', // 2MB
                function ($attribute, $value, $fail) {
                    if ($value && !in_array($value->getClientMimeType(), ['image/jpeg', 'image/png'])) {
                        $fail('Chỉ hỗ trợ định dạng JPEG hoặc PNG.');
                    }
                },
            ],
            'total_spent' => 'nullable|numeric|min:0',
            'current_debt' => 'nullable|numeric|min:0',
            'rank_id' => 'nullable|exists:ranks,id',
            'status' => 'nullable|in:active,inactive,debt_exceeded,blocked',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'phone.required' => 'Số điện thoại khách hàng là bắt buộc.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 100 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            // 'password.required' => 'Mật khẩu là bắt buộc.',
            // 'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            // 'password.confirmed' => 'Mật khẩu và xác nhận không khớp.',
            'avatar.image' => 'Tệp phải là hình ảnh.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'total_spent.min' => 'Tổng chi tiêu không được âm.',
            'current_debt.min' => 'Công nợ không được âm.',
            'rank_id.exists' => 'Hạng khách hàng không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}