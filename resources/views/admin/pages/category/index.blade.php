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
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.category'),
            'url' => route('admin.category.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="rows">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addCategory',
                        'modalId' => 'addCategoryModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'category_datatable',
                    
                        'columns' => [
                            __('admin.category.category_name'),
                            __('admin.common.slug'),
                            __('admin.category.category_type'),
                            __('admin.category.parent'),
                            __('admin.category.level'),
                            __('admin.category.category_image'),
                            __('admin.category.status'),
                            __('admin.common.action'),
                        ],
                    ])
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Category Modal -->
    @include('admin.components.modal', [
        'id' => 'addCategoryModal',
        'size' => 'modal-lg',
        'title' => __('admin.category.add_category'),
        'formId' => 'add_category_form',
        'enctype' => 'multipart/form-data',
        'submitId' => 'add_category_btn',
        'submitText' => __('admin.common.add'),
        'body' => view('admin.pages.category.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Category Modal -->
    @include('admin.components.modal', [
        'id' => 'editCategoryModal',
        'size' => 'modal-lg',
        'title' => __('admin.category.edit_category'),
        'formId' => 'edit_category_form',
        'method' => 'PUT',
        'enctype' => 'multipart/form-data',
        'hiddenFields' => [
            [
                'name' => 'category_id',
                'id' => 'category_id',
            ],
        ],
        'submitId' => 'edit_category_btn',
        'submitText' => __('admin.common.update'),
        'body' => view('admin.pages.category.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.category.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('scripts')
    <!-- Core: Helpers -->
    <script src="{{ asset('backend/assets/js/core/helpers/slug.js') }}"></script>

    <!-- Core: Plugins -->
    <script src="{{ asset('backend/assets/js/core/plugins/datatable.language.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/plugins/select2.js') }}"></script>

    <!-- Core: Services -->
    <script src="{{ asset('backend/assets/js/core/services/crud.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/force-delete.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/restore.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/category/index.js') }}"></script>
@endpush
