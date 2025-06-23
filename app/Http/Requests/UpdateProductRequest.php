<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $isVariant = $this->has('variants') && is_array($this->input('variants')) && count($this->input('variants')) > 0;
        $productId = $this->route('product');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'code')->ignore($productId),
            ],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'expiration_date' => ['nullable', 'date', 'after_or_equal:production_date'],
            'production_date' => ['nullable', 'date'],
            'base_unit_id' => ['required', 'exists:units,id'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*.id' => ['nullable', 'exists:images,id'], // Ảnh cũ
            'images.*.url' => ['nullable', 'string'], // URL của ảnh cũ
            'images.*.name' => ['nullable', 'string'], // Tên file của ảnh mới
            'images.*.data' => ['nullable', 'string', 'regex:/^data:image\/(jpg|jpeg|png);base64,/'], // Base64
            'deleted_image_ids' => ['nullable', 'array'],
            'deleted_image_ids.*' => ['exists:images,id'],
            'unit_conversions' => ['nullable', 'array', 'max:3'],
            'unit_conversions.*.to_unit_id' => ['required_with:unit_conversions', 'exists:units,id', 'different:base_unit_id'],
            'unit_conversions.*.conversion_factor' => ['required_with:unit_conversions', 'numeric', 'min:0.0001'],
        ];

        if ($isVariant) {
            // Validate biến thể
            $rules['variants'] = ['required', 'array'];
            $rules['variants.*.attributes'] = ['required', 'array'];
            $rules['variants.*.attributes.*.attribute_id'] = ['required', 'exists:attributes,id'];
            $rules['variants.*.attributes.*.attribute_value_ids'] = ['required', 'array'];
            $rules['variants.*.attributes.*.attribute_value_ids.*'] = ['required', 'exists:attribute_values,id'];

            $rules['variants.*.combinations'] = ['required', 'array'];
            $rules['variants.*.combinations.*.attribute_value_ids'] = ['required', 'array'];
            $rules['variants.*.combinations.*.attribute_value_ids.*'] = ['required', 'exists:attribute_values,id'];
            $rules['variants.*.combinations.*.code'] = ['required', 'string', 'max:100'];
            $rules['variants.*.combinations.*.barcode'] = ['nullable', 'string', 'max:100'];
            $rules['variants.*.combinations.*.sale_price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.combinations.*.quantity_on_hand'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.combinations.*.supplier_ids'] = ['required', 'array']; // Có thể đổi thành 'nullable' nếu không bắt buộc
            $rules['variants.*.combinations.*.supplier_ids.*'] = ['exists:suppliers,id'];
            $rules['variants.*.combinations.*.warehouse_zone_id'] = ['nullable', 'exists:warehouse_zones,id'];
            $rules['variants.*.combinations.*.custom_location_name'] = ['nullable', 'string', 'max:100'];
        } else {
            // Validate sản phẩm đơn giản
            $rules['simple_sale_price'] = ['required', 'numeric', 'min:0'];
            $rules['simple_quantity'] = ['required', 'numeric', 'min:0'];
            $rules['simple_barcode'] = ['nullable', 'string', 'max:100'];
            $rules['supplier_ids'] = ['required', 'array'];
            $rules['supplier_ids.*'] = ['exists:suppliers,id'];
            $rules['warehouse_zone_id'] = ['nullable', 'exists:warehouse_zones,id'];
            $rules['custom_location_name'] = ['nullable', 'string', 'max:100'];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Tên sản phẩm
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            // Mã sản phẩm
            'code.required' => 'Vui lòng nhập mã sản phẩm.',
            'code.max' => 'Mã sản phẩm không được vượt quá 100 ký tự.',
            'code.unique' => 'Mã sản phẩm đã tồn tại.',

            // Tồn kho tối thiểu
            'min_stock.required' => 'Vui lòng nhập mức tồn kho tối thiểu.',
            'min_stock.integer' => 'Tồn kho tối thiểu phải là số nguyên.',
            'min_stock.min' => 'Tồn kho tối thiểu không được âm.',

            // Danh mục
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',

            // Đơn vị cơ bản
            'base_unit_id.required' => 'Vui lòng chọn đơn vị cơ bản.',
            'base_unit_id.exists' => 'Đơn vị cơ bản không hợp lệ.',

            // Ngày sản xuất & hết hạn
            'production_date.date' => 'Ngày sản xuất không hợp lệ.',
            'expiration_date.date' => 'Ngày hết hạn không hợp lệ.',
            'expiration_date.after_or_equal' => 'Ngày hết hạn phải bằng hoặc sau ngày sản xuất.',

            // Ảnh sản phẩm
            'images.array' => 'Danh sách ảnh không hợp lệ.',
            'images.max' => 'Chỉ được phép tải lên tối đa 4 ảnh.',
            'images.*.id.exists' => 'Ảnh cũ không hợp lệ.',
            'images.*.data.regex' => 'Ảnh mới phải là chuỗi Base64 hợp lệ (jpg, jpeg, png).',
            'deleted_image_ids.array' => 'Danh sách ID ảnh xóa không hợp lệ.',
            'deleted_image_ids.*.exists' => 'ID ảnh xóa không hợp lệ.',

            // Đơn vị quy đổi
            'unit_conversions.array' => 'Danh sách đơn vị quy đổi không hợp lệ.',
            'unit_conversions.max' => 'Không được khai báo quá 3 đơn vị quy đổi.',
            'unit_conversions.*.to_unit_id.required_with' => 'Vui lòng chọn đơn vị quy đổi.',
            'unit_conversions.*.to_unit_id.exists' => 'Đơn vị quy đổi không hợp lệ.',
            'unit_conversions.*.to_unit_id.different' => 'Đơn vị quy đổi phải khác đơn vị cơ bản.',
            'unit_conversions.*.conversion_factor.required_with' => 'Vui lòng nhập hệ số quy đổi.',
            'unit_conversions.*.conversion_factor.numeric' => 'Hệ số quy đổi phải là số.',
            'unit_conversions.*.conversion_factor.min' => 'Hệ số quy đổi phải lớn hơn 0.',

            // Sản phẩm đơn giản
            'simple_sale_price.required' => 'Vui lòng nhập giá bán.',
            'simple_sale_price.numeric' => 'Giá bán phải là số.',
            'simple_sale_price.min' => 'Giá bán không được âm.',

            'simple_quantity.required' => 'Vui lòng nhập tồn kho.',
            'simple_quantity.numeric' => 'Tồn kho phải là số.',
            'simple_quantity.min' => 'Tồn kho không được âm.',

            'simple_barcode.string' => 'Mã vạch phải là chuỗi.',
            'simple_barcode.max' => 'Mã vạch không được vượt quá 100 ký tự.',

            'supplier_ids.required' => 'Hãy chọn nhà cung cấp.',
            'supplier_ids.array' => 'Danh sách nhà cung cấp không hợp lệ.',
            'supplier_ids.*.exists' => 'Nhà cung cấp không hợp lệ.',
            'warehouse_zone_id.exists' => 'Khu vực kho không hợp lệ.',
            'custom_location_name.string' => 'Vị trí lưu kho phải là chuỗi.',
            'custom_location_name.max' => 'Vị trí lưu kho không được vượt quá 100 ký tự.',

            // Biến thể sản phẩm
            'variants.required' => 'Vui lòng khai báo biến thể sản phẩm.',
            'variants.array' => 'Danh sách biến thể không hợp lệ.',

            'variants.*.attributes.required' => 'Vui lòng nhập thuộc tính cho biến thể.',
            'variants.*.attributes.array' => 'Danh sách thuộc tính của biến thể không hợp lệ.',
            'variants.*.attributes.*.attribute_id.required' => 'Vui lòng chọn thuộc tính.',
            'variants.*.attributes.*.attribute_id.exists' => 'Thuộc tính không tồn tại.',
            'variants.*.attributes.*.attribute_value_ids.required' => 'Vui lòng chọn giá trị thuộc tính.',
            'variants.*.attributes.*.attribute_value_ids.array' => 'Danh sách giá trị thuộc tính không hợp lệ.',
            'variants.*.attributes.*.attribute_value_ids.*.exists' => 'Một số giá trị thuộc tính không hợp lệ.',

            // Combinations
            'variants.*.combinations.required' => 'Vui lòng khai báo tổ hợp biến thể.',
            'variants.*.combinations.array' => 'Danh sách tổ hợp không hợp lệ.',
            'variants.*.combinations.*.attribute_value_ids.required' => 'Thiếu danh sách giá trị tổ hợp.',
            'variants.*.combinations.*.attribute_value_ids.array' => 'Danh sách giá trị tổ hợp không hợp lệ.',
            'variants.*.combinations.*.attribute_value_ids.*.exists' => 'Một số giá trị trong tổ hợp không hợp lệ.',

            'variants.*.combinations.*.code.required' => 'Vui lòng nhập mã SKU cho tổ hợp.',
            'variants.*.combinations.*.code.string' => 'Mã SKU phải là chuỗi.',
            'variants.*.combinations.*.code.max' => 'Mã SKU không được vượt quá 100 ký tự.',

            'variants.*.combinations.*.barcode.string' => 'Mã vạch phải là chuỗi.',
            'variants.*.combinations.*.barcode.max' => 'Mã vạch không được vượt quá 100 ký tự.',

            'variants.*.combinations.*.sale_price.required' => 'Vui lòng nhập giá bán cho tổ hợp.',
            'variants.*.combinations.*.sale_price.numeric' => 'Giá bán phải là số.',
            'variants.*.combinations.*.sale_price.min' => 'Giá bán không được âm.',

            'variants.*.combinations.*.quantity_on_hand.required' => 'Vui lòng nhập tồn kho cho tổ hợp.',
            'variants.*.combinations.*.quantity_on_hand.numeric' => 'Tồn kho phải là số.',
            'variants.*.combinations.*.quantity_on_hand.min' => 'Tồn kho không được âm.',

            'variants.*.combinations.*.supplier_ids.required' => 'Vui lòng chọn ít nhất một nhà cung cấp cho tổ hợp.',
            'variants.*.combinations.*.supplier_ids.array' => 'Danh sách nhà cung cấp không hợp lệ.',
            'variants.*.combinations.*.supplier_ids.*.exists' => 'Nhà cung cấp cho tổ hợp không hợp lệ.',
            'variants.*.combinations.*.warehouse_zone_id.exists' => 'Khu vực kho cho tổ hợp không hợp lệ.',
            'variants.*.combinations.*.custom_location_name.string' => 'Tên vị trí lưu kho cho tổ hợp phải là chuỗi.',
            'variants.*.combinations.*.custom_location_name.max' => 'Tên vị trí lưu kho cho tổ hợp không được vượt quá 100 ký tự.',
        ];
    }
}