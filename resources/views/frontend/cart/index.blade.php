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

                        <div class="cart-section">

                            <h2 class="section-title">
                                Thông tin vận chuyển
                            </h2>

                            <div class="shipping-note">
                                Bằng việc ấn nút đặt hàng bạn xác nhận đã đọc và đồng ý với điều khoản mua hàng.
                            </div>

                            <div class="checkout-grid">

                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Họ tên
                                    </label>

                                    <input type="text" class="checkout-input" placeholder="Nhập họ tên của bạn">

                                </div>

                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Số điện thoại
                                    </label>

                                    <input type="text" class="checkout-input" placeholder="Nhập số điện thoại">

                                </div>

                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Email
                                    </label>

                                    <input type="email" class="checkout-input" placeholder="Nhập email của bạn">

                                </div>

                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Địa chỉ
                                    </label>

                                    <input type="text" class="checkout-input" placeholder="Nhập địa chỉ giao hàng">

                                </div>

                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Tỉnh / Thành phố
                                    </label>

                                    <select class="checkout-select">
                                        <option>
                                            Chọn tỉnh / thành phố
                                        </option>
                                    </select>

                                </div>

                                <div class="checkout-group">

                                    <label class="checkout-label">
                                        Quận / Huyện
                                    </label>

                                    <select class="checkout-select">
                                        <option>
                                            Chọn quận / huyện
                                        </option>
                                    </select>

                                </div>

                                <div class="checkout-group full">

                                    <label class="checkout-label">
                                        Ghi chú
                                    </label>

                                    <textarea class="checkout-textarea" placeholder="Ghi chú đơn hàng..."></textarea>

                                </div>

                            </div>

                        </div>

                        {{-- PAYMENT --}}
                        <div class="cart-section">

                            <h2 class="section-title" style="font-size:24px;">
                                Hình thức thanh toán
                            </h2>

                            <div class="payment-list">

                                <div class="payment-item active">

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

                                <div class="payment-item">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            Thanh toán qua ZaloPay
                                        </strong>

                                        <span>
                                            Visa / ATM / QR Code
                                        </span>

                                    </div>

                                </div>

                                <div class="payment-item">

                                    <div class="payment-radio"></div>

                                    <div class="payment-info">

                                        <strong>
                                            Ví điện tử MoMo
                                        </strong>

                                        <span>
                                            Thanh toán nhanh chóng
                                        </span>

                                    </div>

                                </div>

                                <div class="payment-item">

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

