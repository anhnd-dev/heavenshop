window.setupRestoreHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const id = $(this).attr("id");
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: route,
            method: "POST",
            data: {
                id: id,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
};

window.setupRestoreAllHandler = function (button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: route,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                $("#checkAll").prop("checked", false);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
};
