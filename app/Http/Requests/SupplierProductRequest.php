<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierProductRequest extends FormRequest
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
            'variant_ids' => 'required|array|min:1',
            'variant_ids.*' => 'exists:product_variants,id',
            'variant_details' => 'required|array',
            'variant_details.*.id' => 'exists:product_variants,id',
            'variant_details.*.cost_price' => 'required|numeric|min:0',
            'variant_details.*.sale_price' => 'required|numeric|min:0',
            'variant_details.*.min_order_quantity' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'variant_ids.required' => 'Vui lòng chọn ít nhất một biến thể.',
            'variant_ids.*.exists' => 'Biến thể không hợp lệ.',
            'variant_details.required' => 'Vui lòng cung cấp thông tin chi tiết biến thể.',
            'variant_details.*.id.exists' => 'ID biến thể không hợp lệ.',
            'variant_details.*.cost_price.required' => 'Vui lòng nhập giá mua cho biến thể.',
            'variant_details.*.cost_price.numeric' => 'Giá mua phải là số.',
            'variant_details.*.cost_price.min' => 'Giá mua không được nhỏ hơn 0.',
            'variant_details.*.sale_price.required' => 'Vui lòng nhập giá bán cho biến thể.',
            'variant_details.*.sale_price.numeric' => 'Giá bán phải là số.',
            'variant_details.*.sale_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'variant_details.*.min_order_quantity.required' => 'Vui lòng nhập số lượng tối thiểu cho biến thể.',
            'variant_details.*.min_order_quantity.numeric' => 'Số lượng tối thiểu phải là số.',
            'variant_details.*.min_order_quantity.min' => 'Số lượng tối thiểu không được nhỏ hơn 0.',
        ];
    }
}