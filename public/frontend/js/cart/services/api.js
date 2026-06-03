(function (window) {
    window.CartApi = {
        get(url, data = {}) {
            return $.ajax({
                url,
                type: "GET",
                data,
            });
        },

        post(url, data = {}) {
            return $.ajax({
                url,
                type: "POST",
                data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
            });
        },

        delete(url, data = {}) {
            return $.ajax({
                url,
                type: "DELETE",
                data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
            });
        },
    };
})(window);
