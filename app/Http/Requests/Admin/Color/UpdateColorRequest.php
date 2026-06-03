<?php

namespace App\Http\Requests\Admin\Color;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateColorRequest extends FormRequest
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

                Rule::unique('colors', 'name')
                    ->ignore($this->color_id),
            ],

            'code' => [
                'required',
                'string',
                'max:50',

                Rule::unique('colors', 'code')
                    ->ignore($this->color_id),

                'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/',
            ],
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            // NAME

            'name.required' => 'Tên màu sắc không được để trống',

            'name.string' => 'Tên màu sắc không hợp lệ',

            'name.max' => 'Tên màu sắc không được vượt quá 50 ký tự',

            'name.unique' => 'Tên màu sắc đã tồn tại',


            // CODE

            'code.required' => 'Mã màu không được để trống',

            'code.string' => 'Mã màu không hợp lệ',

            'code.max' => 'Mã màu không được vượt quá 50 ký tự',

            'code.unique' => 'Mã màu đã tồn tại',

            'code.regex' => 'Mã màu phải đúng định dạng HEX. Ví dụ: #000000 hoặc #FFF',
        ];
    }
}
