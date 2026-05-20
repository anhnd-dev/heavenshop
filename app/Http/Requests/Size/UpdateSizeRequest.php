<?php

namespace App\Http\Requests\Size;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sizes', 'name')->ignore(
                    $this->size_id
                ),
            ],
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên kích cỡ không được để trống',
            'name.string'   => 'Tên kích cỡ phải là chuỗi',
            'name.max'      => 'Tên kích cỡ không được vượt quá 50 ký tự',
            'name.unique'   => 'Kích cỡ này đã tồn tại',
        ];
    }
}
