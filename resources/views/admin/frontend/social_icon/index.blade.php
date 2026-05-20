@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" />
@endpush

@push('styles')
    <style>
        .dropdown-item.text-red {
            color: rgba(255, 0, 0, 0.7);
        }

        .dropdown-item.text-red:hover {
            color: rgba(255, 0, 0, 1);
        }

        select {
            font-family: 'FontAwesome', 'sans-serif';
        }

        option {
            font-size: 18px;
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
                        <h4 class="mb-0">{{ __('admin.breadcrumbs.social_icon') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.breadcrumbs.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.frontend.social_icon.index') }}">{{ __('admin.breadcrumbs.social_icon') }}</a>
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
                                <a class="dropdown-item" href="javascript:void(0)" id="addSocialIcon" data-toggle="modal"
                                    data-target="#addSocialIconModel">
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
                            <table id="social_icon_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.social_icon.title') }}</th>
                                        <th>{{ __('admin.social_icon.icon') }}</th>
                                        <th>{{ __('admin.social_icon.url') }}</th>
                                        <th>{{ __('admin.social_icon.status') }}</th>
                                        <th>{{ __('admin.social_icon.action') }}</th>
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

    <!-- Add Social Icon Modal -->
    <div class="modal fade" id="addSocialIconModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.social_icon.add_social_icon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_social_icon_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="title">{{ __('admin.social_icon.title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="social_icon">{{ __('admin.social_icon.icon') }}</label>
                            <select name="social_icon" id="social_icon" class="form-control">
                                <option value="" disabled selected>{{ __('admin.social_icon.select_social_icon') }}
                                </option>
                                <option value="fa-brands fa-facebook">&#xf09a; fa-facebook</option>
                                <option value="fa-brands fa-facebook-f">&#xf09a; fa-facebook-f</option>
                                <option value="fa-brands fa-facebook-square">&#xf082; fa-facebook-square</option>
                                <option value="fa-brands fa-dribbble">&#xf17d; fa-dribbble</option>
                                <option value="fa-brands fa-dribbble">&#xf17d; fa-dribbble</option>
                                <option value="fa-brands fa-git">&#xf1d3; fa-git</option>
                                <option value="fa-brands fa-git-square">&#xf1d2; fa-git-square</option>
                                <option value="fa-brands fa-github">&#xf09b; fa-github</option>
                                <option value="fa-brands fa-github-alt">&#xf113; fa-github-alt</option>
                                <option value="fa-brands fa-github-square">&#xf092; fa-github-square</option>
                                <option value="fa-brands fa-linkedin">&#xf0e1; fa-linkedin</option>
                                <option value="fa-brands fa-twitter">&#xf099; fa-twitter</option>
                                <option value="fa-brands fa-instagram">&#xf16d; fa-instagram</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="url">{{ __('admin.social_icon.url') }}</label>
                            <input type="text" class="form-control" id="url" name="url"
                                placeholder="Enter Url" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="add_social_icon_btn"
                            class="btn btn-primary">{{ __('admin.action.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Social Icon Modal -->
    <div class="modal fade" id="editSocialIconModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.social_icon.edit_social_icon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_social_icon_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="social_icon_id" id="social_icon_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="title">{{ __('admin.social_icon.title') }}</label>
                            <input type="text" class="form-control" id="e_title" name="title"
                                placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="social_icon">{{ __('admin.social_icon.icon') }}</label>
                            <select name="social_icon" id="e_social_icon" class="form-control"></select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="url">{{ __('admin.social_icon.url') }}</label>
                            <input type="text" class="form-control" id="e_url" name="url"
                                placeholder="Enter Url" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="edit_social_icon_btn"
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

            const $socialIconDatatable = $("#social_icon_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($socialIconDatatable)) {
                    $socialIconDatatable.DataTable().destroy();
                }

                const dataTable = $socialIconDatatable.DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    language: {
                        sSearch: '<span class="fs-14">' + window.translations.search + '</span>',
                        sProcessing: '' + window.translations.loading +
                            ' <i class="fa fa-spinner" style="transition: 2s;"></i>',
                        sLengthMenu: '<span class="fs-14">' + window.translations.show +
                            '</span> <select class="form-control" style="margin: 0 4px;">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="30">30</option>' +
                            '<option value="40">40</option>' +
                            '<option value="50">50</option>' +
                            '<option value="-1">Tất cả</option>' +
                            '</select> <span class="fs-14">' + window.translations.record + '</span>',
                        sInfo: '' + window.translations.show + ' _START_ ' + window.translations.to +
                            ' _END_ ' + window.translations.of + ' _TOTAL_ ' + window.translations
                            .record + '',
                        sEmptyTable: '' + window.translations.no_data + '',
                        oPaginate: {
                            sNext: '<i class="fa fa-chevron-right"></i>',
                            sPrevious: '<i class="fa fa-chevron-left"></i>',
                        },
                    },
                    ajax: {
                        url: "{{ route('admin.frontend.social_icon.index') }}",
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
                                    var social_icon = jsonObject.social_icon;
                                    return social_icon;
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
                                    var url = jsonObject.url;
                                    return url;
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
                                    return `<span class="badge p-2 badge-success mb-1">${window.translations.show}</span>`;
                                } else {
                                    return `<span class="badge p-2 badge-dark mb-1">${window.translations.hide}</span>`;
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
                        dataTable.draw(); // Reload only the data
                        shouldReloadData = false;
                    }
                }, 300); // Adjust the debounce delay as needed

                $('#includeTrashedCheckbox').change(handleCheckboxChange);

                function handleCheckboxChange() {
                    const $checkbox = $(this);
                    const checked = $checkbox.is(":checked");
                    const elementsToShow = checked ? ['#restoreAll', '#forceDeleteMultiple'] : [
                        '#deleteMultiple', '#addSocialIcon'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addSocialIcon'] : ['#restoreAll',
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
            setupAddHandler("#add_social_icon_form", "#add_social_icon_btn", "#addSocialIconModel", "{{ route('admin.frontend.social_icon.store') }}", initDataTable)

            const iconOptions = [{
                    key: "fa-brands fa-facebook",
                    value: "&#xf09a; fa-facebook"
                },
                {
                    key: "fa-brands fa-facebook-f",
                    value: "&#xf09a; fa-facebook-f"
                },
                {
                    key: "fa-brands fa-facebook-square",
                    value: "&#xf082; fa-facebook-square"
                },
                {
                    key: "fa-brands fa-dribbble",
                    value: "&#xf17d; fa-dribbble"
                },
                {
                    key: "fa-brands fa-git",
                    value: "&#xf1d3; fa-git"
                },
                {
                    key: "fa-brands fa-git-square",
                    value: "&#xf1d2; fa-git-square"
                },
                {
                    key: "fa-brands fa-github",
                    value: "&#xf09b; fa-github"
                },
                {
                    key: "fa-brands fa-github-alt",
                    value: "&#xf113; fa-github-alt"
                },
                {
                    key: "fa-brands fa-github-square",
                    value: "&#xf092; fa-github-square"
                },
                {
                    key: "fa-brands fa-linkedin",
                    value: "&#xf0e1; fa-linkedin"
                },
                {
                    key: "fa-brands fa-twitter",
                    value: "&#xf099; fa-twitter"
                },
                {
                    key: "fa-brands fa-instagram",
                    value: "&#xf16d; fa-instagram"
                }
            ];

            // Nhận dữ liệu được kết xuất trên biểu mẫu chỉnh sửa
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.frontend.social_icon.edit') }}",
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
                        $("#e_url").val(dataValue.url);
                        $("#social_icon_id").val(res.data.id);

                        const social_icon = extractIconClassesFromHTML(dataValue.social_icon);

                        $("#e_social_icon").empty();
                        $.each(iconOptions, function(index, item) {
                            var option = $('<option>', {
                                value: item.key,
                                html: item.value
                            });

                            if (item.key == social_icon) {
                                option.prop('selected', true);
                            }

                            $('#e_social_icon').append(option);
                        });

                    } else {
                        console.error(res.message);
                    }
                });
            });

            // Xử lý biểu mẫu chỉnh sửa
            setupEditHandler("#edit_social_icon_form", "#edit_social_icon_btn", "#editSocialIconModel", "{{ route('admin.frontend.social_icon.update') }}", initDataTable)

            // Xử lý xóa
            setupDeleteHandler(".deleteIcon", "{{ route('admin.frontend.social_icon.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.frontend.social_icon.delete.all') }}", initDataTable)

            // Xử lý việc khôi phục
            setupRestoreHandler(".restoreIcon", "{{ route('admin.frontend.social_icon.restore') }}", initDataTable)

            // Xử lý khôi phục nhiều
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.frontend.social_icon.restore.all') }}", initDataTable)

            // Xử lý việc xóa vĩnh viễn
            setupForceHandler(".forceIcon", "{{ route('admin.frontend.social_icon.force.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều vĩnh viễn
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.frontend.social_icon.force.delete.all') }}", initDataTable)

            // Xử lý trạng thái
            setupStatusHandler(".statusIcon", "{{ route('admin.frontend.social_icon.change.status') }}", initDataTable)
        });

        function extractIconClassesFromHTML(htmlString) {
            const tempElement = document.createElement('div');
            tempElement.innerHTML = htmlString;
            const iconElement = tempElement.querySelector('i');

            if (iconElement) {
                const classes = iconElement.classList;
                const iconClasses = Array.from(classes).filter(className => className.startsWith('fa-'));
                const iconClassString = iconClasses.join(' ');
                return iconClassString;
            } else {
                console.error("No icon found");
                return null;
            }
        }
    </script>
@endpush
