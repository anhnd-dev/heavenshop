<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:50',
            ],

            'slug' => [
                'required',
                'string',
                'max:50',
            ],

            'type' => [
                'required',
                Rule::in([
                    'product',
                    'blog',
                ]),
            ],

            'parent_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,avif',
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

            'name.required' => 'Tên danh mục không được để trống',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'name.max' => 'Tên danh mục tối đa 50 ký tự',

            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'slug.max' => 'Slug tối đa 50 ký tự',

            'type.required' => 'Vui lòng chọn loại danh mục',

            'parent_id.exists' => 'Danh mục cha không tồn tại',

            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp',
            'image.max' => 'Kích thước ảnh tối đa 2MB',
        ];
    }
}
