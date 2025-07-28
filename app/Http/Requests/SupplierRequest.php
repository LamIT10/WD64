<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('id');

        switch ($this->method()) {
            case 'PATCH':
            case 'PUT':
                return [
                    'name' => 'required|min:1|max:50',
                    'address' => 'nullable|string|max:255',
                    'phone' => ['required', 'regex:/^(0|\+84)[0-9]{9}$/'],
                    'email' => 'nullable|email|unique:suppliers,email,' . $supplierId,
                    'contact_person' => 'required|string|max:50',
                    'tax_code' => 'nullable|string|max:50',
                    'type' => 'nullable|string|max:50',
                    'kind' => 'nullable|string|max:50',
                ];

            case 'POST':
            default:
                return [
                    'name' => 'required|min:1|max:50',
                    'address' => 'nullable|string|max:255',
                    'phone' => ['required', 'regex:/^(0|\+84)[0-9]{9}$/'],
                    'email' => 'nullable|email|unique:suppliers,email',
                    'contact_person' => 'required|string|max:50',
                    'tax_code' => 'nullable|string|max:50',
                    'type' => 'nullable|string|max:50',
                    'kind' => 'nullable|string|max:50',
                ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'name.max' => 'Tên nhà cung cấp không được vượt quá 50 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'contact_person.max' => 'Tên người liên hệ không được vượt quá 50 ký tự.',
            'contact_person.required' => 'Vui lòng nhập tên người liên hệ.',
            'tax_code.max' => 'Mã số thuế không được vượt quá 50 ký tự.',
            'type.max' => 'Loại NCC không được vượt quá 50 ký tự.',
            'kind.max' => 'Kiểu NCC không được vượt quá 50 ký tự.',
        ];
    }
}
