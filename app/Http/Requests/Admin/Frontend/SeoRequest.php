<?php

namespace App\Http\Requests\Admin\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SeoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keywords' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],

            'social_title' => ['nullable', 'string', 'max:255'],
            'social_description' => ['nullable', 'string', 'max:1000'],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,avif',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'keywords' => 'từ khóa',
            'description' => 'mô tả SEO',
            'social_title' => 'tiêu đề mạng xã hội',
            'social_description' => 'mô tả mạng xã hội',
            'image' => 'ảnh SEO',
        ];
    }
}
