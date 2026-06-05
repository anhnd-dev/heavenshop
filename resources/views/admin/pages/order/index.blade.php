@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid site-width">

        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.order'),
            'url' => route('admin.order.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header justify-content-between align-items-center d-flex">

                        <div>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">

                                <i class="fas fa-align-justify"></i>
                            </button>

                            <div class="dropdown-menu p-0">

                                <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">

                                    <i class="fab fa-cloudversify"></i>
                                    {{ __('admin.action.restore_all') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">

                                    <i class="fas fa-trash-alt"></i>
                                    {{ __('admin.action.delete_temps') }}
                                </a>

                                <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)"
                                    style="display: none">

                                    <i class="fas fa-trash-alt"></i>
                                    {{ __('admin.action.delete_permanently') }}
                                </a>

                            </div>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">

                            <input type="checkbox" class="custom-control-input" name="include_trashed"
                                id="includeTrashedCheckbox">

                            <label class="custom-control-label" for="includeTrashedCheckbox" style="padding-top: 2px">

                                {{ __('admin.action.trash_record') }}
                            </label>

                        </div>

                    </div>

                    @include('admin.components.datatable', [
                        'tableId' => 'order_datatable',
                    
                        'columns' => [
                            __('admin.order.code'),
                            __('admin.order.customer'),
                            __('admin.order.phone'),
                            __('admin.order.total_price'),
                            __('admin.order.payment_status'),
                            __('admin.order.order_status'),
                            __('admin.order.date'),
                            __('admin.common.action'),
                        ],
                    ])

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
    <script src="{{ asset('backend/assets/js/pages/order/index.js') }}"></script>
@endpush
