<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'address_detail' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.useCustomUnit' => 'required|boolean',
            'items.*.selectedUnitId' => 'nullable|exists:units,id',
            'items.*.defaultUnitId' => 'required|exists:units,id',
            'items.*.conversionFactor' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Vui lòng chọn khách hàng.',
            'total_amount.required' => 'Tổng tiền không được để trống.',
            'items.required' => 'Giỏ hàng không được để trống.',
            'items.min' => 'Phải có ít nhất một sản phẩm trong giỏ hàng.',
        ];
    }
}