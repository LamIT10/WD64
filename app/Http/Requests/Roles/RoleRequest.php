<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            "name" => "required|min:1|max:50|unique:roles,name",
            "permissions" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên vai trò là bắt buộc.',
            'permissions.required' => 'Quyền hạng là bắt buộc.',
            'name.min'      => 'Tên vai trò phải có ít nhất :min ký tự.',
            'name.max'      => 'Tên vai trò không được vượt quá :max ký tự.',
            'name.unique'   => 'Tên vai trò này đã tồn tại.',
        ];
    }
}
