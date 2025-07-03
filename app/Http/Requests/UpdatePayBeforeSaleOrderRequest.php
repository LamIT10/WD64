<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayBeforeSaleOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pay_before' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'pay_before.required' => 'Vui lòng điền số tiền trả trước.',
            'pay_before.numeric' => 'Số tiền trả trước phải là một số hợp lệ.',
            'pay_before.min' => 'Số tiền trả trước không được nhỏ hơn 0.',
        ];
    }
}