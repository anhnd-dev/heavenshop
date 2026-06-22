(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const BlogPage = {
        el: {},
        dataTable: null,
        debounceTimer: null,
        shouldReload: false,

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();
            this.initEditors();
            this.initPlugins();
            this.initDataTable();
            this.bind();
            this.bindCrud();
            this.bindModalReset();
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            this.el.table = $("#blog_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_blog_form";
            this.el.editForm = "#edit_blog_form";

            this.el.addBtn = "#add_blog_btn";
            this.el.editBtn = "#edit_blog_btn";

            this.el.deleteMultipleBtn = "#deleteMultiple";

            this.el.restoreAllBtn = "#restoreAll";

            this.el.forceDeleteMultipleBtn = "#forceDeleteMultiple";

            this.el.addModal = "#addBlogModal";
            this.el.editModal = "#editBlogModal";

            this.el.addTitle = "#add_title";
            this.el.updateTitle = "#update_title";
            this.el.addSlug = "#add_slug";
            this.el.updateSlug = "#update_slug";

            this.el.deleteIcon = ".deleteIcon";
            this.el.deleteAll = "#deleteMultiple";

            this.el.restoreIcon = ".restoreIcon";
            this.el.restoreAll = "#restoreAll";

            this.el.forceIcon = ".forceIcon";
            this.el.forceDeleteAll = "#forceDeleteMultiple";

            this.el.checkAll = $("#checkAll");
        },

        // =========================
        // EDITOR (CKEDITOR)
        // =========================
        initEditors() {
            initializeCKEditor("#add_description", {
                placeholder: "Enter description",
            }).then((ed) => (this.addDesc = ed));

            initializeCKEditor("#add_content", {
                placeholder: "Enter content",
            }).then((ed) => (this.addContent = ed));

            initializeCKEditor("#edit_description", {
                placeholder: "Enter description",
            }).then((ed) => (this.editDesc = ed));

            initializeCKEditor("#edit_content", {
                placeholder: "Enter content",
            }).then((ed) => (this.editContent = ed));
        },

        // =========================
        // PLUGINS
        // =========================
        initPlugins() {
            $(".select2-auto-tokenize").select2({
                dropdownParent: $(".card-body"),
                tags: true,
                tokenSeparators: [","],
            });

            $(".dropify").dropify();

            $(".dropify-fr").dropify({
                messages: {
                    default: "Drag and drop a file here or click to replace",
                    replace: "Drag and drop a file or click to replace",
                    remove: "Remove",
                    error: "File too large",
                },
            });
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
                    url: window.blogConfig.routes.index,
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
                    { data: "title" },
                    { data: "slug" },
                    { data: "view_count" },

                    {
                        data: "tags",

                        render(data) {
                            const tags = (data ?? "")
                                .split(",")
                                .map((tag) => tag.trim())
                                .filter(Boolean);

                            return tags
                                .map(
                                    (tag) => `
                                    <span class="badge badge-primary mr-1 mb-1">
                                        ${tag}
                                    </span>
                                `,
                                )
                                .join("");
                        },
                    },

                    {
                        data: "category_name",
                        render(_, __, full) {
                            return full.category ? full.category.name : "";
                        },
                    },

                    {
                        data: "admin_name",
                        render(_, __, full) {
                            return full.admin ? full.admin.full_name : "";
                        },
                    },

                    {
                        data: "image",
                        render(data) {
                            const src = data
                                ? `${window.blogConfig.assets.blog}/${data}`
                                : window.blogConfig.assets.defaultImage;

                            return `<img src="${src}" width="60">`;
                        },
                    },

                    {
                        data: "is_active",

                        render(data) {
                            return Number(data) === 1
                                ? `<span class="badge badge-success">Hiển thị</span>`
                                : `<span class="badge badge-danger">Tạm khóa</span>`;
                        },
                    },

                    { data: "created_at" },

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

            // slug generator
            if (window.bindSlugGenerator) {
                window.bindSlugGenerator(
                    this.el.addTitle + ", " + this.el.updateTitle,
                    this.el.addSlug + ", " + this.el.updateSlug,
                );
            }

            // edit icon
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

                route: window.blogConfig.routes.store,

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

                route: window.blogConfig.routes.update,

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

                route: window.blogConfig.routes.delete,

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

                route: window.blogConfig.routes.deleteAll,

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

                route: window.blogConfig.routes.restore,

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

                route: window.blogConfig.routes.restoreAll,

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

                route: window.blogConfig.routes.forceDelete,

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

                route: window.blogConfig.routes.forceDeleteAll,

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
                url: window.blogConfig.routes.edit,
                method: "GET",
                data: { id },
            }).done((res) => {
                console.log(res);

                const blog = res.blog;

                $("#blog_id").val(blog.id);
                $("#blog_image").val(blog.image);

                $("#edit_title").val(blog.title);
                $("#edit_slug").val(blog.slug);

                $("#edit_category_id").val(blog.category_id).trigger("change");

                $('#edit_blog_form select[name="tags[]"]')
                    .val(blog.tags ?? [])
                    .trigger("change");

                const imageUrl = blog.image
                    ? `${window.blogConfig.assets.blog}/${blog.image}`
                    : "";

                let drEvent = $("#edit_blog_form .dropify").dropify({
                    defaultFile: imageUrl,
                });

                drEvent = drEvent.data("dropify");

                drEvent.resetPreview();
                drEvent.clearElement();
                drEvent.settings.defaultFile = imageUrl;
                drEvent.destroy();
                drEvent.init();

                this.editDesc.setData(blog.description ?? "");
                this.editContent.setData(blog.content ?? "");
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
                url: window.blogConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.blogConfig.csrf,
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
        resetBlogForm(prefix = "add") {
            const form = $(`#${prefix}_blog_form`);

            // reset native form
            form[0].reset();

            // clear input
            $(`#${prefix}_title`).val("");
            $(`#${prefix}_slug`).val("");
            $(`#${prefix}_description`).val("");
            $(`#${prefix}_content`).val("");

            // reset select2 tags
            form.find('select[name="tags[]"]').val(null).trigger("change");

            // reset category
            $(`#${prefix}_category_id`).val("").trigger("change");

            // reset dropify
            const drEvent = form.find(".dropify").data("dropify");

            if (drEvent) {
                drEvent.resetPreview();
                drEvent.clearElement();
            }
        },

        // =========================
        // TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? [this.el.restoreAll, this.el.forceDeleteAll]
                : [this.el.deleteAll, "#addBlog"];

            const hide = isTrashed
                ? [this.el.deleteAll, "#addBlog"]
                : [this.el.restoreAll, this.el.forceDeleteAll];

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

    window.App.BlogPage = BlogPage;

    $(function () {
        BlogPage.init();
    });
})(jQuery, window);
