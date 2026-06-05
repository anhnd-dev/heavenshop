@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
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
            'title' => __('admin.sidebar.color'),
            'url' => route('admin.color.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">

                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addColor',
                        'modalId' => 'addColorModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'color_datatable',
                    
                        'columns' => [
                            __('admin.color.color_name'),
                            __('admin.color.color_code'),
                            __('admin.color.status'),
                            __('admin.common.action'),
                        ],
                    ])

                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Color Modal -->
    @include('admin.components.modal', [
        'id' => 'addColorModal',
        'title' => __('admin.color.add_color'),
        'formId' => 'add_color_form',
        'submitId' => 'add_color_btn',
        'submitText' => __('admin.action.add'),
        'body' => view('admin.pages.color.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Brand Modal -->
    @include('admin.components.modal', [
        'id' => 'editColorModal',
        'title' => __('admin.color.edit_color'),
        'formId' => 'edit_color_form',
        'method' => 'PUT',
        'hiddenFields' => [
            [
                'name' => 'color_id',
                'id' => 'color_id',
            ],
        ],
        'submitId' => 'edit_color_btn',
        'submitText' => __('admin.action.update'),
        'body' => view('admin.pages.color.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.color.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
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
    <script src="{{ asset('backend/assets/js/pages/color/index.js') }}"></script>
@endpush
