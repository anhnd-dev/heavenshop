@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard/orders.css') }}">
@endpush

@section('content')
    <div class="dashboard container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12 mt-2 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Thống kê</h4>
                        <p>Welcome to liner admin panel</p>
                    </div>

                    <ol class="breadcrumb abg-transparent bg-none align-self-center m-0 p-0">
                        <li class="breadcrumb-item">
                            <i class="icofont-dashboard"></i>
                        </li>
                        <li class="breadcrumb-item">Thống kê</li>
                        <li class="breadcrumb-item active"><a href="">Tổng quan</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card dashboard-kpi-card total">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-cart-alt"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tổng đơn hàng
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($deliveryRate['total']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card delivered">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-check-circled"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Giao thành công
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($deliveryRate['delivered']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card cancelled">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-close-circled"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Đã huỷ
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($deliveryRate['cancelled']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card rate">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-chart-histogram-alt"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tỷ lệ giao thành công
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ $deliveryRate['rate'] }}%
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card dashboard-kpi-card confirm">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-clock-time"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Xác nhận → Giao vận
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ $processingTime['confirm_to_ship'] }} giờ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-kpi-card transport">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-delivery-time"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Giao vận → Thành công
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ $processingTime['ship_to_delivery'] }} giờ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-kpi-card processing">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-stopwatch"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tổng thời gian xử lý
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ $processingTime['confirm_to_delivery'] }} giờ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 mb-4">

            <div class="col-8">
                <div class="card dashboard-chart-card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Xu hướng đơn hàng 30 ngày gần nhất
                        </h5>
                    </div>

                    <div class="card-body chart-wrapper">

                        <canvas id="orderTrendChart"></canvas>

                    </div>

                </div>
            </div>

            <div class="col-4">
                <div class="card dashboard-chart-card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Trạng thái đơn hàng
                        </h5>
                    </div>

                    <div class="card-body chart-wrapper small">

                        <canvas id="orderStatusChart"></canvas>

                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.orderStatus =
            @json($orderStatus);

        window.orderTrend =
            @json($orderTrend);
    </script>

    <script src="{{ asset('backend/assets/js/pages/dashboard/orders.js') }}"></script>
@endpush
