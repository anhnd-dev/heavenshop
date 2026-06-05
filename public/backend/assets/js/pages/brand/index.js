(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const BrandPage = {
        el: {},
        dataTable: null,
        shouldReloadData: false,
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
            this.el.table = $("#brand_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_brand_form";
            this.el.editForm = "#edit_brand_form";

            this.el.addBtn = "#add_brand_btn";
            this.el.editBtn = "#edit_brand_btn";

            this.el.addModal = "#addBrandModal";
            this.el.editModal = "#editBrandModal";
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
                    url: window.brandConfig.routes.index,
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
                        render(data, type, full) {
                            return `
                            <div class="form-check custom-checkbox ms-2">
                                <input type="checkbox"
                                       class="form-check-input checkbox_ids"
                                       value="${full.id}">
                            </div>`;
                        },
                    },

                    { data: "name", name: "name" },

                    { data: "slug", name: "slug" },

                    {
                        data: "image",
                        name: "image",
                        render(data) {
                            const src = data
                                ? `${window.brandConfig.assets.brand}/${data}`
                                : "/default.png";

                            return `
                                <img src="${src}"
                                    width="60"
                                    class="img-thumbnail">
                            `;
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

            // include trashed toggle
            this.el.includeTrashed.on("change", function () {
                self.toggleTrashUI($(this).is(":checked"));
                self.reload();
            });

            // slug generator
            if (window.bindSlugGenerator) {
                window.bindSlugGenerator(
                    "#add_name" + ", " + "#edit_name",
                    "#add_slug" + ", " + "#edit_slug",
                );
            }

            // edit load
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
        // CRUD BINDINGS (external services)
        // =========================
        bindCrud() {
            window.setupFormHandler({
                form: this.el.addForm,
                button: this.el.addBtn,
                modal: this.el.addModal,
                route: window.brandConfig.routes.store,
                method: "POST",
                callback: () => this.initDataTable(),
            });

            if (window.setupEditHandler) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,
                    window.brandConfig.routes.update,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteHandler) {
                window.setupDeleteHandler(
                    ".deleteIcon",
                    window.brandConfig.routes.delete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteMultipleHandler) {
                window.setupDeleteMultipleHandler(
                    "#deleteMultiple",
                    window.brandConfig.routes.deleteAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreHandler) {
                window.setupRestoreHandler(
                    ".restoreIcon",
                    window.brandConfig.routes.restore,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreAllHandler) {
                window.setupRestoreAllHandler(
                    "#restoreAll",
                    window.brandConfig.routes.restoreAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceHandler) {
                window.setupForceHandler(
                    ".forceIcon",
                    window.brandConfig.routes.forceDelete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceMultipleHandler) {
                window.setupForceMultipleHandler(
                    "#forceDeleteMultiple",
                    window.brandConfig.routes.forceDeleteAll,
                    () => this.initDataTable(),
                );
            }
        },

        bindModalReset() {
            // Add modal
            $("#addBrandModal").on("hidden.bs.modal", () => {
                this.resetAddForm();
            });

            // Edit modal
            $("#editBrandModal").on("hidden.bs.modal", () => {
                this.resetEditForm();
            });
        },

        // =========================
        // EDIT LOAD
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.brandConfig.routes.edit,
                method: "GET",
                data: { id },
            }).done((res) => {
                const imageUrl = res.image
                    ? `${window.brandConfig.assets.brand}/${res.image}`
                    : window.brandConfig.assets.defaultImage;

                $("#edit_name").val(res.name);
                $("#edit_slug").val(res.slug);

                $("#edit_image_preview").attr("src", imageUrl);

                $("#brand_id").val(res.id);
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
                url: window.brandConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.brandConfig.csrf,
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
            $("#add_brand_form")[0].reset();

            // reset image preview
            $("#add_image_preview").attr(
                "src",
                window.brandConfig.assets.defaultImage,
            );

            $("#add_slug").val("");
        },

        resetEditForm() {
            $("#edit_brand_form")[0].reset();

            $("#brand_id").val("");

            $("#edit_image_preview").attr(
                "src",
                window.brandConfig.assets.defaultImage,
            );

            $("#edit_slug").val("");
        },

        // =========================
        // TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? ["#restoreAll", "#forceDeleteMultiple"]
                : ["#deleteMultiple", "#addBrand"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addBrand"]
                : ["#restoreAll", "#forceDeleteMultiple"];

            show.forEach((s) => $(s).show());
            hide.forEach((h) => $(h).hide());
        },

        // =========================
        // DEBOUNCE RELOAD
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
    window.App.BrandPage = BrandPage;

    // =========================
    // INIT
    // =========================
    $(function () {
        BrandPage.init();
    });
})(jQuery, window);
