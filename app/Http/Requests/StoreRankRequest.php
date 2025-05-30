<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'min_total_spent' => 'required|integer',
            'discount_percent' => 'required|integer',
            'credit_percent' => 'required|integer',
            'note' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên hạng.',
            'name.max' => 'Tên hạng không được vượt quá 50 ký tự.',
            'min_total_spent.required' => 'Vui lòng nhập tổng chi tiêu tối thiểu.',
            'min_total_spent.integer' => 'Tổng chi tiêu tối thiểu phải là số nguyên.',
            'discount_percent.required' => 'Vui lòng nhập phần trăm giảm giá.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'credit_percent.required' => 'Vui lòng nhập phần trăm công nợ.',
            'credit_percent.integer' => 'Phần trăm công nợ phải là số nguyên.',
        ];
    }
}
