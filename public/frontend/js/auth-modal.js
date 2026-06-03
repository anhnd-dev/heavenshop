(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const AuthModal = {
        el: {
            modal: "#checkoutAuthModal",
            loginForm: "#checkout-login-form",
            registerForm: "#checkout-register-form",
        },

        // =========================
        // INIT
        // =========================
        init() {
            this.bindEvents();

            this.checkAutoOpen();
        },

        // =========================
        // EVENTS
        // =========================
        bindEvents() {
            // OPEN MODAL
            $(document).on("click", "#openAuthModal", (e) => {
                e.preventDefault();

                this.open();
            });

            // CLOSE MODAL
            $(document).on(
                "click",
                ".checkout-auth-close, .checkout-auth-overlay",
                () => {
                    this.close();
                },
            );

            // SHOW REGISTER
            $(document).on("click", "#show-register-form", (e) => {
                e.preventDefault();

                $(this.el.loginForm).hide();

                $(this.el.registerForm).fadeIn(200);

                $("#auth-modal-title").text("Tạo tài khoản");

                $("#auth-modal-description").text(
                    "Đăng ký tài khoản để tiếp tục",
                );
            });

            // SHOW LOGIN
            $(document).on("click", "#show-login-form", (e) => {
                e.preventDefault();

                $(this.el.registerForm).hide();

                $(this.el.loginForm).fadeIn(200);

                $("#auth-modal-title").text("Đăng nhập để tiếp tục");

                $("#auth-modal-description").text(
                    "Vui lòng đăng nhập tài khoản khách hàng",
                );
            });

            // LOGIN SUBMIT
            $(document).on("submit", this.el.loginForm, (e) => {
                e.preventDefault();

                this.ajaxLogin($(e.currentTarget));
            });

            // REGISTER SUBMIT
            $(document).on("submit", this.el.registerForm, (e) => {
                e.preventDefault();

                this.ajaxRegister($(e.currentTarget));
            });
        },

        checkAutoOpen() {
            if (window.autoOpenLoginModal) {
                this.open();
            }
        },

        // =========================
        // OPEN
        // =========================
        open() {
            const modal = $(this.el.modal);

            modal.css({
                display: "block",
            });

            setTimeout(() => {
                modal.addClass("show");
            }, 10);

            $("body").css("overflow", "hidden");
        },

        // =========================
        // CLOSE
        // =========================
        close() {
            const modal = $(this.el.modal);

            modal.removeClass("show");

            setTimeout(() => {
                modal.css({
                    display: "none",
                });
            }, 350);

            $("body").css("overflow", "");
        },

        // =========================
        // LOGIN AJAX
        // =========================
        ajaxLogin(form) {
            let btn = form.find('button[type="submit"]');

            btn.prop("disabled", true);

            btn.text("Đang đăng nhập...");

            $.ajax({
                url: window.appConfig.routes.login,

                type: "POST",

                data: form.serialize(),

                success: (res) => {
                    toastr.success(res.message);

                    this.close();

                    if (window.accountRedirect) {
                        const redirectUrl = window.accountRedirect;

                        window.accountRedirect = null;

                        window.location.href = redirectUrl;

                        return;
                    }

                    location.reload();
                },

                error: (xhr) => {
                    toastr.error(
                        xhr.responseJSON?.message || "Đăng nhập thất bại",
                    );

                    btn.prop("disabled", false);

                    btn.text("Đăng nhập");
                },
            });
        },

        // =========================
        // REGISTER AJAX
        // =========================
        ajaxRegister(form) {
            let btn = form.find('button[type="submit"]');

            btn.prop("disabled", true);

            btn.text("Đang xử lý...");

            $.ajax({
                url: window.appConfig.routes.register,

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

                    btn.prop("disabled", false);

                    btn.text("Đăng ký tài khoản");
                },
            });
        },
    };

    window.App.AuthModal = AuthModal;

    window.openLoginModal = function (redirect = null) {
        if (redirect) {
            window.accountRedirect = redirect;
        }

        if (window.App?.AuthModal) {
            window.App.AuthModal.open();
        }
    };

    $(function () {
        AuthModal.init();
    });
})(jQuery, window);
