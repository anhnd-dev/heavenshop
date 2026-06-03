(function (window) {
    "use strict";

    window.CheckoutModule = {
        // =========================
        // EVENTS
        // =========================
        bindCheckoutEvents() {
            const self = this;

            // PAYMENT SELECT
            $(document).on("click", ".payment-item", function () {
                $(".payment-item").removeClass("active");

                $(this).addClass("active");
            });

            // PLACE ORDER
            $(document).on("click", this.el.orderBtn, function () {
                self.placeOrder($(this));
            });

            // ADDRESS SELECT
            $(document).on("change", ".saved-address-radio", function () {
                $("input[name='save_address']").prop("checked", false);

                let id = $(this).val();

                $(self.el.addressId).val(id);

                self.loadSavedAddress(id);
            });

            // MANUAL INPUT
            $(document).on(
                "input",
                `
                input[name='shipping_name'],
                input[name='shipping_phone'],
                input[name='shipping_email'],
                input[name='shipping_address']
                `,
                function () {
                    $(self.el.addressId).val("");
                },
            );

            // CLEAR ERROR LIVE
            $(document).on(
                "input change",
                `
                .checkout-input,
                .checkout-select,
                .checkout-textarea
                `,
                function () {
                    let group = $(this).closest(".checkout-group");

                    $(this).removeClass("error");

                    group.find("span.error").removeClass("show").text("");
                },
            );
        },

        // =========================
        // PLACE ORDER
        // =========================
        placeOrder(btn) {
            let method = $(".payment-item.active").data("method");

            if (!this.validateCheckoutForm()) {
                toastr.error("Vui lòng kiểm tra lại thông tin");

                return;
            }

            if (!method) {
                toastr.error("Vui lòng chọn phương thức thanh toán");

                return;
            }

            const isLoggedIn = $("body").data("auth") == 1;

            if (!isLoggedIn) {
                if (window.App.AuthModal) {
                    window.App.AuthModal.open();
                }

                return;
            }

            btn.prop("disabled", true).text("Đang xử lý...");

            const payload = {
                _token: window.cartConfig.csrf,

                customer_address_id: $(this.el.addressId).val(),

                shipping_name: $("input[name='shipping_name']").val(),

                shipping_phone: $("input[name='shipping_phone']").val(),

                shipping_email: $("input[name='shipping_email']").val(),

                shipping_address: $("input[name='shipping_address']").val(),

                shipping_province: $("select[name='shipping_province']").val(),

                shipping_district: $("select[name='shipping_district']").val(),

                shipping_ward: $("select[name='shipping_ward']").val(),

                note: $("textarea[name='note']").val(),

                payment_method: method,

                coupon_code: $("#coupon-code").val(),

                save_address: $("input[name='save_address']").is(":checked")
                    ? 1
                    : 0,
            };

            this.post(window.cartConfig.routes.checkoutPlace, payload)
                .done((res) => {
                    toastr.success(res.message);

                    if (method === "cod") {
                        window.location.href =
                            "/order/success/" + res.order_code;

                        return;
                    }

                    if (res.payment_url) {
                        window.location.href = res.payment_url;

                        return;
                    }

                    btn.prop("disabled", false).text("Đặt hàng");
                })
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");

                    btn.prop("disabled", false).text("Đặt hàng");
                });
        },

        // =========================
        // RESTORE DATA
        // =========================
        restoreCheckoutData() {
            let data = sessionStorage.getItem("checkout_shipping_data");

            if (!data) {
                return;
            }

            data = JSON.parse(data);

            $("input[name='shipping_name']").val(data.shipping_name);

            $("input[name='shipping_phone']").val(data.shipping_phone);

            $("input[name='shipping_email']").val(data.shipping_email);

            $("input[name='shipping_address']").val(data.shipping_address);

            $("textarea[name='note']").val(data.note);

            $(".province").val(data.shipping_province).trigger("change");

            setTimeout(() => {
                $(".district").val(data.shipping_district).trigger("change");
            }, 400);

            setTimeout(() => {
                $(".ward").val(data.shipping_ward);
            }, 800);

            sessionStorage.removeItem("checkout_shipping_data");
        },
    };
})(window);
