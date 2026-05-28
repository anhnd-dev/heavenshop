@php
    $allSelected =
        collect($cart)->isNotEmpty() &&
        collect($cart)->every(function ($item) {
            return $item['selected'] ?? false;
        });

@endphp

<div class="cart-toolbar">

    <label class="cart-select-all">

        <input type="checkbox" id="select-all" {{ $allSelected ? 'checked' : '' }}>

        <span class="custom-checkbox"></span>

        <span class="text">Tất cả sản phẩm</span>

    </label>

    <button class="cart-clear-btn">
        Xóa tất cả
    </button>

</div>

<div class="cart-items">

    @forelse($cart as $item)

        @php
            $colors = collect($item['variants'])->unique('color_id')->values();

            $sizes = collect($item['variants'])->unique('size_id')->values();
        @endphp
        <div class="cart-item">

            <label class="cart-select-item">

                <input type="checkbox" class="cart-item-checkbox" data-variant="{{ $item['variant_id'] }}"
                    value="{{ $item['variant_id'] }}" {{ $item['selected'] ?? false ? 'checked' : '' }}>

                <span class="custom-checkbox"></span>

            </label>

            <div class="cart-image">

                <img src="{{ asset('uploads/variant/' . $item['image']) }}" alt="{{ $item['product_name'] }}">

            </div>

            <div class="cart-content">

                <div class="cart-info">

                    <div class="cart-name">
                        {{ $item['product_name'] }}
                    </div>

                    <div class="cart-variant">

                        <div class="variant-pill">
                            {{ $item['color'] }}
                        </div>

                        <span class="variant-slash">/</span>

                        <div class="variant-pill">
                            {{ $item['size'] }}
                        </div>

                    </div>

                    <div class="cart-variant-select">

                        {{-- COLOR --}}
                        <div class="variant-box">

                            <select class="variant-select cart-color-select" data-variant="{{ $item['variant_id'] }}">

                                @foreach ($colors as $color)
                                    <option value="{{ $color['color_id'] }}"
                                        {{ $item['color'] == $color['color_name'] ? 'selected' : '' }}>
                                        {{ $color['color_name'] }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- SIZE --}}
                        <div class="variant-box">

                            <select class="variant-select cart-size-select" data-variant="{{ $item['variant_id'] }}">

                                @foreach ($sizes as $size)
                                    <option value="{{ $size['size_id'] }}"
                                        {{ $item['size'] == $size['size_name'] ? 'selected' : '' }}>
                                        {{ $size['size_name'] }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <button class="cart-remove remove-cart-item" data-variant="{{ $item['variant_id'] }}">

                        <i class="fa fa-trash"></i>
                        Xóa

                    </button>

                </div>

                {{-- PRICE --}}
                <div class="cart-price-area">

                    <div class="cart-price">
                        {{ number_format($item['price'], 0, ',', '.') }}đ
                    </div>

                    <div class="cart-old-price">
                        {{ number_format($item['price'] + 120000, 0, ',', '.') }}đ
                    </div>

                    {{-- QTY --}}
                    <div class="qty-box">

                        <button type="button" class="qty-btn qty-decrease" data-variant="{{ $item['variant_id'] }}">

                            -

                        </button>

                        <input type="text" readonly value="{{ $item['quantity'] }}"
                            class="qty-input qty-input-{{ $item['variant_id'] }}">

                        <button type="button" class="qty-btn qty-increase" data-variant="{{ $item['variant_id'] }}">

                            +

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div style="padding:60px 30px;text-align:center;">

            <img src="{{ asset('frontend/images/empty-cart.png') }}" style="width:140px;opacity:.8;">

            <h3 style="font-size:24px;font-weight:800;margin-bottom:10px;">
                Giỏ hàng đang trống
            </h3>

            <p style="color:#888;">
                Hãy thêm sản phẩm vào giỏ hàng của bạn
            </p>

        </div>
    @endforelse

</div>

{{-- VOUCHER --}}
<div class="voucher-wrapper">

    <div class="voucher-title">
        Ưu đãi dành cho bạn
    </div>

    @php
        $appliedCoupon = session('applied_coupon.code');
    @endphp

    <div class="voucher-slider">

        @foreach ($availableCoupons as $coupon)
            <div class="voucher-card apply-coupon-card
                {{ $appliedCoupon === $coupon->code ? 'active' : '' }}"
                data-code="{{ $coupon->code }}">

                <div class="voucher-content">

                    <div class="voucher-title-text">
                        {{ $coupon->code }}

                        <span>
                            @if (!$coupon->is_unlimited)
                                (Còn {{ $coupon->quantity - $coupon->used_count }})
                            @endif
                        </span>
                    </div>

                    <div class="voucher-desc">
                        {{ $coupon->description }}
                    </div>

                    <div class="voucher-expire">
                        HSD:
                        {{ optional($coupon->end_date)->format('d/m/Y') }}
                    </div>

                </div>

                <div class="voucher-footer">

                    @if ($appliedCoupon === $coupon->code)
                        <div class="radio remove-coupon-btn"></div>
                    @else
                        <div class="radio"></div>
                    @endif

                </div>

            </div>
        @endforeach

    </div>

    <div class="coupon-form">

        <input type="text" id="coupon-code" class="coupon-input" placeholder="Nhập mã giảm giá">

        <button type="button" class="coupon-btn" id="apply-coupon-btn">
            Áp dụng
        </button>

    </div>
</div>

{{-- SUMMARY --}}
<div class="summary-box">

    <div class="summary-title">
        Chi tiết thanh toán
    </div>

    <div class="summary-row">

        <span class="summary-muted">
            Tạm tính
        </span>

        <strong>
            {{ number_format($subtotal, 0, ',', '.') }}đ
        </strong>

    </div>

    <div class="summary-row">

        <span class="summary-muted">
            Voucher giảm giá
        </span>

        <strong class="summary-discount">
            -{{ number_format($discount, 0, ',', '.') }}đ
        </strong>

        @if (session()->has('applied_coupon'))
            <div class="applied-coupon">

                Đã áp dụng:
                <strong>
                    {{ session('applied_coupon.code') }}
                </strong>

            </div>
        @endif

    </div>

    <div class="summary-row">

        <span class="summary-muted">
            Phí giao hàng
        </span>

        <strong>
            {{ $shipping <= 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' }}
        </strong>

    </div>

    <div class="summary-row total">

        <span>
            Thành tiền
        </span>

        <span>
            {{ number_format($total, 0, ',', '.') }}đ
        </span>

    </div>

</div>
