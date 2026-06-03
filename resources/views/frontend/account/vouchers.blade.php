@extends('frontend.account.index')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/account/voucher.css') }}">
@endpush

@section('account-content')
    <div class="voucher-wrapper">

        <h2 class="title">Ví Voucher</h2>

        {{-- SUMMARY --}}
        <div class="summary">
            <span>Khả dụng: {{ count($available) }}</span>
            <span>Đã dùng: {{ count($used) }}</span>
            <span>Hết hạn: {{ count($expired) }}</span>
        </div>

        {{-- TAB --}}
        <div class="tabs">
            <button class="tab active" data-tab="available">Có thể dùng</button>
            <button class="tab" data-tab="used">Đã dùng</button>
            <button class="tab" data-tab="expired">Hết hạn</button>
        </div>

        {{-- SEARCH + GRID SWITCH --}}
        <div class="toolbar">

            <div class="search-box">
                <input type="text" id="searchVoucher" placeholder="Tìm mã voucher...">
            </div>

            <div class="layout-switch">
                <button class="layout-btn active" data-layout="1">I</button>
                <button class="layout-btn" data-layout="2">II</button>
                <button class="layout-btn" data-layout="3">III</button>
            </div>

        </div>

        {{-- AVAILABLE --}}
        <div class="tab-content active grid-2" id="available">

            @forelse($available as $item)
                <div class="voucher-card available">
                    <div class="left">
                        <div class="code">
                            {{ $item->coupon->code }}
                            <span class="badge available">Có thể dùng</span>
                        </div>

                        <div class="desc">
                            Giảm:
                            @if ($item->coupon->discount_type == 'percentage')
                                {{ $item->coupon->discount_value }}%
                            @else
                                {{ number_format($item->coupon->discount_value) }}đ
                            @endif
                        </div>

                        <div class="meta">
                            HSD: {{ $item->coupon->end_date ?? 'Không giới hạn' }}
                        </div>
                    </div>

                    <button class="btn-use" data-code="{{ $item->coupon->code }}">
                        Dùng ngay
                    </button>
                </div>
            @empty
                <p class="empty">Không có voucher khả dụng</p>
            @endforelse

        </div>

        {{-- USED --}}
        <div class="tab-content grid-2" id="used">

            @forelse($used as $item)
                <div class="voucher-card used">
                    <div class="left">
                        <div class="code">
                            {{ $item->coupon->code }}
                            <span class="badge used">Đã dùng</span>
                        </div>
                        <div class="desc">Voucher đã sử dụng</div>
                        <div class="meta">Order #{{ $item->order_id }}</div>
                    </div>
                </div>
            @empty
                <p class="empty">Chưa dùng voucher nào</p>
            @endforelse

        </div>

        {{-- EXPIRED --}}
        <div class="tab-content grid-2" id="expired">

            @forelse($expired as $item)
                <div class="voucher-card expired">
                    <div class="left">
                        <div class="code">
                            {{ $item->coupon->code }}
                            <span class="badge expired">Hết hạn</span>
                        </div>
                        <div class="desc">Voucher không còn hiệu lực</div>
                        <div class="meta">{{ $item->coupon->end_date }}</div>
                    </div>
                </div>
            @empty
                <p class="empty">Không có voucher hết hạn</p>
            @endforelse

        </div>

    </div>

    {{-- JS --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const tabs = document.querySelectorAll(".tab");
            const contents = document.querySelectorAll(".tab-content");
            const searchInput = document.getElementById("searchVoucher");
            const layoutBtns = document.querySelectorAll(".layout-btn");

            /* =========================
               TAB SWITCH
            ========================== */
            tabs.forEach(tab => {
                tab.addEventListener("click", function() {

                    tabs.forEach(t => t.classList.remove("active"));
                    this.classList.add("active");

                    const target = this.dataset.tab;

                    contents.forEach(c => {
                        c.classList.remove("active");
                        if (c.id === target) {
                            c.classList.add("active");
                        }
                    });

                    // reset search + layout
                    searchInput.value = "";
                    resetCards();
                });
            });

            /* =========================
               SEARCH (active tab only)
            ========================== */
            searchInput.addEventListener("input", function() {

                const keyword = this.value.toLowerCase();
                const activeTab = document.querySelector(".tab-content.active");

                activeTab.querySelectorAll(".voucher-card").forEach(card => {
                    const code = card.querySelector(".code").innerText.toLowerCase();
                    card.style.display = code.includes(keyword) ? "flex" : "none";
                });
            });

            function resetCards() {
                document.querySelectorAll(".voucher-card").forEach(card => {
                    card.style.display = "flex";
                });
            }

            /* =========================
               GRID SWITCH (I / II / III)
            ========================== */
            layoutBtns.forEach(btn => {
                btn.addEventListener("click", function() {

                    layoutBtns.forEach(b => b.classList.remove("active"));
                    this.classList.add("active");

                    const layout = this.dataset.layout;
                    const activeTab = document.querySelector(".tab-content.active");

                    activeTab.classList.remove("grid-1", "grid-2", "grid-3");
                    activeTab.classList.add("grid-" + layout);

                    localStorage.setItem("voucher_grid", layout);
                });
            });

            // restore grid
            const saved = localStorage.getItem("voucher_grid");
            if (saved) {
                const activeTab = document.querySelector(".tab-content.active");

                layoutBtns.forEach(b => b.classList.remove("active"));
                document.querySelector(`[data-layout="${saved}"]`)?.classList.add("active");

                activeTab.classList.add("grid-" + saved);
            }

            /* =========================
               USE VOUCHER (checkout hook)
            ========================== */
            document.querySelectorAll(".btn-use").forEach(btn => {
                btn.addEventListener("click", function() {

                    const code = this.dataset.code;

                    localStorage.setItem("selected_voucher", code);

                    alert("Đã chọn voucher: " + code);
                });
            });

        });
    </script>
@endsection
