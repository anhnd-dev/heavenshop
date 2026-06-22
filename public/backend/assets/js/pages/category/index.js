(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const CategoryPage = {
        el: {},
        dataTable: null,
        debounceTimer: null,

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();
            this.initPlugins();
            this.initDataTable();
            this.bind();
            this.bindCrud();
            this.bindModalReset();
        },

        // =========================
        // CACHE DOM
        // =========================
        cache() {
            this.el.table = $("#category_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.checkAll = $("#checkAll");

            this.el.addType = $("#add_type");
            this.el.editType = $("#edit_type");

            this.el.addForm = "#add_category_form";
            this.el.editForm = "#edit_category_form";

            this.el.addBtn = "#add_category_btn";
            this.el.editBtn = "#edit_category_btn";

            this.el.deleteMultipleBtn = "#deleteMultiple";

            this.el.restoreAllBtn = "#restoreAll";

            this.el.forceDeleteMultipleBtn = "#forceDeleteMultiple";

            this.el.addModal = "#addCategoryModal";
            this.el.editModal = "#editCategoryModal";
        },

        // =========================
        // PLUGINS
        // =========================
        initPlugins() {
            window.initializeSelect2("#add_type");
            window.initializeSelect2("#add_parent_id");
            window.initializeSelect2("#edit_type");
            window.initializeSelect2("#edit_parent_id");
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
                    url: window.categoryConfig.routes.index,

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
                        data: "name",
                        name: "name",
                    },

                    {
                        data: "slug",
                        name: "slug",
                    },

                    {
                        data: "type",
                        name: "type",
                    },

                    {
                        data: "parent",
                        name: "parent",
                    },

                    {
                        data: "level",
                        name: "level",
                    },

                    {
                        data: "image",
                        name: "image",

                        render(data) {
                            const src = data
                                ? `${window.categoryConfig.assets.category}/${data}`
                                : "/default.png";

                            return `
                                <img src="${src}"
                                    width="60">
                            `;
                        },
                    },

                    {
                        data: "is_active",
                        name: "is_active",
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
        // EVENTS
        // =========================
        bind() {
            const self = this;

            window.bindFullSlugGenerator({
                nameSelector: "#add_name",
                parentSelector: "#add_parent_id",
                outputSelector: "#add_slug",
            });

            // check all
            this.el.checkAll.on("click", function () {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
            });

            // include trashed
            this.el.includeTrashed.on("change", function () {
                self.toggleTrashUI($(this).is(":checked"));

                self.reload();
            });

            // add type change
            this.el.addType.on("change", function () {
                self.loadParents($(this).val(), "#add_parent_id");
            });

            // edit type change
            this.el.editType.on("change", function () {
                self.loadParents($(this).val(), "#edit_parent_id");
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

                route: window.categoryConfig.routes.store,

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

                route: window.categoryConfig.routes.update,

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

                route: window.categoryConfig.routes.delete,

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

                route: window.categoryConfig.routes.deleteAll,

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

                route: window.categoryConfig.routes.restore,

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

                route: window.categoryConfig.routes.restoreAll,

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

                route: window.categoryConfig.routes.forceDelete,

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

                route: window.categoryConfig.routes.forceDeleteAll,

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
        // LOAD PARENT CATEGORY
        // =========================
        loadParents(type, target) {
            if (!type) {
                $(target).html(`
                    <option value="">
                        Root
                    </option>
                `);

                return;
            }

            return $.ajax({
                url: window.categoryConfig.routes.select,

                method: "GET",

                data: { type, exclude_id: $("#category_id").val() },
            }).done((res) => {
                const categories = res.data ?? res;

                $(target).empty();

                $(target).append(`
                    <option value="">
                        Root
                    </option>
                `);

                categories.forEach((item) => {
                    console.log(item.level);

                    const indent = "└─ ".repeat(item.level);

                    $(target).append(`
                        <option
                            value="${item.id}"
                            data-slug="${item.slug}"
                        >
                           ${indent}${item.name}
                        </option>
                    `);
                });

                $(target).trigger("change");
            });
        },

        // =========================
        // LOAD EDIT
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.categoryConfig.routes.edit,

                method: "GET",

                data: { id },
            }).done((res) => {
                const category = res.category;

                const imageUrl = category.image
                    ? `${window.categoryConfig.assets.category}/${category.image}`
                    : window.categoryConfig.assets.defaultImage;

                $("#category_id").val(category.id);

                $("#edit_name").val(category.name);

                $("#edit_type").val(category.type).trigger("change");

                this.loadParents(category.type, "#edit_parent_id").done(() => {
                    $("#edit_parent_id").val(category.parent_id);

                    /*
                    |--------------------------------------------------------------------------
                    | Set slug after parent loaded
                    |--------------------------------------------------------------------------
                    */

                    $("#edit_slug").val(category.slug);
                });

                $("#edit_image_preview").attr("src", imageUrl);
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
                url: window.categoryConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.categoryConfig.csrf,
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
        // TOGGLE TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? ["#restoreAll", "#forceDeleteMultiple"]
                : ["#deleteMultiple", "#addCategory"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addCategory"]
                : ["#restoreAll", "#forceDeleteMultiple"];

            show.forEach((e) => $(e).show());
            hide.forEach((e) => $(e).hide());
        },

        // =========================
        // RESET FORM
        // =========================
        resetAddForm() {
            $(this.el.addForm)[0].reset();

            $("#add_type").val("").trigger("change");
            $("#add_parent_id").val("").trigger("change");

            $("#add_image_preview").attr(
                "src",
                window.categoryConfig.assets.defaultImage,
            );

            $("#add_slug").val("");
        },

        resetEditForm() {
            $(this.el.editForm)[0].reset();

            $("#category_id").val("");

            $("#edit_type").val("").trigger("change");
            $("#edit_parent_id").val("").trigger("change");

            $("#edit_image_preview").attr(
                "src",
                window.categoryConfig.assets.defaultImage,
            );

            $("#edit_slug").val("");
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

    // =========================
    // REGISTER
    // =========================
    window.App.CategoryPage = CategoryPage;

    // =========================
    // INIT
    // =========================
    $(function () {
        CategoryPage.init();
    });
})(jQuery, window);
