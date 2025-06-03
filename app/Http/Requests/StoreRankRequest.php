<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRankRequest extends FormRequest
{
    // Hằng số cho các ràng buộc xác thực
    private const NAME_MAX_LENGTH = 255;
    private const NOTE_MAX_LENGTH = 1000;
    private const PERCENT_MAX_VALUE = 100;

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
            'name' => [
                'required',
                'string',
                'max:' . self::NAME_MAX_LENGTH,
                Rule::unique('ranks', 'name')->ignore($this->rank?->id),
            ],
            'min_total_spent' => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:' . self::PERCENT_MAX_VALUE],
            'credit_percent' => ['required', 'numeric', 'min:0', 'max:' . self::PERCENT_MAX_VALUE],
            'note' => ['nullable', 'string', 'max:' . self::NOTE_MAX_LENGTH],
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
            'name.required' => 'Tên cấp bậc là bắt buộc.',
            'name.unique' => 'Tên cấp bậc đã tồn tại.',
            'min_total_spent.required' => 'Tổng chi tiêu tối thiểu là bắt buộc.',
            'min_total_spent.min' => 'Tổng chi tiêu tối thiểu không được nhỏ hơn 0.',
            'discount_percent.required' => 'Phần trăm chiết khấu là bắt buộc.',
            'discount_percent.max' => 'Phần trăm chiết khấu không được vượt quá ' . self::PERCENT_MAX_VALUE . '%.',
            'credit_percent.required' => 'Phần trăm tín dụng là bắt buộc.',
            'credit_percent.max' => 'Phần trăm tín dụng không được vượt quá ' . self::PERCENT_MAX_VALUE . '%.',
            'note.max' => 'Ghi chú không được vượt quá ' . self::NOTE_MAX_LENGTH . ' ký tự.',
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
            'note' => $this->input('note') ? trim($this->input('note')) : null,
        ]);
    }
}