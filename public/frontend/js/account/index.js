(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const AccountPage = {
        el: {},

        init() {
            this.cache();

            this.bindModal();

            this.bindAvatarUpload();

            this.bindProfileForm();
            this.bindPasswordForm();

            this.bindPasswordToggle();

            this.bindOrderDetail();

            this.bindCancelOrder();
        },

        cache() {
            this.el.body = $("body");

            this.el.profileModal = $("#profileModal");
            this.el.passwordModal = $("#passwordModal");

            this.el.profileForm = $("#profileForm");
            this.el.passwordForm = $("#passwordForm");

            this.el.avatarInput = $("#avatarInput");
            this.el.avatarPreview = $("#avatarPreview");
            this.el.avatarWrapper = $(".profile-avatar");
        },

        /* ==========================================
         * MODAL
         * ========================================== */
        bindModal() {
            const self = this;

            $(document).on("click", "[data-modal-open]", function () {
                const target = $(this).data("modal-open");

                $(target).addClass("show");

                self.el.body.addClass("overflow-hidden");
            });

            $(document).on(
                "click",
                "[data-modal-close], .modal-close",
                function () {
                    $(this)
                        .closest(".account-modal-overlay")
                        .removeClass("show");

                    self.el.body.removeClass("overflow-hidden");
                },
            );

            $(document).on("click", ".account-modal-overlay", function (e) {
                if ($(e.target).is(".account-modal-overlay")) {
                    $(this).removeClass("show");

                    self.el.body.removeClass("overflow-hidden");
                }
            });

            $(document).on("keydown", function (e) {
                if (e.key === "Escape") {
                    $(".account-modal-overlay").removeClass("show");

                    self.el.body.removeClass("overflow-hidden");
                }
            });
        },

        /* ==========================================
         * AVATAR
         * ========================================== */
        bindAvatarUpload() {
            const self = this;

            $(document).on("click", ".profile-avatar", function () {
                $("#avatarInput")[0].click();
            });

            $(document).on("change", "#avatarInput", function () {
                const file = this.files[0];

                if (!file) {
                    return;
                }

                const formData = new FormData();

                formData.append("avatar", file);

                self.el.avatarWrapper.addClass("loading");

                $.ajax({
                    url: window.accountConfig.routes.avatarUpdate,

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },

                    success(res) {
                        if (res.avatar_url) {
                            self.el.avatarPreview.attr(
                                "src",
                                res.avatar_url + "?" + Date.now(),
                            );
                        }

                        toastr.success(res.message);
                    },

                    error(xhr) {
                        self.handleAjaxError(xhr);
                    },

                    complete() {
                        self.el.avatarWrapper.removeClass("loading");

                        self.el.avatarInput.val("");
                    },
                });
            });
        },

        /* ==========================================
         * PROFILE UPDATE
         * ========================================== */
        bindProfileForm() {
            const self = this;

            $(document).on("submit", "#profileForm", function (e) {
                e.preventDefault();

                const form = $(this);

                const btn = form.find(".modal-submit-btn");

                self.clearErrors(form);

                btn.prop("disabled", true).text("Đang cập nhật...");

                $.ajax({
                    url: window.accountConfig.routes.profileUpdate,

                    type: "POST",

                    data: form.serialize(),

                    success(res) {
                        toastr.success(res.message);

                        self.el.profileModal.removeClass("show");

                        self.el.body.removeClass("overflow-hidden");

                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    },

                    error(xhr) {
                        self.handleValidationErrors(form, xhr);
                    },

                    complete() {
                        btn.prop("disabled", false).text("CẬP NHẬT THÔNG TIN");
                    },
                });
            });
        },

        /* ==========================================
         * PASSWORD UPDATE
         * ========================================== */
        bindPasswordForm() {
            const self = this;

            $(document).on("submit", "#passwordForm", function (e) {
                e.preventDefault();

                const form = $(this);

                const btn = form.find(".modal-submit-btn");

                self.clearErrors(form);

                btn.prop("disabled", true).text("Đang cập nhật...");

                $.ajax({
                    url: window.accountConfig.routes.passwordUpdate,

                    type: "POST",

                    data: form.serialize(),

                    success(res) {
                        toastr.success(res.message);

                        form[0].reset();

                        self.el.passwordModal.removeClass("show");

                        self.el.body.removeClass("overflow-hidden");
                    },

                    error(xhr) {
                        self.handleValidationErrors(form, xhr);
                    },

                    complete() {
                        btn.prop("disabled", false).text("CẬP NHẬT MẬT KHẨU");
                    },
                });
            });
        },

        /* ==========================================
         * PASSWORD TOGGLE
         * ========================================== */
        bindPasswordToggle() {
            $(document).on("click", ".toggle-password", function () {
                const input = $(this).siblings("input");

                const icon = $(this).find("i");

                const type = input.attr("type");

                if (type === "password") {
                    input.attr("type", "text");

                    icon.removeClass("fa-eye");

                    icon.addClass("fa-eye-slash");
                } else {
                    input.attr("type", "password");

                    icon.removeClass("fa-eye-slash");

                    icon.addClass("fa-eye");
                }
            });
        },

        /* ==========================================
         * ORDER DETAIL
         * ========================================== */
        bindOrderDetail() {
            const self = this;

            $(document).on("click", ".view-order-btn", function () {
                const orderId = $(this).data("id");

                const btn = $(this);

                btn.prop("disabled", true).text("Đang tải...");

                $.ajax({
                    url: window.accountConfig.routes.orderDetail.replace(
                        "__ID__",
                        orderId,
                    ),

                    type: "GET",

                    success(res) {
                        $("#orderDetailContent").html(res.html);

                        $("#orderDetailModal").addClass("show");

                        self.el.body.addClass("overflow-hidden");
                    },

                    error(xhr) {
                        let message = "Không thể tải chi tiết đơn hàng";

                        if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        toastr.error(message);
                    },

                    complete() {
                        btn.prop("disabled", false).text("Chi tiết đơn hàng");
                    },
                });
            });
        },

        bindCancelOrder() {
            const self = this;

            $(document).on("click", ".cancel-order-btn", function () {
                const orderId = $(this).data("id");

                $("#cancelOrderId").val(orderId);
                $("#cancelOrderModal").addClass("show");
                $("body").addClass("overflow-hidden");
            });

            $(document).on(
                "change",
                'input[name="cancel_reason"]',
                function () {
                    if ($(this).val() === "Lý do khác") {
                        $("#otherReason").slideDown(200);
                    } else {
                        $("#otherReason").slideUp(200).val("");
                    }
                },
            );

            $(document).on("submit", "#cancelOrderForm", function (e) {
                e.preventDefault();

                const orderId = $("#cancelOrderId").val();

                let reason = $('input[name="cancel_reason"]:checked').val();

                if (!reason) {
                    toastr.warning("Vui lòng chọn lý do hủy đơn");
                    return;
                }

                if (reason === "Lý do khác") {
                    reason = $("#otherReason").val();

                    if (!reason.trim()) {
                        toastr.warning("Vui lòng nhập lý do");
                        return;
                    }
                }

                $.ajax({
                    url: window.accountConfig.routes.cancelOrder.replace(
                        "__ID__",
                        orderId,
                    ),
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        cancel_reason: reason,
                    },
                    success(res) {
                        toastr.success(res.message);

                        $("#cancelOrderModal").removeClass("show");

                        setTimeout(() => location.reload(), 800);
                    },
                    error(xhr) {
                        toastr.error(
                            xhr.responseJSON?.message || "Có lỗi xảy ra",
                        );
                    },
                });
            });
        },

        /* ==========================================
         * VALIDATION
         * ========================================== */
        handleValidationErrors(form, xhr) {
            if (xhr.status !== 422) {
                this.handleAjaxError(xhr);

                return;
            }

            const errors = xhr.responseJSON?.errors || {};

            $.each(errors, function (field, messages) {
                const errorEl = form.find(`[data-error="${field}"]`);

                errorEl.text(messages[0]);

                errorEl
                    .closest(".floating-group")
                    .find(".input-box")
                    .addClass("error");
            });
        },

        clearErrors(form) {
            form.find(".input-error").text("");

            form.find(".input-box").removeClass("error success");
        },

        /* ==========================================
         * AJAX ERROR
         * ========================================== */
        handleAjaxError(xhr) {
            let message = "Có lỗi xảy ra";

            if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            }

            toastr.error(message);
        },
    };

    window.App.AccountPage = AccountPage;

    $(function () {
        AccountPage.init();
    });
})(jQuery, window);
