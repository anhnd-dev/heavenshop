window.setupForceHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();
        const id = $(this).attr("id");
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.force_delete_text,
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
                            id: id,
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
                        window.translations.cancel,
                        "warning",
                    );
                }
            },
        );
    });
};

window.setupForceMultipleHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        var checked = $(this).prop("checked");
        var checkboxes = $(".checkbox_ids");

        if (!checked && checkboxes.filter(":checked").length === 0) {
            // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
            toastr.error(window.translations.checkbox_delete);
            // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
            return;
        }

        // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
        var ids = [];
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        checkboxes.each(function () {
            if ($(this).prop("checked")) {
                ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
            }
        });

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
