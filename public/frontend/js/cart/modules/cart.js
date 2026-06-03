(function (window) {
    window.CartModule = {
        bindCartEvents() {
            const self = this;

            $(document).on(
                "click",
                ".qty-increase, .qty-decrease",
                function () {
                    let button = $(this);

                    let variantId = button.data("variant");

                    let input = $(".qty-input-" + variantId);

                    let qty = parseInt(input.val());

                    if (button.hasClass("qty-increase")) {
                        qty++;
                    } else {
                        if (qty <= 1) {
                            return;
                        }

                        qty--;
                    }

                    self.updateQuantity(variantId, qty);
                },
            );

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

            $(document).on("click", ".remove-cart-item", function () {
                self.removeItem($(this).data("variant"));
            });

            $(document).on("click", ".cart-clear-btn", function () {
                if (!confirm("Bạn muốn xóa toàn bộ giỏ hàng?")) {
                    return;
                }

                self.clearCart();
            });

            $(document).on("change", ".cart-item-checkbox", function () {
                self.selectItem($(this));
            });

            $(document).on("change", "#select-all", function () {
                self.selectAll($(this).is(":checked") ? 1 : 0);
            });

            $(document).on("click", "#apply-coupon-btn", function () {
                self.applyCoupon();
            });

            $(document).on("click", ".apply-coupon-card", function (e) {
                if ($(e.target).closest(".remove-coupon-btn").length) return;

                $(".voucher-card").removeClass("active");
                $(this).addClass("active");

                $("#coupon-code").val($(this).data("code"));
                $("#apply-coupon-btn").click();
            });

            $(document).on("click", ".remove-coupon-btn", function (e) {
                e.stopPropagation();

                self.removeCoupon();
            });

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
        },

        loadCartItems(resetCoupon = false) {
            let couponCode = $(this.el.coupon).val();

            $(this.el.loading).addClass("loading");

            this.get(window.cartConfig.routes.cartItems)
                .done((res) => {
                    $(this.el.cartWrapper).html(res.items);

                    $(this.el.fixedBar).html(res.fixedBar);

                    if (!resetCoupon) {
                        $(this.el.coupon).val(couponCode);
                    }

                    $(this.el.loading).removeClass("loading");
                })
                .fail(() => {
                    $(this.el.loading).removeClass("loading");

                    toastr.error("Không thể tải giỏ hàng");
                });
        },

        updateQuantity(id, qty) {
            this.post(window.cartConfig.routes.cartUpdate, {
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
                    toastr.error(xhr.responseJSON?.message);
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

        removeItem(id) {
            this.delete(window.cartConfig.routes.cartRemove, {
                _token: window.cartConfig.csrf,
                variant_id: id,
            }).done((res) => {
                this.loadCartItems();

                loadHeaderCart();

                toastr.success(res.message);
            });
        },

        clearCart() {
            this.delete(window.cartConfig.routes.cartClear, {
                _token: window.cartConfig.csrf,
            }).done((res) => {
                this.loadCartItems();

                loadHeaderCart();

                toastr.success(res.message);
            });
        },

        selectItem($el) {
            this.post(window.cartConfig.routes.select, {
                _token: window.cartConfig.csrf,
                variant_id: $el.data("variant"),
                selected: $el.is(":checked") ? 1 : 0,
            }).done(() => {
                this.loadCartItems();
            });
        },

        selectAll(selected) {
            this.post(window.cartConfig.routes.selectAll, {
                _token: window.cartConfig.csrf,
                selected,
            }).done(() => {
                this.loadCartItems();
            });
        },

        applyCoupon() {
            let code = $("#coupon-code").val().trim();

            if (!code) {
                toastr.error("Vui lòng nhập mã giảm giá");
                return;
            }

            let checkedItems = $(".cart-item-checkbox:checked").length;

            if (checkedItems <= 0) {
                toastr.error("Vui lòng chọn sản phẩm");

                return;
            }

            this.post(window.cartConfig.routes.applyCoupon, {
                _token: window.cartConfig.csrf,
                coupon_code: code,
            }).done((res) => {
                this.loadCartItems();

                toastr.success(res.message);
            });
        },

        removeCoupon() {
            this.delete(window.cartConfig.routes.removeCoupon, {
                _token: window.cartConfig.csrf,
            }).done((res) => {
                this.loadCartItems(true);

                toastr.success(res.message);
            });
        },
    };
})(window);
