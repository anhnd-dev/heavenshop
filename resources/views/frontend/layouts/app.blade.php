<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <title>Lifestyle - Trang bán hàng thời trang trực tuyến</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="ThemeZaa" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('frontend.layouts.partials.seo')

    <link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />

    <link rel="stylesheet" href="{{ asset('frontend/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/icon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/main.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/shopping-cart.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/auth/style.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/vendors/toastr/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/sweetalert/sweetalert.css') }}">

    <style>
        .checkout-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            list-style: none;
            padding: 0;
            margin-top: 32px !important;
        }

        .step a {
            pointer-events: none;
            text-align: center;
            color: rgba(0, 0, 0, 0.5)
        }

        .step a.active {
            pointer-events: auto;
            cursor: pointer;
            color: rgba(0, 0, 0, 1)
        }

        .step-content {
            margin-top: 60px;
        }

        .shipping-address-cart__body {
            border: 1px solid #e5e5e5;
            border-left: none;
            padding: 12px 12px 12px 16px;
            position: relative;
        }

        .shipping-address-cart__body:before {
            content: "";
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            background-image: url('/frontend/images/border-checkout.png')
        }

        .shipping-address-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shipping-address-item__header span:nth-child(1) {
            font-weight: 700;
        }

        .shipping-address-item__content {
            font-size: 16px;
            line-height: 24px;
            color: #000000;
        }

        .shipping-address-item__content p {
            margin-bottom: 0;
        }

        .progress-container {
            width: 100%;
            background-color: #d0d0d0;
            border-radius: 25px;
            text-align: center;
            position: relative;
        }

        .btn-custom {
            border: 1px solid #000;
            font-size: 14px;
        }

        .btn-custom:hover {
            border: 1px solid #000;
            color: #000;
            background: #ededed;
        }

        .progress-bar {
            height: 15px;
            background-color: #000000;
            border-radius: 25px;
            transition: width 0.4s;
        }

        #progress-text {
            position: absolute;
            width: 100%;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            color: #fff;
            font-size: 10px;
        }

        #checkout-modal {
            height: 80vh;
            overflow-x: hidden;
            overflow-y: scroll;
        }

        #checkout-modal::-webkit-scrollbar {
            width: 8px;
            background: transparent;
            border-radius: 10px;
        }

        #checkout-modal::-webkit-scrollbar-track {
            border-radius: 10px;
        }

        #checkout-modal::-webkit-scrollbar-thumb {
            background: #fff;
            border-radius: 10px;
            width: 8px;
        }

        #checkout-modal::-webkit-scrollbar-thumb:hover {
            background: #8F8F8F;
        }

        .mini-cart-remove {
            cursor: pointer;
            font-size: 14px;
            color: #999;
            transition: .2s;
        }

        .mini-cart-remove:hover {
            color: #ef4444;
        }
    </style>

    @stack('styles')
    @stack('lib-styles')
</head>

<body data-mobile-nav-style="classic" data-auth="{{ Auth::guard('customer')->check() ? 1 : 0 }}">

    @include('frontend.layouts.partials.header')

    @yield('content')

    @if (!request()->routeIs('cart.index'))
        @include('frontend.layouts.partials.footer')
    @else
    @endif

    @include('frontend.layouts.partials.auth-modal')

    @include('frontend.layouts.partials.cookie')

    @include('frontend.layouts.partials.social')

    @include('frontend.layouts.partials.scroll_to_top')

    @include('frontend.layouts.partials.config')

    <script>
        window.accountRedirect =
            @json(session('redirect_after_login'));

        window.autoOpenLoginModal =
            @json(session('open_login_modal', false));
    </script>

    <script data-cfasync="false" src="{{ asset('frontend/js/email-decode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/vendors.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/main.js') }}"></script>

    <script src="{{ asset('backend/vendors/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/toastr/config.js') }}"></script>
    <script src="{{ asset('backend/vendors/sweetalert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('frontend/js/header-cart.js') }}"></script>
    <script src="{{ asset('frontend/js/auth-modal.js') }}"></script>
    <script src="{{ asset('frontend/js/location.js') }}"></script>

    {!! Toastr::message() !!}

    @stack('lib-scripts')
    @stack('scripts')

</body>

</html>
