<?php

namespace App\Http\Requests\Admin\ProductGallery;

use Illuminate\Validation\Rule;
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
        $productId = $this->route('product');
        $galleryId = $this->route('id');

        return [

            'files' => [
                'nullable',
                'array',
            ],

            'files.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,avif,mp4,mov,webm',
                'max:51200',
            ],

            'color_id' => [
                'nullable',
                'exists:colors,id',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',

                Rule::unique('product_galleries')
                    ->ignore($galleryId)
                    ->where(function ($query) use ($productId) {

                        $query->where('product_id', $productId);

                        if ($this->filled('color_id')) {
                            $query->where('color_id', $this->color_id);
                        } else {
                            $query->whereNull('color_id');
                        }

                        return $query;
                    }),
            ],
        ];
    }

    /**
     * =========================
     * MESSAGES
     * =========================
     */
    public function messages(): array
    {
        return [
            'sort_order.unique' =>
            'Màu sắc này đã tồn tại thứ tự hiển thị này.',
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
            'sort_order' => 'thứ tự hiển thị',
        ];
    }
}
