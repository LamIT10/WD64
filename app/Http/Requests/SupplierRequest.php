<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name' => 'required|min:1|max:50',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:12',
            'email' => 'nullable|email|unique:suppliers',
            'contact_person' => 'required|string|max:50',
            'tax_code' => 'nullable|string|max:50',
        ];
    }
     public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'name.max' => 'Tên nhà cung cấp không được vượt quá 50 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 12 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'contact_person.max' => 'Tên người liên hệ không được vượt quá 50 ký tự.',
            'contact_person.required' => 'Vui lòng nhập tên người liên hệ.',
            'tax_code.max' => 'Mã số thuế không được vượt quá 50 ký tự.',
        ];
    }
}
