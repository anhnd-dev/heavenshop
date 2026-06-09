@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard/customers.css') }}">
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
                <div class="card dashboard-kpi-card customers">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-users-social"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tổng khách hàng
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($customerSummary['totalCustomers']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card new-customers">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-user-alt-3"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Khách mới tháng này
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($customerSummary['newCustomers']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card returning">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-refresh"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Khách quay lại
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($customerSummary['returningCustomers']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card average-value">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-money-bag"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Giá trị KH trung bình
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($customerSummary['averageCustomerValue']) }}
                                    đ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Top khách hàng chi tiêu
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Số đơn</th>
                            <th>Chi tiêu</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($topSpenders as $item)
                            <tr>
                                <td>
                                    {{ $item->customer->name }}
                                </td>

                                <td>
                                    {{ $item->total_orders }}
                                </td>

                                <td>
                                    <span class="badge badge-success">
                                        {{ number_format($item->spending) }} đ
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card dashboard-chart-card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Tăng trưởng khách hàng
                </h5>
            </div>

            <div class="card-body">

                <div class="chart-wrapper">
                    <canvas id="customerGrowthChart"></canvas>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.customerGrowth =
            @json($customerGrowth);
    </script>

    <script src="{{ asset('backend/assets/js/pages/dashboard/customers.js') }}"></script>
@endpush
