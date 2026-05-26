<?php

namespace App\Http\Requests\ProductGallery;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductGalleryRequest extends FormRequest
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

            'files' => [
                'required',
                'array',
                'min:1',
            ],

            'files.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov,webm,avif',
                'max:51200',
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
            'files' => 'media',
            'files.*' => 'media',
            'color_id' => 'màu sắc',
            'sort_order' => 'thứ tự',
        ];
    }
}
