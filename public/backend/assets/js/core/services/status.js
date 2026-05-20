function setupStatusHandler(button, route, callback) {
    $(document).on("click", button, function (e) {
        e.preventDefault();

        const id = $(this).attr("id");
        var currentStatus = $(this).hasClass("btn-success") ? 1 : 0;
        var newStatus = currentStatus === 1 ? 0 : 1;
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: route,
            method: "POST",
            data: {
                id: id,
                new_status: newStatus,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        }).done(function (res) {
            if (res.status == 200) {
                if (newStatus === 1) {
                    $("#" + id)
                        .removeClass("btn-dark")
                        .addClass("btn-success");
                } else {
                    $("#" + id)
                        .removeClass("btn-success")
                        .addClass("btn-dark");
                }

                toastr.success(res.message);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
}
