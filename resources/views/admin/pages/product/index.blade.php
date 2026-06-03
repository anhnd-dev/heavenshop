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
            min-height: 200px;
        }

        .ck-content .image {
            max-width: 80%;
            margin: 20px auto;
        }

        .tag {
            display: inline-block;
            background-color: darkgray;
            border-radius: 3px;
            padding: 2px 6px;
            margin: 2px;
            font-size: 12px;
        }

        .df {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mr-1 {
            margin-right: 1px;
        }

        .mr-8 {
            margin-right: 8px;
        }

        .custom {
            border: 1px solid #ccc;
            padding: 8px;
            flex-wrap: wrap;
        }

        .variant-item {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .variant-item h6 {
            margin-bottom: 15px;
            font-weight: 600;
        }

        .select2-results__group {
            font-weight: 600;
            color: #495057;
        }

        .select2-results__option[role="option"] {
            padding-left: 30px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">

        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.product'),
            'url' => route('admin.product.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addProduct',
                        'modalId' => 'addProductModel',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'product_datatable',

                        'columns' => [
                            __('admin.product.product_image'),
                            __('admin.product.product_name'),
                            __('admin.product.category_name'),
                            __('admin.product.product_price'),
                            __('admin.product.product_inventory'),
                            __('admin.product.product_status'),
                            __('admin.product.product_featured'),
                            __('admin.common.action'),
                        ],
                    ])

                </div>
            </div>
        </div>

    </div>

    <!-- Add Product Modal -->
    @include('admin.components.modal', [
        'id' => 'addProductModel',
        'size' => 'modal-xl',
        'title' => __('admin.product.add_product'),
        'formId' => 'add_product_form',
        'submitId' => 'add_product_btn',
        'submitText' => __('admin.common.add'),
        'enctype' => 'multipart/form-data',
        'body' => view('admin.pages.product.components.form-fields', [
            'prefix' => 'add',
            'mode' => 'create',

            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes,
        ])->render(),
    ])

    <!-- Edit Product Modal -->
    @include('admin.components.modal', [
        'id' => 'editProductModel',
        'size' => 'modal-xl',
        'title' => __('admin.product.edit_product'),
        'formId' => 'edit_product_form',
        'method' => 'PUT',
        'submitId' => 'edit_product_btn',
        'submitText' => __('admin.common.update'),
        'enctype' => 'multipart/form-data',
        'hiddenFields' => [
            [
                'name' => 'product_id',
                'id' => 'product_id',
            ],
            [
                'name' => 'removed_variants',
                'id' => 'removed_variants',
                'value' => '[]',
            ],
        ],
        'body' => view('admin.pages.product.components.form-fields', [
            'prefix' => 'edit',
            'mode' => 'edit',
            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes,
        ])->render(),
    ])

    <!-- Variant Template -->
    @include('admin.pages.product.components.variant-template', [
        'colors' => $colors,
        'sizes' => $sizes,
    ])

    <!-- Gallery Wrapper -->
    @include('admin.pages.gallery.components.gallery-wrapper')

    <!-- Add Gallery Modal -->
    @include('admin.components.modal', [
        'id' => 'addGalleryModel',
        'size' => 'modal-lg',
        'title' => 'Thêm media',
        'formId' => 'add_gallery_form',
        'submitId' => 'add_gallery_btn',
        'submitText' => __('admin.common.add'),
        'enctype' => 'multipart/form-data',

        'hiddenFields' => [
            [
                'name' => 'product_id',
                'id' => 'add_gallery_product_id',
            ],
        ],

        'body' => view('admin.pages.gallery.components.gallery-form', [
            'prefix' => 'add',
            'mode' => 'create',
            'colors' => $colors,
        ])->render(),
    ])

    @include('admin.components.modal', [
        'id' => 'editGalleryModel',
        'size' => 'modal-lg',
        'title' => 'Cập nhật media',
        'formId' => 'edit_gallery_form',
        'method' => 'PUT',
        'submitId' => 'edit_gallery_btn',
        'submitText' => __('admin.common.update'),
        'enctype' => 'multipart/form-data',

        'hiddenFields' => [
            [
                'name' => 'gallery_id',
                'id' => 'edit_gallery_id',
            ],
        ],

        'body' => view('admin.pages.gallery.components.gallery-form', [
            'prefix' => 'edit',
            'mode' => 'edit',
            'colors' => $colors,
        ])->render(),
    ])
@endsection

@include('admin.pages.product.assets.config')
@include('admin.pages.gallery.assets.config')

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor-setup.js') }}"></script>
    <script src="{{ asset('backend/vendors/dropify/js/dropify.min.js') }}"></script>
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
    <script src="{{ asset('backend/assets/js/pages/product/index.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/product_gallery/index.js') }}"></script>
@endpush
