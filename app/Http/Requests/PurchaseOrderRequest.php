<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
        return [
            'orders' => ['required', 'array'],
            'orders.*.supplier_id' => ['required', 'integer'],
            'orders.*.items' => ['required', 'array'],
            'orders.*.items.*.variant_id' => ['required', 'integer'],
            'orders.*.items.*.quantity' => ['required', 'numeric'],
            'orders.*.items.*.unit_id' => ['required', 'integer'],
            'orders.*.items.*.price' => ['required', 'numeric'],
            'orders.*.expected_date' => ['required', 'date'],
        ];
    }
    public function messages(): array
    {
        return [
            'orders.*.expected_date.required' => 'Ngày dự kiến không được để trống.',
            'orders.*.expected_date.date' => 'Ngày dự kiến không đúng định dạng.',
        ];
    }
}
