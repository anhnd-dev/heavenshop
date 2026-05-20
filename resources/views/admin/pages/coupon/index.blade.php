@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
@endpush

@push('styles')
    <style>
        .dropdown-item.text-red {
            color: rgba(255, 0, 0, 0.7);
        }

        .dropdown-item.text-red:hover {
            color: rgba(255, 0, 0, 1);
        }

        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }

        .ck-content .image {
            /* Block images */
            max-width: 80%;
            margin: 20px auto;
        }

        input[disabled] {
            background-color: #ddd !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">

        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.coupon'),
            'url' => route('admin.coupon.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addCoupon',
                        'modalId' => 'addCouponModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'coupon_datatable',
                    
                        'columns' => [
                            __('admin.coupon.code'),
                            __('admin.coupon.discount_type'),
                            __('admin.coupon.discount_value'),
                            __('admin.coupon.min_order_amount'),
                            __('admin.coupon.max_discount_amount'),
                            __('admin.coupon.quantity'),
                            __('admin.coupon.start_date'),
                            __('admin.coupon.end_date'),
                            __('admin.coupon.status'),
                            __('admin.common.action'),
                        ],
                    ])
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Coupon Modal -->
    @include('admin.components.modal', [
        'id' => 'addCouponModal',
        'size' => 'modal-xl',
        'title' => __('admin.coupon.add_coupon'),
        'formId' => 'add_coupon_form',
        'submitId' => 'add_coupon_btn',
        'submitText' => __('admin.common.add'),
        'body' => view('admin.pages.coupon.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Coupon Modal -->
    @include('admin.components.modal', [
        'id' => 'editCouponModal',
        'size' => 'modal-xl',
        'title' => __('admin.coupon.edit_coupon'),
        'formId' => 'edit_coupon_form',
        'method' => 'PUT',
        'hiddenFields' => [
            [
                'name' => 'coupon_id',
                'id' => 'coupon_id',
            ],
        ],
        'submitId' => 'edit_coupon_btn',
        'submitText' => __('admin.common.update'),
        'body' => view('admin.pages.coupon.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.coupon.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor-setup.js') }}"></script>
@endpush

@push('scripts')
    <!-- Core: Plugins -->
    <script src="{{ asset('backend/assets/js/core/plugins/datatable.language.js') }}"></script>

    <!-- Core: Services -->
    <script src="{{ asset('backend/assets/js/core/services/crud.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/force-delete.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/restore.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/coupon/index.js') }}"></script>
@endpush