@push('scripts')
    <script>
        // =========================
        // LOAD CART ITEMS
        // =========================
        function loadCartItems() {

            let couponCode = $('#coupon-code').val();

            $('.cart-loading-parent')
                .addClass('loading');

            $.ajax({

                url: "{{ route('cart.items') }}",

                type: "GET",

                success: function(res) {

                    $('#cart-items-wrapper')
                        .html(res.items);

                    $('#fixed-bar-wrapper')
                        .html(res.fixedBar);

                    // restore coupon input
                    $('#coupon-code')
                        .val(couponCode);

                    $('.cart-loading-parent')
                        .removeClass('loading');
                },

                error: function() {

                    $('.cart-loading-parent')
                        .removeClass('loading');

                    toastr.error(
                        'Không thể tải giỏ hàng'
                    );
                }
            });
        }

        // =========================
        // UPDATE QUANTITY BUTTON
        // =========================
        $(document).on(
            'click',
            '.qty-increase, .qty-decrease',
            function() {

                let button = $(this);

                let variantId =
                    button.data('variant');

                let input =
                    $('.qty-input-' + variantId);

                let current =
                    parseInt(input.val());

                if (
                    button.hasClass('qty-increase')
                ) {

                    current++;

                } else {

                    if (current <= 1) {
                        return;
                    }

                    current--;
                }

                updateCartQuantity(
                    variantId,
                    current
                );
            }
        );

        // =========================
        // UPDATE CART AJAX
        // =========================
        function updateCartQuantity(
            variantId,
            quantity
        ) {

            $.ajax({

                url: "{{ route('cart.update') }}",

                type: "POST",

                data: {

                    _token: "{{ csrf_token() }}",

                    variant_id: variantId,

                    quantity: quantity
                },

                success: function(res) {

                    loadCartItems();

                    loadHeaderCart();

                    toastr.success(
                        res.message
                    );
                },

                error: function(xhr) {

                    toastr.error(
                        xhr.responseJSON.message
                    );
                }
            });
        }

        // =========================
        // REMOVE ITEM
        // =========================
        $(document).on(
            'click',
            '.remove-cart-item',
            function() {

                let variantId =
                    $(this).data('variant');

                $.ajax({

                    url: "/cart/remove/" +
                        variantId,

                    type: "DELETE",

                    data: {

                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        loadCartItems();

                        loadHeaderCart();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // CHANGE VARIANT
        // =========================
        $(document).on(
            'change',
            '.cart-color-select, .cart-size-select',
            function() {

                let wrapper =
                    $(this).closest('.cart-variant-select');

                let oldVariantId =
                    $(this).data('variant');

                let colorId =
                    wrapper.find('.cart-color-select').val();

                let sizeId =
                    wrapper.find('.cart-size-select').val();

                // loading
                wrapper.addClass('loading');

                $.ajax({

                    url: "{{ route('cart.changeVariant') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        old_variant_id: oldVariantId,

                        color_id: colorId,

                        size_id: sizeId
                    },

                    success: function(res) {

                        loadCartItems();

                        loadHeaderCart();

                        toastr.success(res.message);
                    },

                    error: function(xhr) {

                        wrapper.removeClass('loading');

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // CLEAR CART
        // =========================
        $(document).on(
            'click',
            '.cart-clear-btn',
            function() {

                if (
                    !confirm(
                        'Bạn muốn xóa toàn bộ giỏ hàng?'
                    )
                ) {
                    return;
                }

                $.ajax({

                    url: "{{ route('cart.clear') }}",

                    type: "DELETE",

                    data: {

                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        loadCartItems();

                        loadHeaderCart();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // PAYMENT SELECT
        // =========================
        $(document).on(
            'click',
            '.payment-item',
            function() {

                $('.payment-item')
                    .removeClass('active');

                $(this)
                    .addClass('active');
            }
        );

        // =========================
        // SELECT SINGLE ITEM
        // =========================
        $(document).on(
            'change',
            '.cart-item-checkbox',
            function() {

                $.ajax({

                    url: "{{ route('cart.select') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        variant_id: $(this).data('variant'),

                        selected: $(this).is(':checked') ?
                            1 : 0
                    },

                    success: function(res) {

                        loadCartItems();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // SELECT ALL
        // =========================
        $(document).on(
            'change',
            '#select-all',
            function() {

                $.ajax({

                    url: "{{ route('cart.selectAll') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        selected: $(this).is(':checked') ?
                            1 : 0
                    },

                    success: function(res) {

                        loadCartItems();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // APPLY COUPON
        // =========================
        $(document).on(
            'click',
            '#apply-coupon-btn',
            function() {

                let couponCode =
                    $('#coupon-code')
                    .val()
                    .trim();

                if (!couponCode) {

                    toastr.error(
                        'Vui lòng nhập mã giảm giá'
                    );

                    return;
                }

                let checkedItems =
                    $('.cart-item-checkbox:checked')
                    .length;

                if (checkedItems <= 0) {

                    toastr.error(
                        'Vui lòng chọn sản phẩm'
                    );

                    return;
                }

                $.ajax({

                    url: "{{ route('cart.applyCoupon') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        coupon_code: couponCode
                    },

                    success: function(res) {

                        loadCartItems();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // QUICK APPLY COUPON
        // =========================
        $(document).on(
            'click',
            '.apply-coupon-card',
            function() {

                let code =
                    $(this).data('code');

                // remove selected
                $('.voucher-card')
                    .removeClass('active');

                // active current
                $(this)
                    .addClass('active');

                $('#coupon-code')
                    .val(code);

                $('#apply-coupon-btn')
                    .click();
            }
        );

        // =========================
        // REMOVE COUPON
        // =========================
        $(document).on(
            'click',
            '.remove-coupon-btn',
            function() {

                $.ajax({

                    url: "{{ route('cart.removeCoupon') }}",

                    type: "DELETE",

                    data: {

                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        loadCartItems();

                        toastr.success(
                            res.message
                        );
                    },

                    error: function(xhr) {

                        toastr.error(
                            xhr.responseJSON.message
                        );
                    }
                });
            }
        );

        // =========================
        // INIT
        // =========================
        $(document).ready(function() {

            loadCartItems();
        });
    </script>
@endpush
