@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/cart/style.css') }}">
@endpush

@section('content')
    <section class="cart-page">

        <div class="container-fluid">

            <div class="cart-wrapper">

                {{-- LEFT --}}
                <div class="cart-left">

                    {{-- SHIPPING --}}
                    <div class="cart-card">

                        <div class="cart-section" data-has-address="{{ $addresses->count() ? 1 : 0 }}">

                            <h2 class="section-title">
                                Thông tin vận chuyển
                            </h2>

                            {{-- SAVED ADDRESS --}}
                            @if ($customer && $addresses->count() > 0)
                                <div class="saved-address-wrapper">

                                    <div class="saved-address-header">

                                        <h3>
                                            Địa chỉ đã lưu
                                        </h3>

                                        <button type="button" class="add-new-address-btn">

                                            + Thêm địa chỉ mới

                                        </button>

                                    </div>

                                    <div class="saved-address-list">

                                        @foreach ($addresses as $address)
                                            <div class="saved-address-card">

                                                <label class="cart-select-item">

                                                    <input type="radio" class="saved-address-radio"
                                                        name="selected_address" value="{{ $address->id }}"
                                                        {{ $address->is_default ? 'checked' : '' }}>
                                                    <span class="custom-checkbox"></span>

                                                </label>

                                                <div class="saved-address-content">

                                                    <div class="saved-address-top">

                                                        <strong>
                                                            {{ $address->full_name }}
                                                        </strong>
                                                    </div>

                                                    <p>
                                                        {{ $address->phone }}
                                                    </p>

                                                    <p>
                                                        {{ $address->address }}
                                                    </p>

                                                    @if ($address->is_default)
                                                        <span class="default-badge">
                                                            Mặc định
                                                        </span>
                                                    @endif

                                                </div>

                                                <button type="button" class="edit-address-btn"
                                                    data-address-id="{{ $address->id }}">

                                                    <i class="fa fa-edit"></i>

                                                </button>

                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            @elseif ($customer)
                                <div class="empty-address">

                                    <span>Chưa có địa chỉ giao hàng nào.</span>
                                    <span>Hãy thêm địa chỉ mới để thanh toán nhanh hơn.</span>

                                </div>
                            @endif

                            {{-- ADDRESS ID --}}
                            <input type="hidden" name="customer_address_id" id="customer_address_id"
                                value="{{ $defaultAddress?->id ?? '' }}">

                            {{-- FORM --}}
                            <div class="checkout-grid">

                                {{-- NAME --}}
                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Họ tên
                                    </label>

                                    <input type="text" class="checkout-input" name="shipping_name"
                                        value="{{ old('shipping_name') }}" placeholder="Nhập họ tên của bạn">

                                    <span class="error"></span>

                                </div>

                                {{-- PHONE --}}
                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Số điện thoại
                                    </label>

                                    <input type="text" class="checkout-input" name="shipping_phone"
                                        value="{{ old('shipping_phone', $defaultAddress?->phone) }}"
                                        placeholder="Nhập số điện thoại">

                                    <span class="error"></span>

                                </div>

                                {{-- EMAIL --}}
                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Email
                                    </label>

                                    <input type="email" class="checkout-input" name="shipping_email"
                                        value="{{ old('shipping_email', $customer?->email) }}"
                                        placeholder="Nhập email của bạn">

                                    <span class="error"></span>

                                </div>

                                {{-- ADDRESS --}}
                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Địa chỉ
                                    </label>

                                    <input type="text" class="checkout-input" name="shipping_address"
                                        value="{{ old('shipping_address', $defaultAddress?->address) }}"
                                        placeholder="Nhập địa chỉ giao hàng">

                                    <span class="error"></span>

                                </div>

                                {{-- LOCATION --}}
                                <div class="location-wrapper checkout-location">

                                    {{-- PROVINCE --}}
                                    <div class="checkout-group">

                                        <label class="checkout-label">
                                            Tỉnh / Thành phố
                                        </label>

                                        <select class="checkout-select province" name="shipping_province"
                                            data-selected="{{ old('shipping_province', $defaultAddress?->province_id) }}">

                                            <option value="">
                                                Chọn tỉnh / thành phố
                                            </option>

                                        </select>

                                        <span class="error"></span>

                                    </div>

                                    {{-- DISTRICT --}}
                                    <div class="checkout-group">

                                        <label class="checkout-label">
                                            Quận / Huyện
                                        </label>

                                        <select class="checkout-select district" name="shipping_district"
                                            data-selected="{{ old('shipping_district', $defaultAddress?->district_id) }}">

                                            <option value="">
                                                Chọn quận / huyện
                                            </option>

                                        </select>

                                        <span class="error"></span>

                                    </div>

                                    {{-- WARD --}}
                                    <div class="checkout-group">

                                        <label class="checkout-label">
                                            Xã / Phường
                                        </label>

                                        <select class="checkout-select ward" name="shipping_ward"
                                            data-selected="{{ old('shipping_ward', $defaultAddress?->ward_id) }}">

                                            <option value="">
                                                Chọn xã / phường
                                            </option>

                                        </select>

                                        <span class="error"></span>

                                    </div>

                                </div>

                                {{-- NOTE --}}
                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Ghi chú
                                    </label>

                                    <textarea class="checkout-textarea" name="note" placeholder="Ghi chú đơn hàng...">{{ old('note') }}</textarea>

                                </div>

                            </div>

                        </div>

                        {{-- SAVE ADDRESS --}}
                        @if ($customer)
                            <div class="save-address-check">

                                <label class="cart-select-item">

                                    <input type="checkbox" class="cart-item-checkbox" name="save_address" value="1">
                                    <span class="custom-checkbox"></span>
                                    Lưu địa chỉ này cho lần sau

                                </label>

                            </div>
                        @endif

                        {{-- PAYMENT --}}
                        <div class="cart-section">

                            <h2 class="section-title" style="font-size:24px;">
                                Hình thức thanh toán
                            </h2>

                            <div class="payment-list">

                                <div class="payment-item active" data-method="cod">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            Thanh toán khi nhận hàng
                                        </strong>

                                        <span>
                                            Kiểm tra sản phẩm trước khi thanh toán
                                        </span>

                                    </div>

                                </div>

                                {{-- <div class="payment-item" data-method="zalopay">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            Thanh toán qua ZaloPay
                                        </strong>

                                        <span>
                                            Visa / ATM / QR Code
                                        </span>

                                    </div>

                                </div> --}}

                                {{-- <div class="payment-item" data-method="momo">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            Ví điện tử MoMo
                                        </strong>

                                        <span>
                                            Thanh toán nhanh chóng
                                        </span>

                                    </div>

                                </div> --}}

                                <div class="payment-item" data-method="vnpay">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            VNPay
                                        </strong>

                                        <span>
                                            Hỗ trợ mọi ngân hàng
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="cart-right">

                    <div class="cart-right-sticky">

                        <div class="cart-card cart-loading-parent">

                            {{-- HEADER --}}
                            <div class="cart-header">

                                <div class="cart-title">
                                    Giỏ hàng
                                </div>

                            </div>

                            {{-- SKELETON --}}
                            <div id="cart-skeleton" class="cart-skeleton">

                                <div class="cart-toolbar skeleton-toolbar"></div>

                                @for ($i = 0; $i < 3; $i++)
                                    <div class="cart-item skeleton-item">

                                        <div class="skeleton skeleton-image"></div>

                                        <div class="skeleton-content">

                                            <div class="skeleton skeleton-line lg"></div>

                                            <div class="skeleton skeleton-line md"></div>

                                            <div class="skeleton skeleton-line sm"></div>

                                        </div>

                                        <div class="skeleton-price">

                                            <div class="skeleton skeleton-line md"></div>

                                            <div class="skeleton skeleton-qty"></div>

                                        </div>

                                    </div>
                                @endfor

                            </div>

                            {{-- AJAX ITEMS --}}
                            <div id="cart-items-wrapper">

                                @include('frontend.cart.partials.items')

                            </div>

                        </div>

                    </div>


                </div>

            </div>
    </section>

    {{-- FIXED CHECKOUT FOOTER --}}
    <div id="fixed-bar-wrapper">
        @include('frontend.cart.partials.fixed-bar')
    </div>
@endsection

{{-- LOGIN MODAL --}}
@include('frontend.cart.partials.check-auth-modal')

{{-- ADDRESS MODAL --}}
@include('frontend.cart.partials.address-modal', [
    'id' => 'addressModal',
    'size' => 'modal-lg',
    'title' => 'Thông tin địa chỉ',
    'formId' => 'address_form',
    'hiddenFields' => [
        [
            'name' => 'address_id',
            'id' => 'address_id',
        ],
        [
            'name' => 'mode',
            'id' => 'address_mode',
            'value' => 'create',
        ],
    ],
    'submitId' => 'save_address_btn',
    'submitText' => 'Lưu địa chỉ',
    'body' => view('frontend.cart.partials.address-form')->render(),
])

@include('frontend.cart.partials.config')

@push('scripts')
    <script src="{{ asset('frontend/js/cart/core/cache.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/services/api.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/modules/cart.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/modules/address.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/modules/validation.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/modules/checkout.js') }}"></script>

    <script src="{{ asset('frontend/js/cart/index.js') }}"></script>
@endpush
