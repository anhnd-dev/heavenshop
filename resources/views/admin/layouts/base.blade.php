<!DOCTYPE html>
<html lang="en">
<!-- START: Head-->

<head>
    <title>Lifestyle - Trang bán hàng thời trang trực tuyến</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('admin.layouts.partials.seo')

    <!-- START: Template CSS-->
    <link rel="stylesheet" href="{{ asset('backend/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/flags-icon/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/materialdesign-webfont/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/fontawesome-free-6.5.2-web/css/fontawesome.min.css') }}">
    <!-- END Template CSS-->

    <!-- Bootstrap Tags Input CSS -->
    <link rel="stylesheet" href="{{ asset('backend/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">

    <!-- START: Page CSS-->
    <link rel="stylesheet" href="{{ asset('backend/vendors/chartjs/Chart.min.css') }}">
    <!-- END: Page CSS-->

    <!-- START: Page CSS-->
    <link rel="stylesheet" href="{{ asset('backend/vendors/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/weather-icons/css/pe-icon-set-weather.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/chartjs/Chart.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/starrr/starrr.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/toastr/toastr.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('backend/vendors/sweetalert/sweetalert.css') }}"> --}}
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- END: Page CSS-->

    <!-- START: Custom CSS-->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
    <!-- END: Custom CSS-->

    @stack('styles-lib')
    @stack('styles')
</head>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">

    <!-- START: Pre Loader-->
    <div class="se-pre-con">
        <div class="loader"></div>
    </div>
    <!-- END: Pre Loader-->

    <!-- START: Header-->
    @include('admin.layouts.partials.header')
    <!-- END: Header-->

    <!-- START: Main Menu-->
    @include('admin.layouts.partials.sidebar')
    <!-- END: Main Menu-->

    <!-- START: Main Content-->
    <main>
        @yield('content')
    </main>
    <!-- END: Content-->

    <!-- START: Back to top-->
    <a href="#" class="scrollup text-center">
        <i class="icon-arrow-up"></i>
    </a>
    <!-- END: Back to top-->

    {{-- <div class="modal fade" id="lockModal" tabindex="-1" role="dialog" aria-labelledby="lockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Màn hình khóa</h5>
                </div>
                <div class="modal-body">
                    <input class="form-control" type="password" id="passwordInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="checkPassword">Submit</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div>
        <label for="minutesInput">Số phút hiển thị modal:</label>
        <input type="number" id="minutesInput" min="1" value="2">
        <button id="saveSettings">Lưu cài đặt</button>
    </div>

    <input type="checkbox" id="disableLock" style="margin-left: 500px"> Tắt màn hình khóa --}}

    @include('admin.layouts.partials.translations')

    <!-- START: Template JS-->
    <script src="{{ asset('backend/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/moment/moment.js') }}"></script>
    <script src="{{ asset('backend/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- END: Template JS-->

    <!-- START: APP JS-->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    <!-- END: APP JS-->

    <!-- START: Page Vendor JS-->
    <script src="{{ asset('backend/vendors/fontawesome-free-6.5.2-web/js/all.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/morris/morris.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/starrr/starrr.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.canvaswrapper.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.colorhelpers.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.saturated.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.browser.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.drawSeries.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.uiConstants.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.legend.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('backend/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js') }}"></script>
    <script src="{{ asset('backend/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
    <script src="{{ asset('backend/vendors/apexcharts/apexcharts.min.js') }}"></script>

    <script src="{{ asset('backend/vendors/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/toastr/config.js') }}"></script>
    {{-- <script src="{{ asset('backend/vendors/sweetalert/sweetalert.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Tags Input JS -->
    <script src="{{ asset('backend/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- Page JS-->
    {{-- <script src="{{ asset('backend/assets/js/home.script.js') }}"></script> --}}
    {{-- <script src="{{ asset('backend/assets/js/lockscreen.js') }}"></script> --}}
    <!-- END: Page JS-->

    {!! Toastr::message() !!}

    @stack('scripts-lib')
    @stack('scripts')
</body>
<!-- END: Body-->

</html>
