<div class="order-detail">

    {{-- HEADER --}}
    <div class="detail-header">

        <div>

            <h2>
                Đơn hàng #{{ $order->order_code }}
            </h2>

            <p>
                {{ $order->created_at->format('d/m/Y H:i') }}
            </p>

        </div>

        <div class="order-status {{ $order->order_status }}">

            {{ $order->status_label }}

        </div>

    </div>

    {{-- TIMELINE --}}
    <div class="detail-card">

        <h3>Tiến trình đơn hàng</h3>

        <div class="timeline">

            <div class="timeline-item active">

                <div class="dot"></div>

                <div>

                    <strong>
                        Đặt hàng thành công
                    </strong>

                    <p>
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>

                </div>

            </div>

            <div class="timeline-item
                {{ $order->confirmed_at ? 'active' : '' }}">

                <div class="dot"></div>

                <div>

                    <strong>
                        Đã xác nhận
                    </strong>

                    <p>
                        {{ optional($order->confirmed_at)->format('d/m/Y H:i') }}
                    </p>

                </div>

            </div>

            <div class="timeline-item
                {{ $order->shipped_at ? 'active' : '' }}">

                <div class="dot"></div>

                <div>

                    <strong>
                        Đang giao hàng
                    </strong>

                    <p>
                        {{ optional($order->shipped_at)->format('d/m/Y H:i') }}
                    </p>

                </div>

            </div>

            <div class="timeline-item
                {{ $order->delivered_at ? 'active' : '' }}">

                <div class="dot"></div>

                <div>

                    <strong>
                        Đã giao hàng
                    </strong>

                    <p>
                        {{ optional($order->delivered_at)->format('d/m/Y H:i') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    {{-- SHIPPING --}}
    <div class="detail-card">

        <h3>Thông tin nhận hàng</h3>

        <div class="shipping-grid">

            <div>
                <strong>Người nhận</strong>
                <p>{{ $order->shipping_name }}</p>
            </div>

            <div>
                <strong>Số điện thoại</strong>
                <p>{{ $order->shipping_phone }}</p>
            </div>

            <div>
                <strong>Email</strong>
                <p>{{ $order->shipping_email }}</p>
            </div>

            <div>
                <strong>Địa chỉ</strong>

                <p>
                    {{ $order->shipping_address }}
                </p>

            </div>

        </div>

    </div>

    {{-- PRODUCTS --}}
    <div class="detail-card">

        <h3>Sản phẩm</h3>

        @foreach ($order->items as $item)
            <div class="detail-product">

                <img src="{{ asset('uploads/variant/' . $item->product_image) }}" alt="">

                <div class="detail-product-info">

                    <h4>
                        {{ $item->product_name }}
                    </h4>

                    <p>
                        {{ $item->variant_name }}
                    </p>

                    <span>
                        {{ number_format($item->final_price) }}đ
                        ×
                        {{ $item->quantity }}
                    </span>

                </div>

                <div class="detail-product-total">

                    {{ number_format($item->total) }}đ

                </div>

            </div>
        @endforeach

    </div>

    {{-- PAYMENT --}}
    <div class="detail-card">

        <h3>Thanh toán</h3>

        <div class="payment-summary">

            <div>
                <span>Tạm tính</span>

                <span>
                    {{ number_format($order->subtotal) }}đ
                </span>
            </div>

            <div>
                <span>Giảm giá</span>

                <span>
                    -{{ number_format($order->discount_amount) }}đ
                </span>
            </div>

            <div>
                <span>Phí vận chuyển</span>

                <span>
                    {{ number_format($order->shipping_fee) }}đ
                </span>
            </div>

            <div class="grand-total">

                <span>Tổng thanh toán</span>

                <span>
                    {{ number_format($order->grand_total) }}đ
                </span>

            </div>

            @if ($order->canCustomerCancel())
                <button class="cancel-order-btn" data-id="{{ $order->id }}">
                    Hủy đơn hàng
                </button>
            @endif

        </div>

    </div>

</div>
