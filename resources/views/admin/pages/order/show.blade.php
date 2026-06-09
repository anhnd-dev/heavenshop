@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/order/style.css') }}">
@endpush

@section('content')
    <div class="container-fluid site-width" style="padding-top: 42px">

        <div class="card order-card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div>

                        <div class="d-flex align-items-center mb-2">

                            <h3 class="mb-0 mr-3">

                                #{{ $order->order_code }}

                            </h3>

                            @php
                                $statusClass = match ($order->order_status) {
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'shipping' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    'returned' => 'secondary',
                                    default => 'dark',
                                };
                            @endphp

                            <span class="badge badge-{{ $statusClass }} px-3 py-2">

                                {{ $order->status_label }}

                            </span>

                        </div>

                        <div class="text-muted">

                            <i class="far fa-calendar-alt mr-1"></i>

                            {{ $order->created_at->format('d/m/Y H:i') }}

                        </div>

                    </div>

                    <div class="mt-3 mt-md-0">

                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary">

                            <i class="fas fa-arrow-left"></i>

                            Danh sách

                        </a>

                        <a href="{{ route('admin.order.print', $order->id) }}" target="_blank" class="btn btn-primary ml-2">

                            <i class="fas fa-print"></i>

                            In hóa đơn

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-8">

                @include('admin.pages.order.partials.items')

            </div>

            <div class="col-lg-4">

                @include('admin.pages.order.partials.customer')

                @include('admin.pages.order.partials.shipping')

                @include('admin.pages.order.partials.status')

            </div>

        </div>

        <div class="row mt-4">

            <div class="col-12">

                <div class="card order-card">

                    <div class="card-header">

                        Lịch sử đơn hàng

                    </div>

                    <div class="card-body">

                        <ul class="order-timeline">

                            {{-- CREATED --}}
                            <li class="done">

                                <div class="timeline-title">

                                    Đơn hàng được tạo

                                </div>

                                <div class="timeline-time">

                                    {{ $order->created_at?->format('d/m/Y H:i') }}

                                </div>

                            </li>

                            {{-- PAYMENT --}}
                            @if ($order->paid_at)
                                <li class="done">

                                    <div class="timeline-title">

                                        Thanh toán thành công

                                    </div>

                                    <div class="timeline-time">

                                        {{ $order->paid_at?->format('d/m/Y H:i') }}

                                    </div>

                                </li>
                            @endif

                            {{-- CONFIRMED --}}
                            <li class="{{ $order->confirmed_at ? 'done' : '' }}">

                                <div class="timeline-title">

                                    Đơn hàng được xác nhận

                                </div>

                                <div class="timeline-time">

                                    {{ $order->confirmed_at?->format('d/m/Y H:i') }}

                                </div>

                            </li>

                            {{-- SHIPPING --}}
                            <li class="{{ $order->shipped_at ? 'done' : '' }}">

                                <div class="timeline-title">

                                    Đơn hàng đang giao

                                </div>

                                <div class="timeline-time">

                                    {{ $order->shipped_at?->format('d/m/Y H:i') }}

                                </div>

                            </li>

                            {{-- DELIVERED --}}
                            <li class="{{ $order->delivered_at ? 'done' : '' }}">

                                <div class="timeline-title">

                                    Giao hàng thành công

                                </div>

                                <div class="timeline-time">

                                    {{ $order->delivered_at?->format('d/m/Y H:i') }}

                                </div>

                            </li>

                            {{-- RETURNED --}}
                            @if ($order->returned_at)
                                <li class="returned">

                                    <div class="timeline-title">

                                        Khách hàng trả hàng

                                    </div>

                                    <div class="timeline-time">

                                        {{ $order->returned_at?->format('d/m/Y H:i') }}

                                    </div>

                                </li>
                            @endif

                            {{-- REFUNDED --}}
                            @if ($order->refunded_at)
                                <li class="returned">

                                    <div class="timeline-title">

                                        Đã hoàn tiền

                                    </div>

                                    <div class="timeline-time">

                                        {{ $order->refunded_at?->format('d/m/Y H:i') }}

                                    </div>

                                </li>
                            @endif

                            {{-- CANCELLED --}}
                            @if ($order->cancelled_at)
                                <li class="cancelled">

                                    <div class="timeline-title">

                                        Đơn hàng đã bị hủy

                                    </div>

                                    <div class="timeline-time">

                                        {{ $order->cancelled_at?->format('d/m/Y H:i') }}

                                    </div>

                                </li>
                            @endif

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@include('admin.pages.order.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('scripts')
    <!-- Core: Plugins -->
    <script src="{{ asset('backend/assets/js/core/plugins/datatable.language.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/plugins/select2.js') }}"></script>

    <!-- Core: Services -->
    <script src="{{ asset('backend/assets/js/core/services/crud.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/force-delete.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/restore.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/order_detail/index.js') }}"></script>
@endpush
