<?php

namespace App\Http\Requests\ProductGallery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductGalleryRequest extends FormRequest
{
    /**
     * =========================
     * AUTHORIZE
     * =========================
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * =========================
     * RULES
     * =========================
     */
    public function rules(): array
    {
        return [

            'file' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov,webm,avif',
                'max:51200',
            ],

            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'type' => [
                'required',
                'in:image,video',
            ],

            'color_id' => [
                'nullable',
                'exists:colors,id',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * =========================
     * ATTRIBUTES
     * =========================
     */
    public function attributes(): array
    {
        return [
            'file' => 'media',
            'thumbnail' => 'thumbnail',
            'type' => 'loại media',
            'color_id' => 'màu sắc',
            'sort_order' => 'thứ tự',
        ];
    }
}
