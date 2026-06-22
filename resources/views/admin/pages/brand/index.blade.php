@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.brand'),
            'url' => route('admin.brand.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addBrand',
                        'modalId' => 'addBrandModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'brand_datatable',
                    
                        'columns' => [
                            __('admin.brand.brand_name'),
                            __('admin.common.slug'),
                            __('admin.brand.brand_image'),
                            __('admin.brand.status'),
                            __('admin.common.action'),
                        ],
                    ])
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Brand Modal -->
    @include('admin.components.modal', [
        'id' => 'addBrandModal',
        'title' => __('admin.brand.add_brand'),
        'formId' => 'add_brand_form',
        'enctype' => 'multipart/form-data',
        'submitId' => 'add_brand_btn',
        'submitText' => __('admin.action.add'),
        'body' => view('admin.pages.brand.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Brand Modal -->
    @include('admin.components.modal', [
        'id' => 'editBrandModal',
        'title' => __('admin.brand.edit_brand'),
        'formId' => 'edit_brand_form',
        'method' => 'PUT',
        'enctype' => 'multipart/form-data',
        'hiddenFields' => [
            [
                'name' => 'brand_id',
                'id' => 'brand_id',
            ],
        ],
        'submitId' => 'edit_brand_btn',
        'submitText' => __('admin.action.update'),
        'body' => view('admin.pages.brand.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.brand.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('scripts')
    <!-- Core: Helpers -->
    <script src="{{ asset('backend/assets/js/core/helpers/slug.js') }}"></script>

    <!-- Core: Plugins -->
    <script src="{{ asset('backend/assets/js/core/plugins/datatable.language.js') }}"></script>

    <!-- Core: Services -->
    <script src="{{ asset('backend/assets/js/core/services/crud.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/brand/index.js') }}"></script>
@endpush
