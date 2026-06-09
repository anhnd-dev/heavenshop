@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard/revenue.css') }}">
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

            <div class="col-md-4 mb-4">
                <div class="card dashboard-kpi-card revenue">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-money-bag"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Doanh thu tháng này
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($growthRate['current']) }} đ
                                </h3>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card dashboard-kpi-card previous">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-chart-line"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Doanh thu tháng trước
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($growthRate['previous']) }} đ
                                </h3>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card dashboard-kpi-card growth">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-chart-growth"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tăng trưởng
                                </span>

                                <h3 class="dashboard-kpi-value">

                                    @if ($growthRate['growth'] >= 0)
                                        +{{ $growthRate['growth'] }}%
                                    @else
                                        {{ $growthRate['growth'] }}%
                                    @endif

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="card dashboard-chart-card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Doanh thu 30 ngày gần nhất
                </h5>
            </div>

            <div class="card-body">

                <div class="chart-wrapper">
                    <canvas id="revenueByDayChart"></canvas>
                </div>

            </div>

        </div>

        <div class="card dashboard-chart-card">

            <div class="card-header">
                <h5 class="mb-0">
                    Doanh thu 12 tháng gần nhất
                </h5>
            </div>

            <div class="card-body">

                <div class="chart-wrapper">
                    <canvas id="revenueByMonthChart"></canvas>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.revenueByDay =
            @json($revenueByDay);

        window.revenueByMonth =
            @json($revenueByMonth);
    </script>

    <script src="{{ asset('backend/assets/js/pages/dashboard/revenue.js') }}"></script>
@endpush
