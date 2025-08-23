<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả user gọi request này
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name'            => 'required|string|max:255',
            'birthday'        => 'nullable|date',
            'gender'          => 'nullable|in:male,female',
            'identity_number' => 'nullable|string|max:12|unique:users,identity_number,'.$userId,
            'address'         => 'nullable|string|max:255',
            'facebook'        => 'nullable|string|max:255',
            'phone'           => 'nullable|string|regex:/^0[0-9]{9}$/|unique:users,phone,'.$userId,
            'email'           => 'required|email|max:255|unique:users,email,'.$userId,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Vui lòng nhập họ tên.',
            'name.max'                 => 'Họ tên không được vượt quá 255 ký tự.',
            'birthday.date'            => 'Ngày sinh không hợp lệ.',
            'gender.in'                => 'Giới tính chỉ được chọn Nam hoặc Nữ.',
            'identity_number.max'      => 'CMND/CCCD tối đa 12 ký tự.',
            'identity_number.unique'   => 'CMND/CCCD này đã được sử dụng.',
            'address.max'              => 'Địa chỉ không được vượt quá 255 ký tự.',
            'facebook.max'             => 'Link Facebook không được vượt quá 255 ký tự.',
            'phone.regex'              => 'Số điện thoại phải bắt đầu bằng 0 và gồm 10 số.',
            'phone.unique'             => 'Số điện thoại này đã được sử dụng.',
            'email.required'           => 'Vui lòng nhập email.',
            'email.email'              => 'Email không hợp lệ.',
            'email.max'                => 'Email không được vượt quá 255 ký tự.',
            'email.unique'             => 'Email này đã được sử dụng.',
        ];
    }
}
