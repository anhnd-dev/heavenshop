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

            this.el.addModal = "#addColorModal";
            this.el.editModal = "#editColorModal";
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
            if (window.setupAddHandler) {
                window.setupAddHandler(
                    this.el.addForm,
                    this.el.addBtn,
                    this.el.addModal,
                    window.colorConfig.routes.store,
                    () => this.initDataTable(),
                );
            }

            if (window.setupEditHandler) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,
                    window.colorConfig.routes.update,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteHandler) {
                window.setupDeleteHandler(
                    ".deleteIcon",
                    window.colorConfig.routes.delete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteMultipleHandler) {
                window.setupDeleteMultipleHandler(
                    "#deleteMultiple",
                    window.colorConfig.routes.deleteAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreHandler) {
                window.setupRestoreHandler(
                    ".restoreIcon",
                    window.colorConfig.routes.restore,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreAllHandler) {
                window.setupRestoreAllHandler(
                    "#restoreAll",
                    window.colorConfig.routes.restoreAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceHandler) {
                window.setupForceHandler(
                    ".forceIcon",
                    window.colorConfig.routes.forceDelete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceMultipleHandler) {
                window.setupForceMultipleHandler(
                    "#forceDeleteMultiple",
                    window.colorConfig.routes.forceDeleteAll,
                    () => this.initDataTable(),
                );
            }
        },

        bindModalReset() {
            // Add modal
            $("#addColorModal").on("hidden.bs.modal", () => {
                this.resetAddForm();
            });

            // Edit modal
            $("#editColorModal").on("hidden.bs.modal", () => {
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
                    _token: window.colorConfig.csrf,
                },
            }).done((res) => {
                $("#update_name").val(res.name);
                $("#update_code").val(res.code);
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
            $("#add_color_form")[0].reset();

            $("#add_name").val("");
            $("#add_code").val("");
        },

        resetEditForm() {
            $("#edit_color_form")[0].reset();

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
