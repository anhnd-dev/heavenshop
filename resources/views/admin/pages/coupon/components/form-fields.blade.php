@php
    $isEdit = $prefix === 'edit';

    $idPrefix = $isEdit ? 'update_' : '';
@endphp

{{-- CODE --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}code">
        {{ __('admin.coupon.code') }}
    </label>

    <input type="text" class="form-control" id="{{ $idPrefix }}code" name="code"
        placeholder="{{ __('admin.coupon.enter_code') }}">

</div>

{{-- DISCOUNT TYPE --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}discount_type">
        {{ __('admin.coupon.discount_type') }}
    </label>

    <select name="discount_type" id="{{ $idPrefix }}discount_type" class="form-control">

        <option value="">
            {{ __('admin.coupon.select_type') }}
        </option>

        <option value="fixed">
            {{ __('admin.coupon.fixed') }}
        </option>

        <option value="percentage">
            {{ __('admin.coupon.percentage') }}
        </option>

    </select>

</div>

{{-- DISCOUNT VALUE --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}discount_value">
        {{ __('admin.coupon.discount_value') }}
    </label>

    <input type="number" step="0.01" min="0" class="form-control" id="{{ $idPrefix }}discount_value"
        name="discount_value" placeholder="{{ __('admin.coupon.enter_discount_value') }}">

</div>

{{-- MIN ORDER AMOUNT --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}min_order_amount">
        {{ __('admin.coupon.min_order_amount') }}
    </label>

    <input type="number" step="0.01" min="0" class="form-control" id="{{ $idPrefix }}min_order_amount"
        name="min_order_amount" placeholder="{{ __('admin.coupon.enter_min_order_amount') }}">

</div>

{{-- MAX DISCOUNT AMOUNT --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}max_discount_amount">
        {{ __('admin.coupon.max_discount_amount') }}
    </label>

    <input type="number" step="0.01" min="0" class="form-control"
        id="{{ $idPrefix }}max_discount_amount" name="max_discount_amount"
        placeholder="{{ __('admin.coupon.enter_max_discount_amount') }}">

</div>

{{-- QUANTITY --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}quantity">
        {{ __('admin.coupon.quantity') }}
    </label>

    <input type="number" min="0" class="form-control" id="{{ $idPrefix }}quantity" name="quantity"
        placeholder="{{ __('admin.coupon.enter_quantity') }}">

</div>

{{-- UNLIMITED --}}
<div class="form-group mb-4">

    <div class="form-check custom-checkbox">

        <input type="checkbox" class="form-check-input" id="{{ $idPrefix }}is_unlimited" name="is_unlimited"
            value="1">

        <label class="form-check-label" for="{{ $idPrefix }}is_unlimited">

            {{ __('admin.coupon.unlimited') }}

        </label>

    </div>

</div>

{{-- START DATE --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}start_date">
        {{ __('admin.coupon.start_date') }}
    </label>

    <input type="datetime-local" class="form-control" id="{{ $idPrefix }}start_date" name="start_date">

</div>

{{-- END DATE --}}
<div class="form-group mb-4">

    <label for="{{ $idPrefix }}end_date">
        {{ __('admin.coupon.end_date') }}
    </label>

    <input type="datetime-local" class="form-control" id="{{ $idPrefix }}end_date" name="end_date">

</div>
