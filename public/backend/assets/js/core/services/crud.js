window.setupAddHandler = function (form, button, modal, route, callback) {
    $(document).on("submit", form, function (e) {
        e.preventDefault();

        const fd = new FormData(this);

        $(button).text(window.translations.adding);

        $.ajax({
            url: route,
            method: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
        })
            .done(function (res) {
                toastr.success(res.message);

                $(form)[0].reset();

                $(modal).modal("hide");

                setTimeout(() => {
                    callback?.();
                }, 200);
            })
            .fail(function (xhr) {
                let message = window.translations.error;

                // Validation error
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    message = Object.values(errors).flat().join("<br>");
                }

                // Other error
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    html: message,
                });
            })

            .always(function () {
                $(button).prop("disabled", false).text(window.translations.add);
            });
    });
};

window.setupEditHandler = function (form, button, modal, route, callback) {
    $(document).on("submit", form, function (e) {
        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const fd = new FormData(this);

        fd.append("_method", "PUT");

        $(button).text(window.translations.updating);

        $.ajax({
            url: route,
            type: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        })
            .done(function (res) {
                toastr.success(res.message);

                $(form)[0].reset();
                $(modal).modal("hide");

                setTimeout(() => {
                    callback?.();
                }, 200);
            })
            .fail(function (xhr) {
                let message = window.translations.error;

                // Validation
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    message = Object.values(errors).flat().join("<br>");
                }

                // Server error
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    html: message,
                });
            })

            .always(function () {
                $(button)
                    .prop("disabled", false)
                    .text(window.translations.update);
            });
    });
};

window.setupDeleteHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        // Lấy ID của mục cần xóa
        const id = $(this).attr("id");

        // Lấy mã thông báo CSRF từ thẻ meta
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        // Hiển thị hộp thoại xác nhận
        swal(
            {
                title: window.translations.title,
                text: window.translations.delete_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                // Nếu người dùng xác nhận xóa
                if (isConfirm) {
                    $.ajax({
                        url: route,
                        method: "DELETE",
                        data: {
                            id: id,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        // Nếu xóa thành công
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success",
                            );
                            callback();
                        } else {
                            // Nếu việc xóa không thành công
                            swal(
                                window.translations.errors,
                                res.message,
                                "error",
                            );
                        }
                    });
                } else {
                    // Nếu người dùng hủy việc xóa
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel,
                        "warning",
                    );
                }
            },
        );
    });
};

window.setupDeleteMultipleHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        var checked = $(this).prop("checked");
        var checkboxes = $(".checkbox_ids");

        if (!checked && checkboxes.filter(":checked").length === 0) {
            // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
            toastr.error(window.translations.checkbox_delete);
            return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
        }

        // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
        var ids = [];

        checkboxes.each(function () {
            if ($(this).prop("checked")) {
                ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
            }
        });

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.delete_all_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: route,
                        method: "DELETE",
                        data: {
                            ids: ids,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success",
                            );
                            $("#checkAll").prop("checked", false);
                            callback();
                        } else {
                            swal(
                                window.translations.errors,
                                res.message,
                                "error",
                            );
                        }
                    });
                } else {
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel_all,
                        "warning",
                    );
                }
            },
        );
    });
};

window.setupAjaxActionHandler = function (
    button,
    route,
    getData,
    callback,
    confirmText = "Bạn có chắc chắn?",
) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: confirmText,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: true,
            },

            function (isConfirm) {
                if (!isConfirm) {
                    return;
                }

                $.ajax({
                    url: route,

                    method: "POST",

                    data: getData(),

                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },

                    dataType: "json",
                })

                    .done(function (res) {
                        toastr.success(res.message);

                        callback?.(res);
                    })

                    .fail(function (xhr) {
                        let message =
                            xhr.responseJSON?.message ??
                            window.translations.error;

                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            html: message,
                        });
                    });
            },
        );
    });
};
