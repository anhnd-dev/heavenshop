(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const ColorPage = {
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
        // CACHE DOM
        // =========================
        cache() {
            this.el.table = $("#color_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_color_form";
            this.el.editForm = "#edit_color_form";

            this.el.addBtn = "#add_color_btn";
            this.el.editBtn = "#edit_color_btn";

            this.el.deleteMultipleBtn = "#deleteMultiple";

            this.el.restoreAllBtn = "#restoreAll";

            this.el.forceDeleteMultipleBtn = "#forceDeleteMultiple";

            this.el.addModal = "#addColorModal";
            this.el.editModal = "#editColorModal";

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
                    url: window.colorConfig.routes.index,

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
                        name: "checkbox",
                        orderable: false,
                        searchable: false,

                        render(data, type, full) {
                            return `
                                <div class="form-check custom-checkbox ms-2">
                                    <input
                                        type="checkbox"
                                        class="form-check-input checkbox_ids"
                                        value="${full.id}">
                                    <label class="form-check-label"></label>
                                </div>
                            `;
                        },
                    },

                    {
                        data: "name",
                        name: "name",
                    },

                    {
                        data: "color_preview",
                        name: "code",
                    },

                    {
                        data: "is_active",

                        render(data) {
                            return Number(data) === 1
                                ? `<span class="badge badge-success">Hiển thị</span>`
                                : `<span class="badge badge-danger">Tạm khóa</span>`;
                        },
                    },

                    {
                        data: "action",
                        name: "action",
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
                self.loadEdit($(this).attr("id"));
            });

            // status toggle
            $(document).on("click", ".statusIcon", function (e) {
                e.preventDefault();
                self.toggleStatus($(this));
            });
        },

        // =========================
        // CRUD
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

                route: window.colorConfig.routes.store,

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

                route: window.colorConfig.routes.update,

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

                route: window.colorConfig.routes.delete,

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

                route: window.colorConfig.routes.deleteAll,

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

                route: window.colorConfig.routes.restore,

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

                route: window.colorConfig.routes.restoreAll,

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

                route: window.colorConfig.routes.forceDelete,

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

                route: window.colorConfig.routes.forceDeleteAll,

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
                url: window.colorConfig.routes.edit,

                method: "GET",

                data: {
                    id,
                },
            }).done((res) => {
                $("#edit_name").val(res.name);
                $("#edit_code").val(res.code);
                $("#color_id").val(res.id);
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
                url: window.colorConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.colorConfig.csrf,
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
        // RESET FORM
        // =========================
        resetAddForm() {
            $(this.el.addForm)[0].reset();

            $("#add_name").val("");
            $("#add_code").val("");
        },

        resetEditForm() {
            $(this.el.editForm)[0].reset();

            $("#color_id").val("");

            $("#edit_name").val("");
            $("#edit_code").val("");
        },

        // =========================
        // TOGGLE TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? ["#restoreAll", "#forceDeleteMultiple"]
                : ["#deleteMultiple", "#addColor"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addColor"]
                : ["#restoreAll", "#forceDeleteMultiple"];

            show.forEach((el) => $(el).show());
            hide.forEach((el) => $(el).hide());
        },

        // =========================
        // RELOAD
        // =========================
        reload() {
            clearTimeout(this.debounceTimer);

            this.debounceTimer = setTimeout(() => {
                this.dataTable?.draw();
            }, 300);
        },
    };

    // =========================
    // REGISTER
    // =========================
    window.App.ColorPage = ColorPage;

    // =========================
    // INIT
    // =========================
    $(function () {
        ColorPage.init();
    });
})(jQuery, window);
