@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.slider_section'),
            'url' => route('admin.slider.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addSlider',
                        'modalId' => 'addSliderModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'slider_datatable',
                    
                        'columns' => [
                            __('admin.slider.title'),
                            __('admin.slider.image'),
                            __('admin.slider.position'),
                            __('admin.slider.sort_order'),
                            __('admin.slider.status'),
                            __('admin.slider.start_at'),
                            __('admin.slider.end_at'),
                            __('admin.common.action'),
                        ],
                    ])
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Slider Modal -->
    @include('admin.components.modal', [
        'id' => 'addSliderModal',
        'size' => 'modal-lg',
        'title' => __('admin.slider.add_slider'),
        'formId' => 'add_slider_form',
        'enctype' => 'multipart/form-data',
        'submitId' => 'add_slider_btn',
        'submitText' => __('admin.common.add'),
        'body' => view('admin.pages.slider.components.form-fields', ['prefix' => 'add'])->render(),
    ])

    <!-- Edit Slider Modal -->
    @include('admin.components.modal', [
        'id' => 'editSliderModal',
        'size' => 'modal-lg',
        'title' => __('admin.slider.edit_slider'),
        'formId' => 'edit_slider_form',
        'method' => 'PUT',
        'enctype' => 'multipart/form-data',
        'hiddenFields' => [
            [
                'name' => 'slider_id',
                'id' => 'slider_id',
            ],
        ],
        'submitId' => 'edit_slider_btn',
        'submitText' => __('admin.common.update'),
        'body' => view('admin.pages.slider.components.form-fields', ['prefix' => 'edit'])->render(),
    ])
@endsection

@include('admin.pages.slider.assets.config')

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
    <script src="{{ asset('backend/assets/js/core/services/force-delete.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/restore.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/slider/index.js') }}"></script>
@endpush
