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

        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }

        .ck-content .image {
            /* Block images */
            max-width: 80%;
            margin: 20px auto;
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
                        <h4 class="mb-0">Policy</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Quản lý</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.frontend.policy.index') }}">policy</a></li>
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
                                <a class="dropdown-item" href="javascript:void(0)" id="addPolicy" data-toggle="modal"
                                    data-target="#addPolicyModel">
                                    <i class="far fa-plus-square"></i> Thêm</a>
                                <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">
                                    <i class="fab fa-cloudversify"></i> Khôi phục tất cả</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">
                                    <i class="fas fa-trash-alt"></i> Xóa nhiều tạm thời</a>
                                <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)"
                                    style="display: none">
                                    <i class="fas fa-trash-alt"></i> Xóa nhiều vĩnh viễn</a>
                            </div>
                        </div>
                        <div class="">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" name="include_trashed"
                                    id="includeTrashedCheckbox">
                                <label class="custom-control-label" for="includeTrashedCheckbox"
                                    style="padding-top: 2px">Bản
                                    ghi thùng rác</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="policy_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>Tiêu đề</th>
                                        <th>Slug</th>
                                        <th>Trạng thái</th>
                                        <th>Tác vụ</th>
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

    <!-- Add Policy Modal -->
    <div class="modal fade" id="addPolicyModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_policy_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="add_title">Title</label>
                            <input type="text" class="form-control" id="add_title" name="title"
                                placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_slug">Slug</label>
                            <input type="text" class="form-control" id="add_slug" name="slug"
                                placeholder="Enter slug" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_detail">Detail</label>
                            <textarea class="form-control" id="add_detail" name="detail"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                        <button type="submit" id="add_policy_btn" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Policy Modal -->
    <div class="modal fade" id="editPolicyModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_policy_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="policy_id" id="policy_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="edit_title">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title"
                                placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_slug">Slug</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug"
                                placeholder="Enter slug" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_detail">Detail</label>
                            <textarea class="form-control" id="edit_detail" name="detail"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                        <button type="submit" id="edit_policy_btn" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>

    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor-setup.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initializeCKEditor("#add_detail", {
                placeholder: 'Enter detail'
            }).then(addEditorDetail => {
                addEditorD = addEditorDetail;
            });

            initializeCKEditor("#edit_detail", {
                placeholder: 'Enter detail'
            }).then(editEditorDetail => {
                editEditorD = editEditorDetail;
            });
        });

        $(document).ready(function() {
            // DataTable initialization
            const $policyDatatable = $("#policy_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($policyDatatable)) {
                    $policyDatatable.DataTable().destroy();
                }

                const dataTable = $policyDatatable.DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    language: {
                        sProcessing: 'Loading <i class="fa fa-spinner" style="transition: 2s;"></i>',
                        oPaginate: {
                            sNext: '<i class="fa fa-chevron-right"></i>',
                            sPrevious: '<i class="fa fa-chevron-left"></i>',
                        },
                    },
                    ajax: {
                        url: "{{ route('admin.frontend.policy.index') }}",
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
                                    var slug = jsonObject.slug;
                                    return slug;
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
                        dataTable.draw(); // Reload only the data
                        shouldReloadData = false;
                    }
                }, 300); // Adjust the debounce delay as needed

                $('#includeTrashedCheckbox').change(handleCheckboxChange);

                function handleCheckboxChange() {
                    const $checkbox = $(this);
                    const checked = $checkbox.is(":checked");
                    const elementsToShow = checked ? ['#restoreAll', '#forceDeleteMultiple'] : [
                        '#deleteMultiple', '#addPolicy'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addPolicy'] : ['#restoreAll',
                        '#forceDeleteMultiple'
                    ];

                    elementsToShow.forEach(element => $(element).show());
                    elementsToHide.forEach(element => $(element).hide());

                    shouldReloadData = true;
                    debounceReloadData();
                }
            };

            // Initialization
            initDataTable();

            // Bind Generate Slug
            bindSlugGenerator("#add_title, #edit_title", "#add_slug, #edit_slug");

            // Event listeners
            $(document).on("click", "#checkAll", function() {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
            });

            // Process a policy add form
            $(document).on("submit", "#add_policy_form", function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_policy_btn").text("Adding...");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.store') }}",
                    method: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        initDataTable();

                        addEditorD.setData("");

                        $("#add_policy_btn").text("Add");
                        $("#add_policy_form")[0].reset();
                        $("#addPolicyModel").modal("hide");
                    }
                })
            });

            $('#addPolicyModel, #editPolicyModel').on('hidden.modal', function() {
                $(this).find('form').trigger('reset');
            });

            // Get the data dumped on the edit policy form
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function(res) {
                    if (res.status === 200) {
                        // Phân tích chuỗi JSON trong trường data_value thành một đối tượng JavaScript
                        const dataValue = JSON.parse(res.data.data_value);

                        // Cập nhật giá trị của các trường trong modal chỉnh sửa policy
                        $("#policy_id").val(res.data.id);
                        $("#edit_title").val(dataValue.title);
                        $("#edit_slug").val(dataValue.slug);

                        editEditorD.setData(dataValue.detail);
                    } else {
                        console.error(res.message);
                    }
                });
            });

            // Process a policy edit form
            $(document).on("submit", "#edit_policy_form", function(e) {
                e.preventDefault();
                const csrfToken = $('meta[name="csrf-token"]').attr("content");
                const fd = new FormData(this);
                fd.append("_method", "PUT");

                $("#edit_policy_btn").text("Updating...");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.update') }}",
                    type: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        initDataTable();

                        $("#edit_policy_btn").text("Update");
                        $("#edit_policy_form")[0].reset();
                        $("#editPolicyModel").modal("hide");
                    } else {
                        toastr.error(res.message);
                    }
                });
            });

            // Process deleting a policy
            $(document).on("click", ".deleteIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn vẫn có thể khôi phục policy này trong thùng rác!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.frontend.policy.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa policy không thành công!",
                                        "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa policy", "warning");
                        }
                    }
                );
            });

            // Handle multiple policy deletion
            $(document).on("click", "#deleteMultiple", function(e) {
                e.preventDefault();

                var checked = $(this).prop("checked");
                var checkboxes = $(".checkbox_ids");

                if (!checked && checkboxes.filter(":checked").length === 0) {
                    // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
                    toastr.error("Bạn chưa chọn bất kỳ mục nào để xóa.");
                    return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
                }

                // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
                var ids = [];
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                checkboxes.each(function() {
                    if ($(this).prop("checked")) {
                        ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
                    }
                });

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn vẫn có thể khôi phục thương hiệu này trong thùng rác!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.frontend.policy.delete.all') }}",
                                method: "DELETE",
                                data: {
                                    ids: ids,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    $("#checkAll").prop("checked", false);
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa nhiều thương hiệu thất bại",
                                        "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa nhiều thương hiệu", "warning");
                        }
                    }
                );
            });

            // Handle the recovery of a policy
            $(document).on("click", ".restoreIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.restore') }}",
                    method: "POST",
                    data: {
                        id: id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });

            // Multi-policy recovery processing
            $(document).on("click", "#restoreAll", function(e) {
                e.preventDefault();
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.restore.all') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#checkAll").prop("checked", false);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });

            // Process permanent deletion of a policy
            $(document).on("click", ".forceIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn sẽ không thể khôi phục policy này nữa!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.frontend.policy.force.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    initDataTable();
                                } else {
                                    swal("Lỗi!", res.message, "error");
                                }
                            });
                        } else {
                            swal("Hủy bỏ", "Đã hủy bỏ việc xóa policy", "warning");
                        }
                    }
                );
            });

            // Click force delete multiple item policy
            $(document).on("click", "#forceDeleteMultiple", function(e) {
                e.preventDefault();

                var checked = $(this).prop("checked");
                var checkboxes = $(".checkbox_ids");

                if (!checked && checkboxes.filter(":checked").length === 0) {
                    // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
                    toastr.error("Bạn chưa chọn bất kỳ mục nào để xóa.");
                    return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
                }

                // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
                var ids = [];
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                checkboxes.each(function() {
                    if ($(this).prop("checked")) {
                        ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
                    }
                });

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn không thể khôi phục thương hiệu này nữa!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.frontend.policy.force.delete.all') }}",
                                method: "DELETE",
                                data: {
                                    ids: ids,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    $("#checkAll").prop("checked", false);
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa nhiều thương hiệu thất bại",
                                        "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa nhiều thương hiệu", "warning");
                        }
                    }
                );
            });

            // Click status item policy
            $(document).on('click', '.statusIcon', function(e) {
                e.preventDefault();

                const id = $(this).attr("id");
                var currentStatus = $(this).hasClass('btn-success') ? 1 : 0;
                var newStatus = currentStatus === 1 ? 0 : 1;
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.frontend.policy.change.status') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        new_status: newStatus
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                }).done(function(res) {
                    if (res.status == 200) {
                        if (newStatus === 1) {
                            $('#' + id).removeClass('btn-dark').addClass(
                                'btn-success');
                        } else {
                            $('#' + id).removeClass('btn-success').addClass(
                                'btn-dark');
                        }

                        toastr.success(res.message);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });
        });
    </script>
@endpush
