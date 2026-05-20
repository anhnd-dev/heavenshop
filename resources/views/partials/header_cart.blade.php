<header class="header-with-topbar" style="height: 60px; border-bottom: 1px solid #ddd; box-shadow: 1px -1px 4px 1px #ddd;">
    <nav class="navbar navbar-expand-lg header-light bg-white disable-fixed center-logo" style="height: 60px">
        <div class="container-fluid">
            <div class="col-auto col-xxl-6 col-lg-6 menu-logo">
                <a href="{{ route('home') }}">
                    @if ($logoIcon)
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black) }}"
                            data-at2x="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black_2x) }}"
                            alt class="default-logo" />
                    @else
                        <img src="" alt="" class="default-logo" />
                    @endif

                </a>
            </div>

            <div class="col-auto col-xxl-6 col-lg-6 text-end">
                <div class="header-icon">
                    <div class="header-cart-icon icon me-15px">
                        <div class="header-cart dropdown">
                            <i class="fa fa-lock me-5px"
                                style="padding: 6px; color: #fff; background: #198055; border-radius: 50%; font-size: 14px;"></i><span
                                class="d-none d-xxl-inline-block"
                                style="margin-left: 4px; text-transform: uppercase; color: #198055; font-size: 16px;">Thanh
                                toán an toàn</span>
                        </div>
                    </div>
                    <div class="header-cart-icon icon">
                        <div class="header-cart dropdown">
                            <a  href="{{ route('shop') }}" class="d-none d-xxl-inline-block me-5px">
                                <span
                                    style="margin-left: 4px; text-transform: uppercase; font-size: 16px;">Tiếp tục mua sắm
                                </span>
                            </a>
                            <i class="fa fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
