// =========================
// LOAD HEADER CART
// =========================
function loadHeaderCart() {
    $.ajax({
        url: "/cart/mini-cart",

        type: "GET",

        success: function (res) {
            $("#cart-count").text(res.count);

            $("#cartItemList").html(res.html);
        },
    });
}

// REMOVE MINI CART ITEM
$(document).on("click", ".mini-cart-remove", function () {
    let variantId = $(this).data("variant");

    $.ajax({
        url: "/cart/remove/" + variantId,

        type: "DELETE",

        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (res) {
            // reload mini cart
            loadHeaderCart();

            toastr.success(res.message);
        },

        error: function (xhr) {
            toastr.error(xhr.responseJSON.message);
        },
    });
});

// INIT
$(document).ready(function () {
    loadHeaderCart();
});
