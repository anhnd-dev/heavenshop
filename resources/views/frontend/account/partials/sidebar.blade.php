<div class="account-sidebar">

    <div class="sidebar-menu">

        <a href="{{ route('account.profile') }}"
            class="sidebar-item {{ request()->routeIs('account.profile') ? 'active' : '' }}">

            <div class="sidebar-left">

                <div class="sidebar-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <span class="sidebar-title">
                    Thông tin tài khoản
                </span>

            </div>

            <i class="fa-solid fa-arrow-right sidebar-arrow"></i>

        </a>

        <a href="{{ route('account.orders') }}"
            class="sidebar-item {{ request()->routeIs('account.orders*') ? 'active' : '' }}">

            <div class="sidebar-left">

                <div class="sidebar-icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>

                <span class="sidebar-title">
                    Lịch sử đơn hàng
                </span>

            </div>

            <i class="fa-solid fa-arrow-right sidebar-arrow"></i>

        </a>

        <a href="{{ route('account.vouchers') }}"
            class="sidebar-item {{ request()->routeIs('account.vouchers*') ? 'active' : '' }}">

            <div class="sidebar-left">

                <div class="sidebar-icon">
                    <i class="fa-solid fa-percent"></i>
                </div>

                <span class="sidebar-title">
                    Ví Voucher
                </span>

            </div>

            <i class="fa-solid fa-arrow-right sidebar-arrow"></i>

        </a>

        <a href="{{ route('account.addresses') }}"
            class="sidebar-item {{ request()->routeIs('account.addresses*') ? 'active' : '' }}">

            <div class="sidebar-left">

                <div class="sidebar-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>

                <span class="sidebar-title">
                    Sổ địa chỉ
                </span>

            </div>

            <i class="fa-solid fa-arrow-right sidebar-arrow"></i>

        </a>

        <form action="{{ route('account.logout') }}" method="POST">

            @csrf

            <button type="submit" class="sidebar-item logout-btn">

                <div class="sidebar-left">

                    <div class="sidebar-icon">
                        <i class="fa-solid fa-power-off"></i>
                    </div>

                    <span class="sidebar-title">
                        Đăng xuất
                    </span>

                </div>

                <i class="fa-solid fa-arrow-right sidebar-arrow"></i>

            </button>

        </form>

    </div>

</div>
