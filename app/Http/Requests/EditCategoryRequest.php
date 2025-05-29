<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category'); // Lấy ID từ route

        return [
            'name' => "required|string|max:100|unique:categories,name,{$categoryId}",
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Vui lòng nhập tên danh mục.',
            'name.string'        => 'Tên danh mục phải là kiểu chuỗi.',
            'name.max'           => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique'        => 'Tên danh mục này đã tồn tại. Vui lòng chọn tên khác.',

            'parent_id.exists'   => 'Danh mục cha được chọn không hợp lệ.',

            'description.string' => 'Mô tả danh mục phải là kiểu chuỗi.',
        ];
    }
}
