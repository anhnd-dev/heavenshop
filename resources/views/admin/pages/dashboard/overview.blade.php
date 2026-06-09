@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard/overview.css') }}">
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

        <div class="row">

            <div class="col-md-3 mb-4">
                <div class="card dashboard-kpi-card revenue">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-money-bag"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Doanh thu
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($kpis['totalRevenue']) }} đ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card dashboard-kpi-card orders">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-box"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Đơn hàng
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($kpis['totalOrders']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card dashboard-kpi-card customers">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-users-social"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Khách hàng
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($kpis['totalCustomers']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card dashboard-kpi-card average">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-shopping-cart"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Giá trị đơn TB
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($kpis['avgOrderValue']) }} đ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">

            <div class="col-lg-12">

                <div class="card dashboard-chart-card">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Doanh thu 12 tháng gần nhất
                        </h5>

                    </div>

                    <div class="card-body chart-wrapper">

                        <canvas id="monthlyRevenueChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.monthlyRevenue = @json($monthlyRevenue);
    </script>

    <script src="{{ asset('backend/assets/js/pages/dashboard/overview.js') }}"></script>
@endpush
