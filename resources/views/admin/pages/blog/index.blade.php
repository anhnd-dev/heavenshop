@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
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

        .tag {
            background: black;
            color: #fff;
            padding: 2px;
            border-radius: 4px;
            margin: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        @include('admin.components.breadcrumbs', [
            'parent' => __('admin.common.manage'),
            'title' => __('admin.sidebar.blog'),
            'url' => route('admin.blog.index'),
        ])
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row mb-4">
            <div class="col-12 mt-3">
                <div class="card">
                    @include('admin.components.toolbox', [
                        'addButtonId' => 'addBlog',
                        'modalId' => 'addBlogModal',
                    ])

                    @include('admin.components.datatable', [
                        'tableId' => 'blog_datatable',
                    
                        'columns' => [
                            __('admin.blog.blog_title'),
                            __('admin.common.slug'),
                            __('admin.blog.blog_view'),
                            __('admin.blog.blog_tags'),
                            __('admin.blog.blog_category'),
                            __('admin.blog.blog_author'),
                            __('admin.blog.blog_image'),
                            __('admin.blog.status'),
                            __('admin.blog.created_at'),
                            __('admin.blog.action'),
                        ],
                    ])
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Blog Modal -->
    @include('admin.components.modal', [
        'id' => 'addBlogModal',
        'size' => 'modal-xl',
        'title' => __('admin.blog.add_blog'),
        'formId' => 'add_blog_form',
        'enctype' => 'multipart/form-data',
        'submitId' => 'add_blog_btn',
        'submitText' => __('admin.common.add'),
        'body' => view('admin.pages.blog.components.form-fields', [
            'prefix' => 'add',
            'categories' => $categories,
        ])->render(),
    ])

    <!-- Edit Blog Modal -->
    @include('admin.components.modal', [
        'id' => 'editBlogModal',
        'size' => 'modal-xl',
        'title' => __('admin.blog.edit_blog'),
        'formId' => 'edit_blog_form',
        'method' => 'PUT',
        'enctype' => 'multipart/form-data',
        'hiddenFields' => [
            [
                'name' => 'blog_id',
                'id' => 'blog_id',
            ],
            [
                'name' => 'blog_image',
                'id' => 'blog_image',
                'value' => '[]',
            ],
        ],
        'submitId' => 'edit_blog_btn',
        'submitText' => __('admin.common.update'),
        'body' => view('admin.pages.blog.components.form-fields', [
            'prefix' => 'edit',
            'categories' => $categories,
        ])->render(),
    ])
@endsection

@include('admin.pages.blog.assets.config')

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

    <!-- Core: Services -->
    <script src="{{ asset('backend/assets/js/core/services/crud.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/force-delete.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/restore.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/services/status.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend/assets/js/pages/blog/index.js') }}"></script>
@endpush
