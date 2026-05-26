<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSliderRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'position' => [
                'required',
                Rule::in(array_keys(\App\Models\Slider::POSITIONS)),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'start_at' => [
                'nullable',
                'date',
            ],

            'end_at' => [
                'nullable',
                'date',
                'after_or_equal:start_at',
            ],
        ];
    }

    /**
     * Attributes
     */
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề slider',
            'subtitle' => 'mô tả slider',
            'url' => 'đường dẫn',
            'position' => 'vị trí',
            'sort_order' => 'thứ tự hiển thị',
            'image' => 'ảnh slider',
            'start_at' => 'thời gian bắt đầu',
            'end_at' => 'thời gian kết thúc',
        ];
    }
}
