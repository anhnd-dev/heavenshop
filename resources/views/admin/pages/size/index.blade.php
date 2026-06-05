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
            'title' => __('admin.sidebar.size'),
            'url' => route('admin.size.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">

                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addSize',
                        'modalId' => 'addSizeModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'size_datatable',
                    
                        'columns' => [
                            __('admin.size.size_name'),
                            __('admin.size.status'),
                            __('admin.common.action'),
                        ],
                    ])

                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Size Modal -->
    @include('admin.components.modal', [
        'id' => 'addSizeModal',
        'title' => __('admin.size.add_size'),
        'formId' => 'add_size_form',
        'submitId' => 'add_size_btn',
        'submitText' => __('admin.action.add'),
        'body' => view('admin.pages.size.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Size Modal -->
    @include('admin.components.modal', [
        'id' => 'editSizeModal',
        'title' => __('admin.size.edit_size'),
        'formId' => 'edit_size_form',
        'method' => 'PUT',
        'hiddenFields' => [
            [
                'name' => 'size_id',
                'id' => 'size_id',
            ],
        ],
        'submitId' => 'edit_size_btn',
        'submitText' => __('admin.action.update'),
        'body' => view('admin.pages.size.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.size.assets.config')

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
    <script src="{{ asset('backend/assets/js/pages/size/index.js') }}"></script>
@endpush
