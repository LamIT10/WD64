<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            "name" => "required|min:1|max:50|unique:permissions,name",
            "description" => "max:255"
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên quyền hạng là bắt buộc.',
            'name.min'      => 'Tên quyền hạng phải có ít nhất :min ký tự.',
            'name.max'      => 'Tên quyền hạng không được vượt quá :max ký tự.',
            'name.unique'   => 'Tên quyền hạng này đã tồn tại.',
            'description.max'      => 'Mô tả được vượt quá :max ký tự.',
        ];
    }
}
