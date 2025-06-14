<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
          return [
        'transaction_id' => 'required|exists:customer_transactions,id',
        'amount' => 'required|numeric|min:0.01',
    ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'Vui lòng chọn giao dịch công nợ.',
            'transaction_id.exists' => 'Giao dịch công nợ không tồn tại.',
            'amount.required' => 'Số tiền thanh toán là bắt buộc.',
            'amount.numeric' => 'Số tiền thanh toán phải là một số.',
            'amount.min' => 'Số tiền thanh toán phải lớn hơn 0.',
        ];
    }
}
