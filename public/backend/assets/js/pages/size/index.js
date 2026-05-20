(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const SizePage = {
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
        },

        // =========================
        // CACHE DOM
        // =========================
        cache() {
            this.el.table = $("#size_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_size_form";
            this.el.editForm = "#edit_size_form";

            this.el.addBtn = "#add_size_btn";
            this.el.editBtn = "#edit_size_btn";

            this.el.addModal = "#addSizeModal";
            this.el.editModal = "#editSizeModal";
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
                    url: window.sizeConfig.routes.index,

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

                const id = $(this).attr("id");

                self.loadEdit(id);
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
                    window.sizeConfig.routes.store,
                    () => this.initDataTable(),
                );
            }

            if (window.setupEditHandler) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,
                    window.sizeConfig.routes.update,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteHandler) {
                window.setupDeleteHandler(
                    ".deleteIcon",
                    window.sizeConfig.routes.delete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteMultipleHandler) {
                window.setupDeleteMultipleHandler(
                    "#deleteMultiple",
                    window.sizeConfig.routes.deleteAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreHandler) {
                window.setupRestoreHandler(
                    ".restoreIcon",
                    window.sizeConfig.routes.restore,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreAllHandler) {
                window.setupRestoreAllHandler(
                    "#restoreAll",
                    window.sizeConfig.routes.restoreAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceHandler) {
                window.setupForceHandler(
                    ".forceIcon",
                    window.sizeConfig.routes.forceDelete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceMultipleHandler) {
                window.setupForceMultipleHandler(
                    "#forceDeleteMultiple",
                    window.sizeConfig.routes.forceDeleteAll,
                    () => this.initDataTable(),
                );
            }
        },

        // =========================
        // LOAD EDIT
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.sizeConfig.routes.edit,
                method: "GET",

                data: {
                    id,
                    _token: window.sizeConfig.csrf,
                },
            }).done((res) => {
                $("#edit_name").val(res.name);
                $("#size_id").val(res.id);
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
                url: window.sizeConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.sizeConfig.csrf,
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
                : ["#deleteMultiple", "#addSize"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addSize"]
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
    window.App.SizePage = SizePage;

    // =========================
    // INIT
    // =========================
    $(function () {
        SizePage.init();
    });
})(jQuery, window);
