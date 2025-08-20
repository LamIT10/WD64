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
                    'purchase_order_id' => ['required', 'integer', 'exists:purchase_orders,id'],
                    'note' => ['nullable', 'string', 'max:500'],
                    'total_amount' => ['required', 'numeric', 'min:0'],
                    'payment' => ['required', 'numeric', 'min:0'],
                    'receive_type' => ['required', 'in:full,partial'],
                    'items' => ['required', 'array', 'min:1'],
                    'items.*.product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
                    'items.*.unit_id' => ['required', 'integer', 'exists:units,id'],
                    'items.*.unit_default' => ['required', 'integer', 'exists:units,id'],
                    'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                    'items.*.quantity_ordered' => ['required', 'numeric', 'min:0'],
                    'items.*.quantity_received' => ['required', 'numeric', 'gt:0'],
                    'items.*.unit_price' => ['required', 'numeric', 'min:0'],
                    'items.*.subtotal' => ['required', 'numeric', 'min:0'],
                ];
            case 'PUT':
            case 'PATCH':
            default:
                return [];
        }
    }
    public function messages(): array
    {
        return [
            'purchase_order_id.required' => 'Mã đơn nhập không được để trống.',
            'purchase_order_id.exists' => 'Đơn nhập không tồn tại.',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự.',
            'total_amount.required' => 'Tổng tiền không được để trống.',
            'total_amount.numeric' => 'Tổng tiền phải là số.',
            'total_amount.min' => 'Tổng tiền phải lớn hơn hoặc bằng 0.',
            'payment.required' => 'Số tiền thanh toán không được để trống.',
            'payment.numeric' => 'Số tiền thanh toán phải là số.',
            'payment.min' => 'Số tiền thanh toán phải lớn hơn hoặc bằng 0.',
            'receive_type.required' => 'Loại nhận hàng không được để trống.',
            'receive_type.in' => 'Loại nhận hàng không hợp lệ.',
            'items.required' => 'Danh sách sản phẩm không được để trống.',
            'items.array' => 'Danh sách sản phẩm không hợp lệ.',
            'items.min' => 'Phải có ít nhất 1 sản phẩm.',
            'items.*.product_variant_id.required' => 'Mã sản phẩm không được để trống.',
            'items.*.product_variant_id.exists' => 'Sản phẩm không tồn tại.',
            'items.*.unit_id.required' => 'Đơn vị tính không được để trống.',
            'items.*.unit_id.exists' => 'Đơn vị tính không tồn tại.',
            'items.*.quantity_received.required' => 'Số lượng nhận không được để trống.',
            'items.*.quantity_received.numeric' => 'Số lượng nhận phải là số.',
            'items.*.quantity_received.min' => 'Số lượng nhận phải lớn hơn 0.',
            'items.*.unit_price.required' => 'Đơn giá không được để trống.',
            'items.*.unit_price.numeric' => 'Đơn giá phải là số.',
            'items.*.unit_price.min' => 'Đơn giá phải lớn hơn hoặc bằng 0.',
        ];
    }
}
