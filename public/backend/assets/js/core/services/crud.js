window.setupAddHandler = function (form, button, modal, route, callback) {
    $(document).on("submit", form, function (e) {
        e.preventDefault();

        const fd = new FormData(this);

        $(button).text(window.translations.adding);

        $.ajax({
            url: typeof route === "function" ? route() : route,
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

        const id =
            fd.get("id") || $("#edit_gallery_id").val() || $("#id").val();

        $(button).text(window.translations.updating);

        $.ajax({
            url: typeof route === "function" ? route(id) : route,
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
                        url: typeof route === "function" ? route() : route,
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

window.setupDeleteMultipleHandler = function (
    button,
    route,
    callback,
    checkboxSelector = ".checkbox_ids",
    checkAllSelector = "#checkAll",
) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const checkboxes = $(checkboxSelector);

        if (checkboxes.filter(":checked").length === 0) {
            toastr.error(window.translations.checkbox_delete);

            return;
        }

        const ids = [];

        checkboxes.each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
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
                if (!isConfirm) {
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel_all,
                        "warning",
                    );

                    return;
                }

                $.ajax({
                    url: typeof route === "function" ? route() : route,

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

                        $(checkAllSelector).prop("checked", false);

                        callback?.();
                    } else {
                        swal(window.translations.errors, res.message, "error");
                    }
                });
            },
        );
    });
};

window.setupRestoreAllHandler = function (
    button,
    route,
    callback,
    checkboxSelector = ".checkbox_ids",
    checkAllSelector = "#checkAll",
) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const checkboxes = $(checkboxSelector);

        if (checkboxes.filter(":checked").length === 0) {
            toastr.error(window.translations.checkbox_restore);

            return;
        }

        const ids = [];

        checkboxes.each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        });

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.restore_all_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (!isConfirm) {
                    return;
                }

                $.ajax({
                    url: typeof route === "function" ? route() : route,

                    method: "POST",

                    data: {
                        ids: ids,
                    },

                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },

                    dataType: "json",
                }).done(function (res) {
                    toastr.success(res.message);

                    $(checkAllSelector).prop("checked", false);

                    callback?.();
                });
            },
        );
    });
};

window.setupForceMultipleHandler = function (
    button,
    route,
    callback,
    checkboxSelector = ".checkbox_ids",
    checkAllSelector = "#checkAll",
) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const checkboxes = $(checkboxSelector);

        if (checkboxes.filter(":checked").length === 0) {
            toastr.error(window.translations.checkbox_delete);

            return;
        }

        const ids = [];

        checkboxes.each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        });

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.force_delete_all_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },

            function (isConfirm) {
                if (!isConfirm) {
                    return;
                }

                $.ajax({
                    url: typeof route === "function" ? route() : route,

                    method: "DELETE",

                    data: {
                        ids: ids,
                    },

                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },

                    dataType: "json",
                }).done(function (res) {
                    toastr.success(res.message);

                    $(checkAllSelector).prop("checked", false);

                    callback?.();
                });
            },
        );
    });
};

window.setupFormHandler = function ({
    form,
    button,
    modal,

    route,

    method = "POST",

    buttonLoadingText = "Đang xử lý...",
    buttonDefaultText = "Lưu",

    resetForm = true,

    transformData = null,

    callback,
}) {
    const formSelector =
        typeof form === "string" ? form : "#" + $(form).attr("id");

    $(document)
        .off("submit", formSelector)
        .on("submit", formSelector, function (e) {
            e.preventDefault();

            const formElement = this;

            let fd = new FormData(formElement);

            if (typeof transformData === "function") {
                fd = transformData(fd) || fd;
            }

            if (method.toUpperCase() === "PUT") {
                fd.append("_method", "PUT");
            }

            const $button = $(button);

            $button.prop("disabled", true).text(buttonLoadingText);

            const url = typeof route === "function" ? route() : route;

            if (!url) {
                toastr.error("Không tìm thấy URL xử lý");

                $button.prop("disabled", false).text(buttonDefaultText);

                return;
            }

            $.ajax({
                url,
                method: "POST",

                data: fd,

                processData: false,
                contentType: false,

                dataType: "json",
            })
                .done((res) => {
                    toastr.success(res.message ?? "Thao tác thành công");

                    if (resetForm) {
                        formElement.reset();
                    }

                    if (modal) {
                        $(modal).modal("hide");
                    }

                    callback?.(res);
                })
                .fail((xhr) => {
                    let message =
                        xhr.responseJSON?.message ??
                        window.translations?.error ??
                        "Đã xảy ra lỗi";

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors ?? {};

                        message = Object.values(errors).flat().join("<br>");
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        html: message,
                    });
                })
                .always(() => {
                    $button.prop("disabled", false).text(buttonDefaultText);
                });
        });
};

window.setupBulkActionHandler = function ({
    button,
    route,
    method = "POST",

    checkboxSelector = ".checkbox_ids",
    checkAllSelector = "#checkAll",

    confirmText,
    emptyText,

    getData = null,

    callback,
}) {
    $(document)
        .off("click", button)
        .on("click", button, function (e) {
            e.preventDefault();

            const ids = $(checkboxSelector)
                .filter(":checked")
                .map(function () {
                    return $(this).val();
                })
                .get();

            if (!ids.length) {
                toastr.error(
                    emptyText ??
                        window.translations?.checkbox_required ??
                        "Vui lòng chọn dữ liệu",
                );

                return;
            }

            Swal.fire({
                title: window.translations?.title ?? "Xác nhận",
                text: confirmText ?? window.translations?.confirmText,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: window.translations?.confirmText ?? "Đồng ý",
                cancelButtonText: window.translations?.cancelText ?? "Hủy",
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: typeof route === "function" ? route() : route,

                    method,

                    data: getData?.(ids) ?? {
                        ids,
                    },

                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },

                    dataType: "json",
                })
                    .done((res) => {
                        toastr.success(res.message ?? "Thao tác thành công");

                        $(checkAllSelector).prop("checked", false);

                        $(checkboxSelector).prop("checked", false);

                        callback?.(res);
                    })
                    .fail((xhr) => {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            html:
                                xhr.responseJSON?.message ??
                                window.translations?.error ??
                                "Đã xảy ra lỗi",
                        });
                    });
            });
        });
};

window.setupAjaxActionHandler = function ({
    button,
    route,

    method = "POST",

    confirmText = "Bạn có chắc chắn?",

    getData = () => ({}),

    callback = null,
}) {
    $(document)
        .off("click", button)
        .on("click", button, function (e) {
            e.preventDefault();

            const $btn = $(this);

            Swal.fire({
                title: window.translations?.title ?? "Xác nhận",
                text: confirmText,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: window.translations?.confirmText ?? "Đồng ý",
                cancelButtonText: window.translations?.cancelText ?? "Hủy",
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;

                $btn.prop("disabled", true);

                $.ajax({
                    url: typeof route === "function" ? route($btn) : route,

                    method,

                    data: getData($btn),

                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },

                    dataType: "json",
                })
                    .done((res) => {
                        toastr.success(res.message ?? "Thao tác thành công");

                        callback?.(res, $btn);
                    })
                    .fail((xhr) => {
                        let message =
                            xhr.responseJSON?.message ??
                            window.translations?.error ??
                            "Đã xảy ra lỗi";

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors ?? {};

                            message = Object.values(errors).flat().join("<br>");
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            html: message,
                        });
                    })
                    .always(() => {
                        $btn.prop("disabled", false);
                    });
            });
        });
};
