<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    // Hằng số cho các ràng buộc xác thực
    private const NAME_MAX_LENGTH = 255;
    private const PHONE_MAX_LENGTH = 20;
    private const EMAIL_MAX_LENGTH = 255;
    private const ADDRESS_MAX_LENGTH = 255;
    private const PASSWORD_MIN_LENGTH = 6;

    /**
     * Xác định xem người dùng có được phép thực hiện yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Cập nhật logic quyền nếu cần
    }

    /**
     * Các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:' . self::NAME_MAX_LENGTH],
            'phone' => ['nullable', 'string', 'max:' . self::PHONE_MAX_LENGTH, 'regex:/^[+]?[0-9]{8,15}$/'],
            'email' => [
                'nullable',
                'email',
                'max:' . self::EMAIL_MAX_LENGTH,
                Rule::unique('customers', 'email')->ignore($this->customer?->id),
            ],
            'address' => ['nullable', 'string', 'max:' . self::ADDRESS_MAX_LENGTH],
            'current_debt' => ['nullable', 'numeric', 'min:0'],
            'password' => ['nullable', 'string', 'min:' . self::PASSWORD_MIN_LENGTH],
            'rank_id' => ['nullable', Rule::exists('ranks', 'id')],
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'current_debt.min' => 'Nợ hiện tại không được nhỏ hơn 0.',
            'password.min' => 'Mật khẩu phải có ít nhất ' . self::PASSWORD_MIN_LENGTH . ' ký tự.',
            'rank_id.exists' => 'Cấp bậc không hợp lệ.',
        ];
    }

    /**
     * Chuẩn bị dữ liệu trước khi xác thực.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->input('name')),
            'phone' => $this->input('phone') ? preg_replace('/\s+/', '', $this->input('phone')) : null,
            'email' => $this->input('email') ? strtolower(trim($this->input('email'))) : null,
            'address' => $this->input('address') ? trim($this->input('address')) : null,
        ]);
    }
}