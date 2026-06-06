@extends('frontend.account.index')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/account/order.css') }}">
@endpush

@section('account-content')

    <div class="orders-page">

        {{-- Header --}}
        <div class="account-section-header">

            <h2>Lịch sử đơn hàng</h2>

            <p>
                Theo dõi trạng thái và xem chi tiết các đơn hàng của bạn.
            </p>

        </div>

        {{-- Stats --}}
        <div class="order-stat-grid">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <strong>{{ $totalOrders }}</strong>
                </div>

                <div>
                    <span>Tổng đơn hàng</span>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon pending">
                    <i class="fa-solid fa-clock"></i>
                    <strong>{{ $pendingOrders }}</strong>
                </div>

                <div>
                    <span>Chờ xác nhận</span>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon shipping">
                    <i class="fa-solid fa-truck"></i>
                    <strong>{{ $shippingOrders }}</strong>
                </div>

                <div>
                    <span>Đang giao</span>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon delivered">
                    <i class="fa-solid fa-circle-check"></i>
                    <strong>{{ $deliveredOrders }}</strong>
                </div>

                <div>
                    <span>Đã giao</span>
                </div>

            </div>

        </div>

        {{-- Filter --}}
        <div class="order-filter">

            <form method="GET">

                <div class="filter-search">

                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập mã đơn hàng...">

                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                </div>

                <div class="order-tabs">

                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
                        class="{{ request('status') == null ? 'active' : '' }}">
                        Tất cả
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                        class="{{ request('status') == 'pending' ? 'active' : '' }}">
                        Chờ xác nhận
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}"
                        class="{{ request('status') == 'confirmed' ? 'active' : '' }}">
                        Đã xác nhận
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'shipping']) }}"
                        class="{{ request('status') == 'shipping' ? 'active' : '' }}">
                        Đang giao
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'delivered']) }}"
                        class="{{ request('status') == 'delivered' ? 'active' : '' }}">
                        Đã giao
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
                        class="{{ request('status') == 'cancelled' ? 'active' : '' }}">
                        Đã hủy
                    </a>

                </div>

                <div class="filter-bottom">

                    <input type="date" name="from_date" value="{{ request('from_date') }}">

                    <input type="date" name="to_date" value="{{ request('to_date') }}">

                    <select name="range">

                        <option value="">
                            Khoảng thời gian
                        </option>

                        <option value="today" @selected(request('range') == 'today')>
                            Hôm nay
                        </option>

                        <option value="3days" @selected(request('range') == '3days')>
                            3 ngày
                        </option>

                        <option value="week" @selected(request('range') == 'week')>
                            1 tuần
                        </option>

                        <option value="month" @selected(request('range') == 'month')>
                            1 tháng
                        </option>

                    </select>

                    <button type="submit">
                        Lọc
                    </button>

                </div>

            </form>

        </div>

        {{-- Orders --}}
        @forelse($orders as $order)
            @php
                $firstItem = $order->items->first();
            @endphp

            <div class="order-card {{ $highlight == $order->order_code ? 'highlight' : '' }}"
                data-order-code="{{ $order->order_code }}">

                {{-- Header --}}
                <div class="order-card-header">

                    <div>

                        <div class="order-code">
                            #{{ $order->order_code }}
                        </div>

                        <div class="order-meta">

                            {{ $order->created_at->format('d/m/Y H:i') }}

                            <span>•</span>

                            {{ strtoupper($order->payment_method) }}

                        </div>

                    </div>

                    <div class="order-status {{ $order->order_status }}">

                        {{ $order->status_label }}

                    </div>

                </div>

                {{-- Product --}}
                @if ($firstItem)
                    <div class="order-preview">

                        <img src="{{ asset('uploads/variant/' . $firstItem->product_image) }}"
                            alt="{{ $firstItem->product_name }}">

                        <div class="order-preview-info">

                            <h4>
                                {{ $firstItem->product_name }}
                            </h4>

                            @if ($firstItem->variant_name)
                                <p>
                                    {{ $firstItem->variant_name }}
                                </p>
                            @endif

                            <span>
                                x{{ $firstItem->quantity }}
                            </span>

                            @if ($order->items_count > 1)
                                <small>
                                    + {{ $order->items_count - 1 }}
                                    sản phẩm khác
                                </small>
                            @endif

                        </div>

                    </div>
                @endif

                {{-- Footer --}}
                <div class="order-card-footer">

                    <div>

                        <div class="order-total-label">
                            Tổng thanh toán
                        </div>

                        <div class="order-total">
                            {{ number_format($order->grand_total) }}đ
                        </div>

                    </div>

                    <button class="view-order-btn" data-id="{{ $order->id }}">

                        Chi tiết đơn hàng

                    </button>

                </div>

            </div>

        @empty

            <div class="empty-orders">

                <i class="fa-solid fa-box-open"></i>

                <h3>
                    Chưa có đơn hàng nào
                </h3>

                <p>
                    Hãy khám phá sản phẩm và bắt đầu mua sắm.
                </p>

            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="custom-pagination">

                {{ $orders->links() }}

            </div>
        @endif

    </div>

    {{-- Modal --}}
    <div id="orderDetailModal" class="account-modal-overlay">

        <div class="account-modal order-detail-modal">

            <button type="button" class="modal-close">
                ×
            </button>

            <div id="orderDetailContent"></div>

        </div>

    </div>

    <div id="cancelOrderModal" class="account-modal-overlay">

        <div class="account-modal cancel-order-modal">

            <button type="button" class="modal-close">
                ×
            </button>

            <h3>Hủy đơn hàng</h3>

            <p class="cancel-desc">
                Vui lòng cho chúng tôi biết lý do bạn muốn hủy đơn hàng.
            </p>

            <form id="cancelOrderForm">

                <input type="hidden" id="cancelOrderId" name="order_id">

                <div class="cancel-reasons">

                    <label>
                        <input type="radio" name="cancel_reason" value="Đặt nhầm sản phẩm">

                        <span>Đặt nhầm sản phẩm</span>
                    </label>

                    <label>
                        <input type="radio" name="cancel_reason" value="Muốn thay đổi địa chỉ">

                        <span>Muốn thay đổi địa chỉ</span>
                    </label>

                    <label>
                        <input type="radio" name="cancel_reason" value="Thời gian giao quá lâu">

                        <span>Thời gian giao quá lâu</span>
                    </label>

                    <label>
                        <input type="radio" name="cancel_reason" value="Tìm được giá tốt hơn">

                        <span>Tìm được giá tốt hơn</span>
                    </label>

                    <label>
                        <input type="radio" name="cancel_reason" value="Lý do khác">

                        <span>Lý do khác</span>
                    </label>

                </div>

                <textarea id="otherReason" name="other_reason" placeholder="Nhập lý do..." style="display:none">
            </textarea>

                <button type="submit" class="cancel-submit-btn">

                    Xác nhận hủy đơn

                </button>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const highlight = "{{ request('highlight') }}";

            if (highlight) {
                const el = document.querySelector(`[data-order-code="${highlight}"]`);

                if (el) {
                    el.classList.add("highlight");

                    el.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });

                    setTimeout(() => {
                        el.classList.remove("highlight");
                    }, 6000);
                }
            }
        });
    </script>
@endpush
