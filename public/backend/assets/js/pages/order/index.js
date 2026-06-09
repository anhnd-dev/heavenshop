(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const OrderPage = {
        el: {},

        dataTable: null,

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();

            this.initDataTable();

            this.bind();
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            this.el.table = $("#order_datatable");

            this.el.includeTrashed = $("#includeTrashedCheckbox");
        },

        // =========================
        // DATATABLE
        // =========================
        initDataTable() {
            if ($.fn.DataTable.isDataTable(this.el.table)) {
                this.el.table.DataTable().destroy();
            }

            const self = this;

            this.dataTable = this.el.table.DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                language: window.dataTableLanguage,

                ajax: {
                    url: window.orderConfig.routes.index,
                    data(d) {
                        d.include_trashed = self.el.includeTrashed.is(
                            ":checked",
                        )
                            ? 1
                            : 0;
                    },
                },

                order: [[7, "desc"]],

                columns: [
                    {
                        data: "checkbox",
                        orderable: false,
                        searchable: false,

                        render(_, __, full) {
                            if (
                                full.order_status_value !== "cancelled" &&
                                !self.el.includeTrashed.is(":checked")
                            ) {
                                return "";
                            }

                            return `
                                <div class="form-check custom-checkbox ms-2">
                                    <input type="checkbox"
                                        class="form-check-input checkbox_ids"
                                        value="${full.id}">
                                </div>
                            `;
                        },
                    },

                    {
                        data: "order_code",
                    },

                    {
                        data: "customer_name",
                    },

                    {
                        data: "customer_phone",
                    },

                    {
                        data: "grand_total",
                    },

                    {
                        data: "payment_badge",
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "order_badge",
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "created_at_format",
                    },

                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        },

        bind() {
            const self = this;

            this.el.includeTrashed.on("change", function () {
                self.reload();
            });

            $(document).on("click", ".viewOrderBtn", function () {
                const id = $(this).data("id");

                const url = window.orderConfig.routes.show.replace(
                    "__ID__",
                    id,
                );

                window.location.href = url;
            });

            window.setupAjaxActionHandler({
                button: ".deleteOrderBtn",

                route: window.orderConfig.routes.delete,

                method: "DELETE",

                confirmText: "Bạn có chắc muốn chuyển đơn hàng vào thùng rác?",

                getData: ($btn) => ({
                    id: $btn.data("id"),
                }),

                callback: () => {
                    self.reload();
                },
            });

            window.setupAjaxActionHandler({
                button: ".restoreOrderBtn",

                route: window.orderConfig.routes.restore,

                method: "POST",

                confirmText: "Bạn có chắc muốn khôi phục đơn hàng?",

                getData: ($btn) => ({
                    id: $btn.data("id"),
                }),

                callback: () => {
                    self.reload();
                },
            });
        },

        reload() {
            this.dataTable.ajax.reload();
        },
    };

    window.App.OrderPage = OrderPage;

    $(function () {
        OrderPage.init();
    });
})(jQuery, window);
