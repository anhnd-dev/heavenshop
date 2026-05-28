(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const CartPage = {
        el: {},

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();
            this.bindCartEvents();
            this.bindCheckoutEvents();
            this.bindAuthEvents();
            this.restoreCheckoutData();
            this.autoLoadAddress();
            this.loadCartItems();
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            this.el.cartWrapper = "#cart-items-wrapper";
            this.el.fixedBar = "#fixed-bar-wrapper";
            this.el.loading = ".cart-loading-parent";
            this.el.coupon = "#coupon-code";
            this.el.addressId = "#customer_address_id";
            this.el.orderBtn = "#fixed-order-btn";
            this.el.authModal = "#checkoutAuthModal";
        },

        // =========================
        // LOAD CART ITEMS
        // =========================
        loadCartItems(resetCoupon = false) {
            let couponCode = $(this.el.coupon).val();

            $(this.el.loading).addClass("loading");

            $.ajax({
                url: window.cartConfig.routes.cartItems,
                type: "GET",
                success: (res) => {
                    $(this.el.cartWrapper).html(res.items);
                    $(this.el.fixedBar).html(res.fixedBar);

                    if (!resetCoupon) {
                        $(this.el.coupon).val(couponCode);
                    }

                    $(this.el.loading).removeClass("loading");
                },
                error: () => {
                    $(this.el.loading).removeClass("loading");
                    toastr.error("Không thể tải giỏ hàng");
                },
            });
        },

        // =========================
        // CART EVENTS
        // =========================
        bindCartEvents() {
            const self = this;

            // QTY
            $(document).on(
                "click",
                ".qty-increase, .qty-decrease",
                function () {
                    let button = $(this);
                    let variantId = button.data("variant");
                    let input = $(".qty-input-" + variantId);
                    let current = parseInt(input.val());

                    if (button.hasClass("qty-increase")) current++;
                    else {
                        if (current <= 1) return;
                        current--;
                    }

                    self.updateQuantity(variantId, current);
                },
            );

            // REMOVE ITEM
            $(document).on("click", ".remove-cart-item", function () {
                self.removeItem($(this).data("variant"));
            });

            // VARIANT CHANGE
            $(document).on(
                "change",
                ".cart-color-select, .cart-size-select",
                function () {
                    let wrapper = $(this).closest(".cart-variant-select");

                    self.changeVariant(
                        wrapper,
                        $(this).data("variant"),
                        wrapper.find(".cart-color-select").val(),
                        wrapper.find(".cart-size-select").val(),
                    );
                },
            );

            // CLEAR CART
            $(document).on("click", ".cart-clear-btn", function () {
                if (!confirm("Bạn muốn xóa toàn bộ giỏ hàng?")) return;
                self.clearCart();
            });

            // SELECT ITEM
            $(document).on("change", ".cart-item-checkbox", function () {
                self.selectItem($(this));
            });

            // SELECT ALL
            $(document).on("change", "#select-all", function () {
                self.selectAll($(this).is(":checked") ? 1 : 0);
            });

            // APPLY COUPON
            $(document).on("click", "#apply-coupon-btn", function () {
                self.applyCoupon();
            });

            // QUICK COUPON
            $(document).on("click", ".apply-coupon-card", function (e) {
                if ($(e.target).closest(".remove-coupon-btn").length) return;

                $(".voucher-card").removeClass("active");
                $(this).addClass("active");

                $("#coupon-code").val($(this).data("code"));
                $("#apply-coupon-btn").click();
            });

            // REMOVE COUPON
            $(document).on("click", ".remove-coupon-btn", function (e) {
                e.stopPropagation();
                self.removeCoupon();
            });
        },

        // =========================
        // CHECKOUT EVENTS
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

            // SINGLE ADDRESS SELECT
            $(document).on("change", ".saved-address-radio", function () {
                $("input[name='save_address']").prop("checked", false);

                let id = $(this).val();

                $(self.el.addressId).val(id);

                self.loadSavedAddress(id);
            });

            // MANUAL INPUT CLEAR ADDRESS
            $(document).on(
                "input",
                "input[name='shipping_name'],input[name='shipping_phone'],input[name='shipping_email'],input[name='shipping_address']",
                function () {
                    $(self.el.addressId).val("");
                },
            );

            // CLEAR ERRORS LIVE
            $(document).on(
                "input change",
                ".checkout-input, .checkout-select, .checkout-textarea",
                function () {
                    let group = $(this).closest(".checkout-group");
                    $(this).removeClass("error");
                    group.find("span.error").removeClass("show").text("");
                },
            );
        },

        // =========================
        // AUTH EVENTS
        // =========================
        bindAuthEvents() {
            const self = this;

            $(document).on(
                "click",
                ".checkout-auth-close, .checkout-auth-overlay",
                function () {
                    self.closeAuthModal();
                },
            );

            $(document).on("click", "#show-register-form", function (e) {
                e.preventDefault();
                $("#checkout-login-form").hide();
                $("#checkout-register-form").fadeIn(200);
                $("#auth-modal-title").text("Tạo tài khoản");
                $("#auth-modal-description").text(
                    "Đăng ký tài khoản để tiếp tục thanh toán",
                );
            });

            $(document).on("click", "#show-login-form", function (e) {
                e.preventDefault();
                $("#checkout-register-form").hide();
                $("#checkout-login-form").fadeIn(200);
                $("#auth-modal-title").text("Đăng nhập để tiếp tục đặt hàng");
                $("#auth-modal-description").text(
                    "Vui lòng đăng nhập tài khoản khách hàng trước",
                );
            });

            $(document).on("submit", "#checkout-login-form", function (e) {
                e.preventDefault();
                self.ajaxLogin($(this));
            });

            $(document).on("submit", "#checkout-register-form", function (e) {
                e.preventDefault();
                self.ajaxRegister($(this));
            });
        },

        // =========================
        // CART ACTIONS
        // =========================
        updateQuantity(id, qty) {
            $.post(window.cartConfig.routes.cartUpdate, {
                _token: window.cartConfig.csrf,
                variant_id: id,
                quantity: qty,
            })
                .done((res) => {
                    this.loadCartItems();
                    loadHeaderCart();
                    toastr.success(res.message);
                })
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                });
        },

        removeItem(id) {
            $.ajax({
                url: window.cartConfig.routes.cartRemove,
                type: "DELETE",
                data: {
                    variant_id: id,
                    _token: window.cartConfig.csrf,
                },
                success: (res) => {
                    this.loadCartItems();
                    loadHeaderCart();
                    toastr.success(res.message);
                },
                error: (xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                },
            });
        },

        changeVariant(wrapper, oldId, colorId, sizeId) {
            wrapper.addClass("loading");

            $.post(window.cartConfig.routes.changeVariant, {
                _token: window.cartConfig.csrf,
                old_variant_id: oldId,
                color_id: colorId,
                size_id: sizeId,
            })
                .done((res) => {
                    this.loadCartItems();
                    loadHeaderCart();
                    toastr.success(res.message);
                })
                .fail((xhr) => {
                    wrapper.removeClass("loading");
                    toastr.error(xhr.responseJSON.message);
                });
        },

        selectItem($el) {
            $.post(window.cartConfig.routes.select, {
                _token: window.cartConfig.csrf,
                variant_id: $el.data("variant"),
                selected: $el.is(":checked") ? 1 : 0,
            })
                .done(() => this.loadCartItems())
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                });
        },

        selectAll(val) {
            $.post(window.cartConfig.routes.selectAll, {
                _token: window.cartConfig.csrf,
                selected: val,
            })
                .done(() => {
                    this.loadCartItems();
                    toastr.success("Đã cập nhật");
                })
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                });
        },

        clearCart() {
            $.ajax({
                url: window.cartConfig.routes.cartClear,
                type: "DELETE",
                data: { _token: window.cartConfig.csrf },
                success: (res) => {
                    this.loadCartItems();
                    loadHeaderCart();
                    toastr.success(res.message);
                },
            });
        },

        applyCoupon() {
            let code = $("#coupon-code").val().trim();
            if (!code) return toastr.error("Vui lòng nhập mã giảm giá");

            let checkedItems = $(".cart-item-checkbox:checked").length;

            if (checkedItems <= 0) {
                toastr.error("Vui lòng chọn sản phẩm");

                return;
            }

            $.post(window.cartConfig.routes.applyCoupon, {
                _token: window.cartConfig.csrf,
                coupon_code: code,
            })
                .done((res) => {
                    this.loadCartItems();
                    toastr.success(res.message);
                })
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                });
        },

        removeCoupon() {
            $.ajax({
                url: window.cartConfig.routes.removeCoupon,
                type: "DELETE",
                data: { _token: window.cartConfig.csrf },
                success: (res) => {
                    this.loadCartItems(true);
                    toastr.success(res.message);
                },
                error: (xhr) => {
                    toastr.error(xhr.responseJSON.message);
                },
            });
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

            if (!method) return toastr.error("Vui lòng chọn thanh toán");

            const isLoggedIn = $("body").data("auth") == 1;

            if (!isLoggedIn) {
                this.openAuthModal();
                return;
            }

            btn.prop("disabled", true).text("Đang xử lý...");

            $.post(window.cartConfig.routes.checkoutPlace, {
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
            })
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
                    toastr.error(xhr.responseJSON.message);
                    btn.prop("disabled", false).text("Đặt hàng");
                });
        },

        // =========================
        // ADDRESS
        // =========================
        loadSavedAddress(id) {
            $(this.el.addressId).val(id);

            $(".saved-address-radio")
                .prop("checked", false)
                .filter(`[value="${id}"]`)
                .prop("checked", true);

            $.get(window.cartConfig.routes.customerAddress, { id })
                .done(async (res) => {
                    $("input[name='shipping_name']").val(res.full_name);
                    $("input[name='shipping_phone']").val(res.phone);
                    $("input[name='shipping_email']").val(res.email);
                    $("input[name='shipping_address']").val(res.address);

                    // 👉 trigger province
                    $(".province").val(res.province_id).trigger("change");

                    // 👉 đợi district load xong bằng polling nhẹ (KHÔNG event)
                    const waitDistrict = () =>
                        new Promise((resolve) => {
                            let check = setInterval(() => {
                                if ($(".district option").length > 1) {
                                    clearInterval(check);
                                    resolve();
                                }
                            }, 50);
                        });

                    await waitDistrict();

                    $(".district").val(res.district_id).trigger("change");

                    const waitWard = () =>
                        new Promise((resolve) => {
                            let check = setInterval(() => {
                                if ($(".ward option").length > 1) {
                                    clearInterval(check);
                                    resolve();
                                }
                            }, 50);
                        });

                    await waitWard();

                    $(".ward").val(res.ward_id);
                })
                .fail(() => toastr.error("Không thể tải địa chỉ"));
        },

        // =========================
        // AUTH MODAL
        // =========================
        openAuthModal() {
            const modal = $(this.el.authModal);
            modal.css({ display: "block" }).addClass("show");
            $("body").css("overflow", "hidden");
        },

        closeAuthModal() {
            const modal = $(this.el.authModal);
            modal.removeClass("show");

            setTimeout(() => {
                modal.css({ display: "none" });
            }, 350);

            $("body").css("overflow", "");
        },

        ajaxLogin(form) {
            let btn = form.find('button[type="submit"]');

            btn.prop("disabled", true).text("Đang đăng nhập...");

            $.ajax({
                url: window.cartConfig.routes.login,
                type: "POST",
                data: form.serialize(),
                success: (res) => {
                    toastr.success(res.message);

                    this.closeAuthModal();

                    $("body").css("overflow", "");

                    location.reload();
                },
                error: (xhr) => {
                    toastr.error(
                        xhr.responseJSON?.message || "Đăng nhập thất bại",
                    );

                    btn.prop("disabled", false).text("Đăng nhập");
                },
            });
        },

        ajaxRegister(form) {
            let btn = form.find('button[type="submit"]');

            btn.prop("disabled", true).text("Đang xử lý...");

            $.ajax({
                url: window.cartConfig.routes.register,
                type: "POST",
                data: form.serialize(),
                success: (res) => {
                    toastr.success(res.message);
                    location.reload();
                },
                error: (xhr) => {
                    toastr.error(
                        xhr.responseJSON?.message || "Đăng ký thất bại",
                    );

                    btn.prop("disabled", false).text("Đăng ký tài khoản");
                },
            });
        },

        // =========================
        // VALIDATION (FULL)
        // =========================
        setError(field, message) {
            let el = $(`[name="${field}"]`);
            el.addClass("error");
            el.closest(".checkout-group")
                .find("span.error")
                .text(message)
                .addClass("show");
        },

        clearErrors() {
            $(
                ".checkout-input, .checkout-select, .checkout-textarea",
            ).removeClass("error");

            $("span.error").text("").removeClass("show");
        },

        validateCheckoutForm() {
            this.clearErrors();

            let name = $("input[name='shipping_name']").val()?.trim();
            let phone = $("input[name='shipping_phone']").val()?.trim();
            let email = $("input[name='shipping_email']").val()?.trim();
            let address = $("input[name='shipping_address']").val()?.trim();
            let province = $("select[name='shipping_province']").val();
            let district = $("select[name='shipping_district']").val();
            let ward = $("select[name='shipping_ward']").val();

            let hasError = false;

            if (!name) {
                this.setError("shipping_name", "Vui lòng nhập họ tên");
                hasError = true;
            }

            if (!phone) {
                this.setError("shipping_phone", "Vui lòng nhập số điện thoại");
                hasError = true;
            } else if (!/^(0|\+84)[0-9]{9}$/.test(phone)) {
                this.setError("shipping_phone", "Số điện thoại không hợp lệ");
                hasError = true;
            }

            if (!email) {
                this.setError("shipping_email", "Vui lòng nhập email");
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                this.setError("shipping_email", "Email không hợp lệ");
                hasError = true;
            }

            if (!address) {
                this.setError("shipping_address", "Vui lòng nhập địa chỉ");
                hasError = true;
            }
            if (!province) {
                this.setError(
                    "shipping_province",
                    "Vui lòng chọn tỉnh/thành phố",
                );
                hasError = true;
            }
            if (!district) {
                this.setError("shipping_district", "Vui lòng chọn quận/huyện");
                hasError = true;
            }
            if (!ward) {
                this.setError("shipping_ward", "Vui lòng chọn phường/xã");
                hasError = true;
            }

            return !hasError;
        },

        // =========================
        // RESTORE + AUTO ADDRESS
        // =========================
        restoreCheckoutData() {
            let data = sessionStorage.getItem("checkout_shipping_data");
            if (!data) return;

            data = JSON.parse(data);

            $("input[name='shipping_name']").val(data.shipping_name);
            $("input[name='shipping_phone']").val(data.shipping_phone);
            $("input[name='shipping_email']").val(data.shipping_email);
            $("input[name='shipping_address']").val(data.shipping_address);
            $("textarea[name='note']").val(data.note);

            $(".province").val(data.shipping_province).trigger("change");

            setTimeout(
                () =>
                    $(".district")
                        .val(data.shipping_district)
                        .trigger("change"),
                400,
            );
            setTimeout(() => $(".ward").val(data.shipping_ward), 800);

            sessionStorage.removeItem("checkout_shipping_data");
        },

        autoLoadAddress() {
            let radios = $(".saved-address-radio");

            if (!radios.length) {
                $("#customer_address_id").val("");
                $("input[name='save_address']").prop("checked", true);
                return;
            }

            let checked = radios.filter(":checked");

            if (!checked.length) {
                radios.first().prop("checked", true).trigger("change");
                return;
            }

            checked.trigger("change");
        },
    };

    window.App.CartPage = CartPage;

    $(function () {
        CartPage.init();
    });
})(jQuery, window);
