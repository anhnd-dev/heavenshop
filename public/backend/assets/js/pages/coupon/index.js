(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const CouponPage = {
        el: {},
        dataTable: null,
        debounceTimer: null,

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();
            this.initDataTable();
            this.bind();
            this.bindCrud();
            this.bindModalReset();
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            this.el.table = $("#coupon_datatable");

            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_coupon_form";
            this.el.editForm = "#edit_coupon_form";

            this.el.addBtn = "#add_coupon_btn";
            this.el.editBtn = "#edit_coupon_btn";

            this.el.addModal = "#addCouponModal";
            this.el.editModal = "#editCouponModal";

            this.el.checkAll = $("#checkAll");
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
                    url: window.couponConfig.routes.index,

                    data(d) {
                        d.include_trashed = self.el.includeTrashed.is(
                            ":checked",
                        )
                            ? 1
                            : 0;
                    },
                },

                columns: [
                    {
                        data: "checkbox",
                        orderable: false,
                        searchable: false,

                        render(_, __, full) {
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
                        data: "code",
                    },

                    {
                        data: "discount_type",

                        render(data) {
                            if (data === "percentage") {
                                return `
                                    <span class="badge badge-info">
                                        %
                                    </span>
                                `;
                            }

                            return `
                                <span class="badge badge-primary">
                                    VNĐ
                                </span>
                            `;
                        },
                    },

                    {
                        data: "discount",
                    },

                    {
                        data: "min_order_amount",

                        render(data) {
                            return data
                                ? Number(data).toLocaleString("vi-VN") + " đ"
                                : "-";
                        },
                    },

                    {
                        data: "max_discount_amount",

                        render(data) {
                            return data
                                ? Number(data).toLocaleString("vi-VN") + " đ"
                                : "-";
                        },
                    },

                    {
                        data: "quantity_text",
                    },

                    {
                        data: "start_date",
                    },

                    {
                        data: "end_date",
                    },

                    {
                        data: "status",
                    },

                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        },

        // =========================
        // BIND EVENTS
        // =========================
        bind() {
            const self = this;

            // include trashed
            this.el.includeTrashed.on("change", function () {
                self.toggleTrashUI($(this).is(":checked"));
                self.reload();
            });

            // check all
            this.el.checkAll.on("click", function () {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
            });

            // edit
            $(document).on("click", ".editIcon", function (e) {
                e.preventDefault();

                const id = $(this).attr("id");

                self.loadEdit(id);
            });

            // status toggle
            $(document).on("click", ".statusIcon", function (e) {
                e.preventDefault();
                self.toggleStatus($(this));
            });

            // unlimited checkbox
            $(document).on(
                "change",
                '#add_coupon_form input[name="is_unlimited"]',
                function () {
                    if ($(this).is(":checked")) {
                        $('#add_coupon_form input[name="quantity"]')
                            .val("")
                            .prop("disabled", true);
                    } else {
                        $('#add_coupon_form input[name="quantity"]').prop(
                            "disabled",
                            false,
                        );
                    }
                },
            );

            $(document).on(
                "change",
                '#edit_coupon_form input[name="is_unlimited"]',
                function () {
                    if ($(this).is(":checked")) {
                        $('#edit_coupon_form input[name="quantity"]')
                            .val("")
                            .prop("disabled", true);
                    } else {
                        $('#edit_coupon_form input[name="quantity"]').prop(
                            "disabled",
                            false,
                        );
                    }
                },
            );
        },

        // =========================
        // CRUD BINDING
        // =========================
        bindCrud() {
            if (window.setupAddHandler) {
                window.setupAddHandler(
                    this.el.addForm,
                    this.el.addBtn,
                    this.el.addModal,
                    window.couponConfig.routes.store,
                    () => this.initDataTable(),
                );
            }

            if (window.setupEditHandler) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,
                    window.couponConfig.routes.update,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteHandler) {
                window.setupDeleteHandler(
                    ".deleteIcon",
                    window.couponConfig.routes.delete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteMultipleHandler) {
                window.setupDeleteMultipleHandler(
                    "#deleteMultiple",
                    window.couponConfig.routes.deleteAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreHandler) {
                window.setupRestoreHandler(
                    ".restoreIcon",
                    window.couponConfig.routes.restore,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreAllHandler) {
                window.setupRestoreAllHandler(
                    "#restoreAll",
                    window.couponConfig.routes.restoreAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceHandler) {
                window.setupForceHandler(
                    ".forceIcon",
                    window.couponConfig.routes.forceDelete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceMultipleHandler) {
                window.setupForceMultipleHandler(
                    "#forceDeleteMultiple",
                    window.couponConfig.routes.forceDeleteAll,
                    () => this.initDataTable(),
                );
            }
        },

        // =========================
        // RESET
        // =========================
        bindModalReset() {
            // Add modal
            $("#addCouponModal").on("hidden.bs.modal", () => {
                this.resetAddForm();
            });

            // Edit modal
            $("#editCouponModal").on("hidden.bs.modal", () => {
                this.resetEditForm();
            });
        },

        // =========================
        // LOAD EDIT
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.couponConfig.routes.edit,
                method: "GET",
                data: { id },
            }).done((coupon) => {
                $("#coupon_id").val(coupon.id);

                $("#update_code").val(coupon.code);

                $("#update_discount_type").val(coupon.discount_type);

                $("#update_discount_value").val(coupon.discount_value);

                $("#update_min_order_amount").val(coupon.min_order_amount);

                $("#update_max_discount_amount").val(
                    coupon.max_discount_amount,
                );

                $("#update_quantity").val(coupon.quantity);

                $("#update_description").val(coupon.description);

                $("#update_start_date").val(
                    this.formatDateTimeLocal(coupon.start_date),
                );

                $("#update_end_date").val(
                    this.formatDateTimeLocal(coupon.end_date),
                );

                $("#update_is_unlimited").prop(
                    "checked",
                    coupon.is_unlimited == 1,
                );

                if (coupon.is_unlimited == 1) {
                    $("#update_quantity").val("").prop("disabled", true);
                } else {
                    $("#update_quantity").prop("disabled", false);
                }
            });
        },

        // =========================
        // STATUS TOGGLE
        // =========================
        toggleStatus($btn) {
            const id = $btn.attr("id");
            const current = $btn.hasClass("btn-success") ? 1 : 0;
            const newStatus = current ? 0 : 1;

            $.ajax({
                url: window.couponConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.couponConfig.csrf,
                },
            }).done((res) => {
                if (res.status === 200) {
                    $btn.toggleClass("btn-success btn-dark");
                    toastr.success(res.message);
                    this.reload();
                }
            });
        },

        // =========================
        // FORMAT DATETIME
        // =========================
        formatDateTimeLocal(date) {
            if (!date) return "";

            const d = new Date(date);

            const pad = (n) => String(n).padStart(2, "0");

            return `${d.getFullYear()}-${pad(
                d.getMonth() + 1,
            )}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        },

        // =========================
        // RESET FORM
        // =========================
        resetAddForm() {
            $("#add_coupon_form")[0].reset();

            $("#code").val("");
            $("#discount_type").val("").trigger("change");
            $("#discount_value").val("");
            $("#min_order_amount").val("");
            $("#max_discount_amount").val("");
            $("#quantity").val("");
            $("#start_date").val("");
            $("#end_date").val("");
        },

        resetEditForm() {
            $("#edit_coupon_form")[0].reset();

            $("#coupon_id").val("");

            $("#update_code").val("");
            $("#update_discount_type").val("").trigger("change");
            $("#update_discount_value").val("");
            $("#update_min_order_amount").val("");
            $("#update_max_discount_amount").val("");
            $("#update_quantity").val("");
            $("#update_start_date").val("");
            $("#update_end_date").val("");
        },

        // =========================
        // TOGGLE TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? ["#restoreAll", "#forceDeleteMultiple"]
                : ["#deleteMultiple", "#addCoupon"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addCoupon"]
                : ["#restoreAll", "#forceDeleteMultiple"];

            show.forEach((e) => $(e).show());

            hide.forEach((e) => $(e).hide());
        },

        // =========================
        // RELOAD DATATABLE
        // =========================
        reload() {
            clearTimeout(this.debounceTimer);

            this.debounceTimer = setTimeout(() => {
                this.dataTable?.draw();
            }, 300);
        },
    };

    window.App.CouponPage = CouponPage;

    $(function () {
        CouponPage.init();
    });
})(jQuery, window);
