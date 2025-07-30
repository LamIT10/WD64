<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRankRequest extends FormRequest
{
    private const NAME_MAX_LENGTH = 255;
    private const NOTE_MAX_LENGTH = 1000;
    private const PERCENT_MAX_VALUE = 100;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:' . self::NAME_MAX_LENGTH,
                Rule::unique('ranks', 'name')->ignore($this->route('rank')?->id),
            ],
            'min_total_spent' => [
                'required',
                'numeric',
                'min:1',
            ],
            'discount_percent' => [
                'required',
                'numeric',
                'min:1',
                'max:' . self::PERCENT_MAX_VALUE,
            ],
            'credit_percent' => [
                'required',
                'numeric',
                'min:1',
                'max:' . self::PERCENT_MAX_VALUE,
            ],
            'note' => ['nullable', 'string', 'max:' . self::NOTE_MAX_LENGTH],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên hạng là bắt buộc.',
            'name.min' => 'Tên hạng phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên hạng không được vượt quá ' . self::NAME_MAX_LENGTH . ' ký tự.',
            'name.unique' => 'Tên hạng đã tồn tại.',
            'min_total_spent.required' => 'Tổng chi tiêu tối thiểu là bắt buộc.',
            'min_total_spent.min' => 'Tổng chi tiêu tối thiểu phải lớn hơn 0.',
            'discount_percent.required' => 'Phần trăm chiết khấu là bắt buộc.',
            'discount_percent.min' => 'Phần trăm chiết khấu phải lớn hơn 0.',
            'discount_percent.max' => 'Phần trăm chiết khấu không được vượt quá ' . self::PERCENT_MAX_VALUE . '%.',
            'credit_percent.required' => 'Phần trăm tín dụng là bắt buộc.',
            'credit_percent.min' => 'Phần trăm tín dụng phải lớn hơn 0.',
            'credit_percent.max' => 'Phần trăm tín dụng không được vượt quá ' . self::PERCENT_MAX_VALUE . '%.',
            'note.max' => 'Ghi chú không được vượt quá ' . self::NOTE_MAX_LENGTH . ' ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->input('name') ?? ''),
            'note' => $this->input('note') ? trim($this->input('note')) : null,
            'min_total_spent' => $this->input('min_total_spent') !== '' ? $this->input('min_total_spent') : null,
            'discount_percent' => $this->input('discount_percent') !== '' ? $this->input('discount_percent') : null,
            'credit_percent' => $this->input('credit_percent') !== '' ? $this->input('credit_percent') : null,
        ]);
    }
}