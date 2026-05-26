<div class="checkout-fixed-bar">

    <div class="checkout-fixed-left">

        <div class="fixed-payment">
            <i class="fas fa-money-bill-wave"></i>
            <span>Thanh toán khi nhận hàng</span>
        </div>

        <div class="fixed-voucher">
            <i class="fas fa-ticket-alt"></i>
            <span>
                @if (session()->has('applied_coupon'))
                    {{ session('applied_coupon.code') }}
                @else
                    Voucher
                @endif
            </span>
        </div>

    </div>

    <div class="checkout-fixed-right">

        <div class="fixed-total">

            <strong id="fixed-total-price">
                {{ number_format($total, 0, ',', '.') }}đ
            </strong>

            <span>

                @auth('customer')
                    @if ($subtotal > 0)
                        Sẵn sàng đặt hàng
                    @else
                        Vui lòng chọn sản phẩm
                    @endif
                @else
                    <a href="">
                        Đăng nhập
                    </a>

                    để hoàn thành đặt hàng!
                @endauth

            </span>

        </div>

        <button class="fixed-order-btn" id="fixed-order-btn" {{ $subtotal <= 0 ? 'disabled' : '' }}>
            ĐẶT HÀNG
        </button>

    </div>

</div>
