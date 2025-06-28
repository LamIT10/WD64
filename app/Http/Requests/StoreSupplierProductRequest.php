<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:product_variants,id'],
            'cost_price' => ['required', 'integer', 'min:1'],
            'min_order_quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Vui lòng nhập mã biến thể sản phẩm.',
            'id.integer' => 'Mã biến thể sản phẩm phải là số nguyên.',
            'id.exists' => 'Biến thể sản phẩm không tồn tại.',

            'cost_price.required' => 'Vui lòng nhập giá vốn.',
            'cost_price.integer' => 'Giá vốn phải là số nguyên.',
            'cost_price.min' => 'Giá vốn phải lớn hơn 0.',

            'min_order_quantity.required' => 'Vui lòng nhập số lượng đặt tối thiểu.',
            'min_order_quantity.integer' => 'Số lượng đặt tối thiểu phải là số nguyên.',
            'min_order_quantity.min' => 'Số lượng đặt tối thiểu phải lớn hơn 0.',
        ];
    }
}
