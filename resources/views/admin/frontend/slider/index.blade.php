@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}" />
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
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.breadcrumbs.slider') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.breadcrumbs.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.frontend.slider.index') }}">{{ __('admin.breadcrumbs.slider') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
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
                            <div class="dropdown-menu p-0" style="">
                                <a class="dropdown-item" href="javascript:void(0)" id="addSlider" data-toggle="modal"
                                    data-target="#addSliderModel">
                                    <i class="far fa-plus-square"></i> {{ __('admin.action.add') }}</a>
                                <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">
                                    <i class="fab fa-cloudversify"></i> {{ __('admin.action.restore_all') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.action.delete_temps') }}</a>
                                <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)"
                                    style="display: none">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.action.delete_permanently') }}</a>
                            </div>
                        </div>
                        <div class="">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" name="include_trashed"
                                    id="includeTrashedCheckbox">
                                <label class="custom-control-label" for="includeTrashedCheckbox"
                                    style="padding-top: 2px">{{ __('admin.action.trash_record') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="slider_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.slider.title') }}</th>
                                        <th>{{ __('admin.slider.description') }}</th>
                                        <th>{{ __('admin.slider.link_url') }}</th>
                                        <th>{{ __('admin.slider.image_url') }}</th>
                                        <th>{{ __('admin.slider.status') }}</th>
                                        <th>{{ __('admin.slider.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Slider Modal -->
    <div class="modal fade" id="addSliderModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.slider.add_slider') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_slider_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="title">{{ __('admin.slider.title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="{{ __('admin.slider.enter_title') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="description">{{ __('admin.slider.description') }}</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="{{ __('admin.slider.enter_description') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="link_url">{{ __('admin.slider.link_url') }}</label>
                            <input type="text" class="form-control" id="link_url" name="link_url"
                                placeholder="{{ __('admin.slider.enter_link_url') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.slider.image_url') }}</label>
                            <input type="file" name="image_url" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="add_slider_btn"
                            class="btn btn-primary">{{ __('admin.action.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Slider Modal -->
    <div class="modal fade" id="editSliderModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.slider.edit_slider') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_slider_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="slider_id" id="slider_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="title">{{ __('admin.slider.title') }}</label>
                            <input type="text" class="form-control" id="e_title" name="title"
                                placeholder="{{ __('admin.slider.enter_title') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="description">{{ __('admin.slider.description') }}</label>
                            <input type="text" class="form-control" id="e_description" name="description"
                                placeholder="{{ __('admin.slider.enter_description') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="link_url">{{ __('admin.slider.link_url') }}</label>
                            <input type="text" class="form-control" id="e_link" name="link_url"
                                placeholder="{{ __('admin.slider.enter_link_url') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.slider.image_url') }}</label>
                            <input type="file" name="image_url" class="form-control">
                        </div>
                        <div id="image_url"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="edit_slider_btn"
                            class="btn btn-primary">{{ __('admin.action.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        window.translations = {
            // DataTables
            search: "{{ __('admin.action.search') }}",
            show: "{{ __('admin.action.show') }}",
            record: "{{ __('admin.action.record') }}",
            loading: "{{ __('admin.action.loading') }}",
            to: "{{ __('admin.action.to') }}",
            of: "{{ __('admin.action.of') }}",
            no_data: "{{ __('admin.action.no_data') }}",

            show: "{{ __('admin.action.show') }}",
            hide: "{{ __('admin.action.hide') }}",
            add: "{{ __('admin.action.add') }}",
            adding: "{{ __('admin.action.adding') }}",
            update: "{{ __('admin.action.update') }}",
            updating: "{{ __('admin.action.updating') }}",

            // Notify
            deleted: "{{ __('admin.notify.deleted') }}",
            errors: "{{ __('admin.notify.errors') }}",
            cancelled: "{{ __('admin.notify.cancelled') }}",
            title: "{{ __('admin.notify.title') }}",
            confirmText: "{{ __('admin.notify.confirmText') }}",
            cancelText: "{{ __('admin.notify.cancelText') }}",
            cancel: "{{ __('admin.notify.cancel') }}",
            cancel_all: "{{ __('admin.notify.cancel_all') }}",
            delete_text: "{{ __('admin.notify.delete_text') }}",
            delete_all_text: "{{ __('admin.notify.delete_all_text') }}",
            force_delete_text: "{{ __('admin.notify.force_delete_text') }}",
            force_delete_all_text: "{{ __('admin.notify.force_delete_all_text') }}",
            checkbox_delete: "{{ __('admin.notify.checkbox_delete') }}",
        };
    </script>

    <script>
        $(document).ready(function() {

            const $sliderDatatable = $("#slider_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($sliderDatatable)) {
                    $sliderDatatable.DataTable().destroy();
                }

                const dataTable = $sliderDatatable.DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    language: {
                        sSearch: '<span class="fs-14">' + window.translations.search + '</span>',
                        sProcessing: '' + window.translations.loading + ' <i class="fa fa-spinner" style="transition: 2s;"></i>',
                        sLengthMenu: '<span class="fs-14">' + window.translations.show + '</span> <select class="form-control" style="margin: 0 4px;">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="30">30</option>' +
                            '<option value="40">40</option>' +
                            '<option value="50">50</option>' +
                            '<option value="-1">Tất cả</option>' +
                        '</select> <span class="fs-14">' + window.translations.record + '</span>',
                        sInfo: '' + window.translations.show + ' _START_ ' + window.translations.to + ' _END_ ' + window.translations.of + ' _TOTAL_ ' + window.translations.record + '',
                        sEmptyTable: '' + window.translations.no_data + '',
                        oPaginate: {
                            sNext: '<i class="fa fa-chevron-right"></i>',
                            sPrevious: '<i class="fa fa-chevron-left"></i>',
                        },
                    },
                    ajax: {
                        url: "{{ route('admin.frontend.slider.index') }}",
                        data: function(d) {
                            d.include_trashed = $('#includeTrashedCheckbox').is(':checked') ? 1 : 0;
                        }
                    },
                    columns: [{
                            data: "checkbox",
                            name: "checkbox",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return `<div class="form-check custom-checkbox ms-2">
                                    <input type="checkbox" class="form-check-input checkbox_ids" name="ids" required="" value="${full.id}">
                                    <label class="form-check-label"></label>
                                </div>`;
                            },
                        },
                        {
                            data: "data_value",
                            name: "data_value",
                            render: function(data, type, full, meta) {
                                try {
                                    var decodedData = $("<div/>").html(data).text();
                                    var jsonObject = JSON.parse(decodedData);
                                    var title = jsonObject.title;
                                    return title;
                                } catch (error) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "data_value",
                            name: "data_value",
                            render: function(data, type, full, meta) {
                                try {
                                    var decodedData = $("<div/>").html(data).text();
                                    var jsonObject = JSON.parse(decodedData);
                                    var description = jsonObject.description;
                                    return description;
                                } catch (error) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "data_value",
                            name: "data_value",
                            render: function(data, type, full, meta) {
                                try {
                                    var decodedData = $("<div/>").html(data).text();
                                    var jsonObject = JSON.parse(decodedData);
                                    var link_url = jsonObject.link_url;
                                    return link_url;
                                } catch (error) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "data_value",
                            name: "data_value",
                            render: function(data, type, full, meta) {
                                try {
                                    var decodedData = $("<div/>").html(data).text();
                                    var jsonObject = JSON.parse(decodedData);
                                    var image_url = jsonObject.image_url;

                                    if (image_url) {
                                        return `<img src="{{ asset('/uploads/slider/${image_url}') }}" width="60">`;
                                    } else {
                                        return `<img src="{{ asset('/default.png') }}" width="60">`;
                                    }
                                } catch (error) {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "status",
                            name: "status",
                            render: function(data, type, full, meta) {
                                if (data === 1) {
                                    return `<span class="badge p-2 badge-success mb-1">Hiển thị</span>`;
                                } else {
                                    return `<span class="badge p-2 badge-dark mb-1">Ẩn</span>`;
                                }
                            }
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                let shouldReloadData = false;
                const debounceReloadData = debounce(() => {
                    if (shouldReloadData) {
                        dataTable.draw();
                        shouldReloadData = false;
                    }
                }, 300);

                $('#includeTrashedCheckbox').change(handleCheckboxChange);

                function handleCheckboxChange() {
                    const $checkbox = $(this);
                    const checked = $checkbox.is(":checked");
                    const elementsToShow = checked ? ['#restoreAll', '#forceDeleteMultiple'] : [
                        '#deleteMultiple', '#addSlider'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addSlider'] : ['#restoreAll',
                        '#forceDeleteMultiple'
                    ];

                    toggleElements(elementsToShow, elementsToHide);
                    shouldReloadData = true;
                    debounceReloadData();
                }
            };

            // Khởi tạo bảng
            initDataTable();

            // Xử lý biểu mẫu thêm
            setupAddHandler("#add_slider_form", "#add_slider_btn", "#addSliderModel", "{{ route('admin.frontend.slider.store') }}", initDataTable)

            // Nhận dữ liệu được kết xuất trên biểu mẫu chỉnh sửa
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();

                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.frontend.slider.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                    }
                }).done(function(res) {
                    if (res.status === 200) {
                        // Phân tích chuỗi JSON trong trường data_value thành một đối tượng JavaScript
                        const dataValue = JSON.parse(res.data.data_value);

                        // Cập nhật giá trị của các trường trong modal chỉnh sửa slider
                        $("#e_title").val(dataValue.title);
                        $("#e_description").val(dataValue.description);
                        $("#e_link").val(dataValue.link_url);
                        $("#image_url").html(
                            `<img src="{{ asset('uploads/slider/${dataValue.image_url}') }}" alt="${dataValue.title}" class="img-fluid img-thumbnail" width="100">`
                        );
                        // Truy cập trực tiếp vào trường id từ dữ liệu trả về
                        $("#slider_id").val(res.data.id);
                    } else {
                        console.error(res.message);
                    }
                });
            });

            // Xử lý biểu mẫu chỉnh sửa
            setupEditHandler("#edit_slider_form", "#edit_slider_btn", "#editSliderModel", "{{ route('admin.frontend.slider.update') }}", initDataTable)

            // Xử lý xóa
            setupDeleteHandler(".deleteIcon", "{{ route('admin.frontend.slider.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.frontend.slider.delete.all') }}", initDataTable)

            // Xử lý việc khôi phục
            setupRestoreHandler(".restoreIcon", "{{ route('admin.frontend.slider.restore') }}", initDataTable)

            // Xử lý khôi phục nhiều
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.frontend.slider.restore.all') }}", initDataTable)

            // Xử lý việc xóa vĩnh viễn
            setupForceHandler(".forceIcon", "{{ route('admin.frontend.slider.force.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều vĩnh viễn
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.frontend.slider.force.delete.all') }}", initDataTable)

            // Xử lý trạng thái
            setupStatusHandler(".statusIcon", "{{ route('admin.frontend.slider.change.status') }}", initDataTable)
        });
    </script>
@endpush
