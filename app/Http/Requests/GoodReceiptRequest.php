<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'orders' => ['required', 'array'],
                    'orders.*.supplier_id' => ['required', 'integer'],
                    'orders.*.items' => ['required', 'array'],
                    'orders.*.items.*.variant_id' => ['required', 'integer', 'exists:product_variants,id'],
                    'orders.*.items.*.quantity' => ['required', 'numeric'],
                    'orders.*.items.*.unit_id' => ['required', 'integer', 'exists:units,id'],
                    'orders.*.items.*.price' => ['required', 'numeric'],
                    'orders.*.expected_date' => [
                        'required',
                        'date',
                        'after_or_equal:' . now()->format('Y-m-d')
                    ],
                ];
            case 'PUT':
                return [];
            default:
                return [];
        }
    }
    public function messages(): array
    {
        return [
            'orders.*.expected_date.required' => 'Ngày dự kiến không được để trống.',
            'orders.*.expected_date.date' => 'Ngày dự kiến không đúng định dạng.',
            'orders.*.expected_date.after_or_equal' => 'Ngày dự kiến không được ở quá khứ.',
        ];
    }
}
