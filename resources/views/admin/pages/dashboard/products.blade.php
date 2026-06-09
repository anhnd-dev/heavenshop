@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard/products.css') }}">
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
                <div class="card dashboard-kpi-card sold">

                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-cart-alt"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Tổng sản phẩm đã bán
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($productSummary['totalSold']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card revenue">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-money-bag"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Doanh thu sản phẩm
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($productSummary['totalRevenue']) }} đ
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card products">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-box"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    Sản phẩm đã bán
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ number_format($productSummary['totalProducts']) }}
                                </h3>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-kpi-card average">
                    <div class="card-body">

                        <div class="dashboard-kpi-wrapper">

                            <div class="dashboard-kpi-icon">
                                <i class="icofont-chart-bar-graph"></i>
                            </div>

                            <div>

                                <span class="dashboard-kpi-title">
                                    SP / Đơn hàng
                                </span>

                                <h3 class="dashboard-kpi-value">
                                    {{ $productSummary['avgOrderQuantity'] }}
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
                    Doanh thu hàng đầu
                </h5>

            </div>

            <div class="card-body chart-wrapper">

                <canvas id="topRevenueChart"></canvas>

            </div>

        </div>

        <div class="card dashboard-chart-card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Top 10 biến thể bán chạy
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Màu</th>
                            <th>Size</th>
                            <th>Đã bán</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($topVariants as $variant)
                            <tr>
                                <td>
                                    {{ $variant->product_name }}
                                </td>

                                <td>
                                    {{ $variant->color_name }}
                                </td>

                                <td>
                                    {{ $variant->size_name }}
                                </td>

                                <td>
                                    {{ number_format($variant->sold) }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="row mt-4 mb-4">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Top 10 bán chạy
                        </h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đã bán</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($bestSellers as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name }}
                                        </td>

                                        <td>
                                            <span class="badge badge-success">
                                                {{ number_format($item->sold) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Sản phẩm chưa từng bán
                        </h5>
                    </div>

                    <div class="card-body">

                        <table class="table">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>SKU</th>
                                    <th>Màu sắc</th>
                                    <th>Kích thước</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($unsoldVariants as $variant)
                                    <tr>
                                        <td>{{ $variant->id }}</td>

                                        <td>{{ $variant->product->name }}</td>

                                        <td>{{ $variant->sku }}</td>

                                        <td>
                                            <div class="variant-color"
                                                style="
                                                background:
                                                {{ $variant->color->code }}
                                            ">
                                            </div>
                                        </td>

                                        <td>{{ $variant->size->name }}</td>

                                        <td>
                                            {{ $variant->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        window.topRevenueProducts =
            @json($topRevenueProducts);
    </script>

    <script src="{{ asset('backend/assets/js/pages/dashboard/products.js') }}"></script>
@endpush
