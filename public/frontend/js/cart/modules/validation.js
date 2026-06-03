(function (window) {
    "use strict";

    window.ValidationModule = {
        // =========================
        // SET ERROR
        // =========================
        setError(field, message) {
            const el = $(`[name="${field}"]`);

            el.addClass("error");

            el.closest(".checkout-group")
                .find("span.error")
                .text(message)
                .addClass("show");
        },

        // =========================
        // CLEAR ERRORS
        // =========================
        clearErrors() {
            $(
                ".checkout-input, " +
                    ".checkout-select, " +
                    ".checkout-textarea",
            ).removeClass("error");

            $("span.error").text("").removeClass("show");
        },

        // =========================
        // VALIDATE CHECKOUT
        // =========================
        validateCheckoutForm() {
            this.clearErrors();

            const name = $("input[name='shipping_name']").val()?.trim();

            const phone = $("input[name='shipping_phone']").val()?.trim();

            const email = $("input[name='shipping_email']").val()?.trim();

            const address = $("input[name='shipping_address']").val()?.trim();

            const province = $("select[name='shipping_province']").val();

            const district = $("select[name='shipping_district']").val();

            const ward = $("select[name='shipping_ward']").val();

            let hasError = false;

            // =========================
            // NAME
            // =========================
            if (!name) {
                this.setError("shipping_name", "Vui lòng nhập họ tên");

                hasError = true;
            }

            // =========================
            // PHONE
            // =========================
            if (!phone) {
                this.setError("shipping_phone", "Vui lòng nhập số điện thoại");

                hasError = true;
            } else if (!/^(0|\+84)[0-9]{9}$/.test(phone)) {
                this.setError("shipping_phone", "Số điện thoại không hợp lệ");

                hasError = true;
            }

            // =========================
            // EMAIL
            // =========================
            if (!email) {
                this.setError("shipping_email", "Vui lòng nhập email");

                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                this.setError("shipping_email", "Email không hợp lệ");

                hasError = true;
            }

            // =========================
            // ADDRESS
            // =========================
            if (!address) {
                this.setError("shipping_address", "Vui lòng nhập địa chỉ");

                hasError = true;
            }

            // =========================
            // PROVINCE
            // =========================
            if (!province) {
                this.setError(
                    "shipping_province",
                    "Vui lòng chọn tỉnh/thành phố",
                );

                hasError = true;
            }

            // =========================
            // DISTRICT
            // =========================
            if (!district) {
                this.setError("shipping_district", "Vui lòng chọn quận/huyện");

                hasError = true;
            }

            // =========================
            // WARD
            // =========================
            if (!ward) {
                this.setError("shipping_ward", "Vui lòng chọn phường/xã");

                hasError = true;
            }

            return !hasError;
        },
    };
})(window);
