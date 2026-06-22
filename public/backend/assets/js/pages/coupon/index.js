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

            this.el.deleteMultipleBtn = "#deleteMultiple";

            this.el.restoreAllBtn = "#restoreAll";

            this.el.forceDeleteMultipleBtn = "#forceDeleteMultiple";

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
            /*
            |--------------------------------------------------------------------------
            | Add
            |--------------------------------------------------------------------------
            */

            window.setupFormHandler({
                form: this.el.addForm,
                button: this.el.addBtn,
                modal: this.el.addModal,

                route: window.couponConfig.routes.store,

                method: "POST",

                buttonLoadingText: "Đang thêm...",
                buttonDefaultText: "Thêm",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Edit
            |--------------------------------------------------------------------------
            */
            window.setupFormHandler({
                form: this.el.editForm,
                button: this.el.editBtn,
                modal: this.el.editModal,

                route: window.couponConfig.routes.update,

                method: "PUT",

                buttonLoadingText: "Đang cập nhật...",
                buttonDefaultText: "Cập nhật",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Delete One
            |--------------------------------------------------------------------------
            */

            window.setupAjaxActionHandler({
                button: ".deleteIcon",

                route: window.couponConfig.routes.delete,

                method: "DELETE",

                getData: ($btn) => ({
                    id: $btn.attr("id"),
                }),

                confirmText: "Bạn có chắc muốn xóa danh mục này?",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Delete Multiple
            |--------------------------------------------------------------------------
            */

            window.setupBulkActionHandler({
                button: this.el.deleteMultipleBtn,

                route: window.couponConfig.routes.deleteAll,

                method: "DELETE",

                confirmText: "Bạn có chắc muốn xóa các danh mục đã chọn?",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Restore One
            |--------------------------------------------------------------------------
            */

            window.setupAjaxActionHandler({
                button: ".restoreIcon",

                route: window.couponConfig.routes.restore,

                method: "POST",

                getData: ($btn) => ({
                    id: $btn.attr("id"),
                }),

                confirmText: "Khôi phục danh mục này?",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Restore Multiple
            |--------------------------------------------------------------------------
            */

            window.setupBulkActionHandler({
                button: this.el.restoreAllBtn,

                route: window.couponConfig.routes.restoreAll,

                method: "POST",

                confirmText: "Khôi phục các danh mục đã chọn?",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Force Delete One
            |--------------------------------------------------------------------------
            */

            window.setupAjaxActionHandler({
                button: ".forceIcon",

                route: window.couponConfig.routes.forceDelete,

                method: "DELETE",

                getData: ($btn) => ({
                    id: $btn.attr("id"),
                }),

                confirmText: "Bạn có chắc muốn xóa vĩnh viễn danh mục này?",

                callback: () => this.reload(),
            });

            /*
            |--------------------------------------------------------------------------
            | Force Delete Multiple
            |--------------------------------------------------------------------------
            */

            window.setupBulkActionHandler({
                button: this.el.forceDeleteMultipleBtn,

                route: window.couponConfig.routes.forceDeleteAll,

                method: "DELETE",

                confirmText:
                    "Bạn có chắc muốn xóa vĩnh viễn các danh mục đã chọn?",

                callback: () => this.reload(),
            });
        },

        // =========================
        // RESET
        // =========================
        bindModalReset() {
            $(this.el.addModal).on("hidden.bs.modal", () => {
                this.resetAddForm();
            });

            $(this.el.editModal).on("hidden.bs.modal", () => {
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
            $(this.el.addForm)[0].reset();

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
            $(this.el.editForm)[0].reset();

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
