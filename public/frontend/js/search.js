$(function () {
    let timer;

    function resetSearch() {
        $("#search-input").val("");
        $("#search-results").html("").hide();
    }

    $("#search-input").on("keyup", function () {
        clearTimeout(timer);

        let keyword = $(this).val().trim();

        if (keyword.length < 2) {
            $("#search-results").html("").hide();
            return;
        }

        timer = setTimeout(function () {
            $.ajax({
                url: "/search-products",
                method: "GET",
                data: {
                    keyword: keyword,
                },

                success: function (response) {
                    let html = "";

                    if (response.length) {
                        response.forEach(function (product) {
                            html += `
                                <a href="/product/${product.slug}"
                                   class="search-result-item">

                                    <img src="${product.image_url}"
                                         alt="${product.name}">

                                    <div>
                                        <h6>${product.name}</h6>
                                    </div>

                                </a>
                            `;
                        });
                    } else {
                        html = `
                            <div class="search-empty">
                                Không tìm thấy sản phẩm
                            </div>
                        `;
                    }

                    $("#search-results").html(html).show();
                },

                error: function () {
                    $("#search-results").html("").hide();
                },
            });
        }, 300);
    });

    // Click nút đóng (×)
    $(".search-close").on("click", function () {
        resetSearch();
    });

    // Nhấn ESC
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") {
            resetSearch();
        }
    });

    // Click ra ngoài form tìm kiếm
    $(document).on("click", function (e) {
        if (
            !$(e.target).closest(".search-form-box").length &&
            !$(e.target).closest(".header-search-form").length
        ) {
            $("#search-results").hide();
        }
    });
});
