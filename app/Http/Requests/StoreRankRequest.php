<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',

            'min_total_spent' => 'required|integer|min:0',

            'discount_percent' => 'required|integer|min:0|max:100',

            'credit_percent' => 'required|integer|min:0|max:100',

            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên hạng.',
            'name.max' => 'Tên hạng không được vượt quá 50 ký tự.',

            'min_total_spent.required' => 'Vui lòng nhập tổng chi tiêu tối thiểu.',
            'min_total_spent.integer' => 'Tổng chi tiêu tối thiểu phải là số nguyên.',
            'min_total_spent.min' => 'Tổng chi tiêu tối thiểu phải lớn hơn hoặc bằng 0.',

            'discount_percent.required' => 'Vui lòng nhập phần trăm giảm giá.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'discount_percent.min' => 'Phần trăm giảm giá không được âm.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100.',

            'credit_percent.required' => 'Vui lòng nhập phần trăm công nợ.',
            'credit_percent.integer' => 'Phần trăm công nợ phải là số nguyên.',
            'credit_percent.min' => 'Phần trăm công nợ không được âm.',
            'credit_percent.max' => 'Phần trăm công nợ không được vượt quá 100.',

            'note.max' => 'Ghi chú không được vượt quá 255 ký tự.',
        ];
    }

}
