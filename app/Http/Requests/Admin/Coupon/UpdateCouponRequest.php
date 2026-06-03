<?php

namespace App\Http\Requests\Admin\Coupon;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim($this->code)),
        ]);
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [
            'coupon_id' => [
                'required',
                'exists:coupons,id',
            ],

            'code' => [
                'required',
                'string',
                'max:50',

                Rule::unique('coupons', 'code')
                    ->ignore($this->coupon_id),
            ],

            'discount_type' => [
                'required',

                Rule::in([
                    Coupon::TYPE_PERCENTAGE,
                    Coupon::TYPE_FIXED,
                ]),
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_unlimited' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [
            'coupon_id.required' => 'Không tìm thấy coupon',
            'coupon_id.exists' => 'Coupon không tồn tại',

            'code.required' => 'Mã coupon không được để trống',
            'code.unique' => 'Mã coupon đã tồn tại',
            'code.max' => 'Mã coupon tối đa 50 ký tự',

            'discount_type.required' => 'Vui lòng chọn loại giảm giá',

            'discount_value.required' => 'Giá trị giảm giá không được để trống',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0',

            'min_order_amount.numeric' => 'Đơn tối thiểu phải là số',
            'min_order_amount.min' => 'Đơn tối thiểu phải lớn hơn hoặc bằng 0',

            'max_discount_amount.numeric' => 'Giảm tối đa phải là số',
            'max_discount_amount.min' => 'Giảm tối đa phải lớn hơn hoặc bằng 0',

            'quantity.integer' => 'Số lượng phải là số nguyên',
            'quantity.min' => 'Số lượng không hợp lệ',

            'start_date.date' => 'Ngày bắt đầu không hợp lệ',

            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu',
        ];
    }

    /**
     * Attributes
     */
    public function attributes(): array
    {
        return [
            'code' => 'mã coupon',
            'discount_type' => 'loại giảm giá',
            'discount_value' => 'giá trị giảm giá',
            'min_order_amount' => 'đơn tối thiểu',
            'max_discount_amount' => 'giảm tối đa',
            'quantity' => 'số lượng',
            'start_date' => 'ngày bắt đầu',
            'end_date' => 'ngày kết thúc',
        ];
    }
}
