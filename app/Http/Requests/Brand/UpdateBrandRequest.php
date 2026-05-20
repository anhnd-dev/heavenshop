<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
            'brand_id' => [
                'required',
                'exists:brands,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',

                Rule::unique('brands', 'name')
                    ->ignore($this->brand_id),
            ],

            'slug' => [
                'required',
                'string',
                'max:100',

                Rule::unique('brands', 'slug')
                    ->ignore($this->brand_id),
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            // BRAND ID
            'brand_id.required' => 'ID thương hiệu không hợp lệ',
            'brand_id.exists'   => 'Thương hiệu không tồn tại',

            // NAME
            'name.required' => 'Tên thương hiệu không được để trống',
            'name.string'   => 'Tên thương hiệu không hợp lệ',
            'name.max'      => 'Tên thương hiệu tối đa 100 ký tự',
            'name.unique'   => 'Tên thương hiệu đã tồn tại',

            // SLUG
            'slug.required' => 'Slug không được để trống',
            'slug.string'   => 'Slug không hợp lệ',
            'slug.max'      => 'Slug tối đa 100 ký tự',
            'slug.unique'   => 'Slug đã tồn tại',

            // IMAGE
            'image.image'   => 'File tải lên phải là hình ảnh',
            'image.mimes'   => 'Hình ảnh phải có định dạng jpg, jpeg, png hoặc webp',
            'image.max'     => 'Dung lượng ảnh tối đa là 2MB',
        ];
    }

    /**
     * Attributes
     */
    public function attributes(): array
    {
        return [
            'brand_id' => 'ID thương hiệu',
            'name'     => 'tên thương hiệu',
            'slug'     => 'slug',
            'image'    => 'hình ảnh',
        ];
    }
}
