(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const OrderDetailPage = {
        el: {},

        init() {
            this.cache();
            this.bind();
        },

        cache() {
            this.el.orderId = $("#order_id");

            this.el.orderStatus = $("#order_status");
            this.el.paymentStatus = $("#payment_status");

            this.el.updateOrderBtn = $("#updateOrderBtn");
            this.el.updatePaymentBtn = $("#updatePaymentBtn");
        },

        bind() {
            const self = this;

            self.el.updateOrderBtn.on("click", function () {
                self.updateOrderStatus();
            });

            self.el.updatePaymentBtn.on("click", function () {
                self.updatePaymentStatus();
            });
        },

        updateOrderStatus() {
            const status = this.el.orderStatus.val();

            if (!status) {
                toastr.warning("Vui lòng chọn trạng thái đơn hàng");

                return;
            }

            $.ajax({
                url: window.orderDetailConfig.routes.updateStatus,

                type: "POST",

                data: {
                    id: this.el.orderId.val(),
                    status: status,
                },

                success(res) {
                    toastr.success(res.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },

                error(xhr) {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                },
            });
        },

        updatePaymentStatus() {
            const status = this.el.paymentStatus.val();

            if (!status) {
                toastr.warning("Vui lòng chọn trạng thái thanh toán");

                return;
            }

            $.ajax({
                url: window.orderDetailConfig.routes.updatePaymentStatus,

                type: "POST",

                data: {
                    id: this.el.orderId.val(),
                    status: status,
                },

                success(res) {
                    toastr.success(res.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },

                error(xhr) {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                },
            });
        },
    };

    window.App.OrderDetailPage = OrderDetailPage;

    $(function () {
        OrderDetailPage.init();
    });
})(jQuery, window);
