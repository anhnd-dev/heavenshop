<?php

namespace App\Http\Requests\Admin\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:20',
            ],

            'question' => [
                'nullable',
                'string',
            ],

            'image_url' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,avif',
                'max:2048',
            ],

            'map_url' => [
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
            'title' => 'title',
            'address' => 'address',
            'email' => 'email',
            'phone_number' => 'phone number',
            'question' => 'question',
            'image_url' => 'contact image',
            'map_url' => 'map image',
        ];
    }
}
