<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lifestyle - Trang bán hàng thời trang trực tuyến</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @include('partials.seo')

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/auth/vendors/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/auth/assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/auth/assets/icon/icofont/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/auth/vendors/toastr/toastr.min.css') }}" />
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/auth/assets/css/style.css') }}">

    @stack('styles-lib')
    @stack('styles')
</head>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->

    @yield('content')

    <!-- Required Jquery -->
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/jquery/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/popper.js/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/jquery-slimscroll/js/jquery.slimscroll.js') }}">
    </script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/modernizr/js/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/modernizr/js/css-scrollbars.js') }}"></script>
    <!-- i18next.min.js -->
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/i18next/js/i18next.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('backend/auth/vendors/i18next-xhr-backend/js/i18nextXHRBackend.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('backend/auth/vendors/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/jquery-i18next/js/jquery-i18next.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('backend/auth/assets/js/common-pages.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/auth/vendors/toastr/config.js') }}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>

    {!! Toastr::message() !!}

    @stack('scripts-lib')
    @stack('scripts')
</body>

</html>
