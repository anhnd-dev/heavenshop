(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const SliderPage = {
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
            this.el.table = $("#slider_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            this.el.addForm = "#add_slider_form";
            this.el.editForm = "#edit_slider_form";

            this.el.addBtn = "#add_slider_btn";
            this.el.editBtn = "#edit_slider_btn";

            this.el.addModal = "#addSliderModal";
            this.el.editModal = "#editSliderModal";
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
                    url: window.sliderConfig.routes.index,
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

                    {
                        data: "title",
                        name: "title",
                    },

                    {
                        data: "image",
                        name: "image",
                        render(data) {
                            const src = data
                                ? `${window.sliderConfig.assets.slider}/${data}`
                                : window.sliderConfig.assets.defaultImage;

                            return `
                                <img src="${src}"
                                    width="80"
                                    class="img-thumbnail">
                            `;
                        },
                    },

                    {
                        data: "position",
                        name: "position",
                        render(data) {
                            return `
                                <span class="badge badge-info">
                                    ${data}
                                </span>
                            `;
                        },
                    },

                    {
                        data: "sort_order",
                        name: "sort_order",
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
                        data: "start_at",
                        name: "start_at",
                    },

                    {
                        data: "end_at",
                        name: "end_at",
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
        // CRUD BINDINGS
        // =========================
        bindCrud() {
            if (window.setupAddHandler) {
                window.setupAddHandler(
                    this.el.addForm,
                    this.el.addBtn,
                    this.el.addModal,
                    window.sliderConfig.routes.store,
                    () => this.initDataTable(),
                );
            }

            if (window.setupEditHandler) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,
                    window.sliderConfig.routes.update,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteHandler) {
                window.setupDeleteHandler(
                    ".deleteIcon",
                    window.sliderConfig.routes.delete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupDeleteMultipleHandler) {
                window.setupDeleteMultipleHandler(
                    "#deleteMultiple",
                    window.sliderConfig.routes.deleteAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreHandler) {
                window.setupRestoreHandler(
                    ".restoreIcon",
                    window.sliderConfig.routes.restore,
                    () => this.initDataTable(),
                );
            }

            if (window.setupRestoreAllHandler) {
                window.setupRestoreAllHandler(
                    "#restoreAll",
                    window.sliderConfig.routes.restoreAll,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceHandler) {
                window.setupForceHandler(
                    ".forceIcon",
                    window.sliderConfig.routes.forceDelete,
                    () => this.initDataTable(),
                );
            }

            if (window.setupForceMultipleHandler) {
                window.setupForceMultipleHandler(
                    "#forceDeleteMultiple",
                    window.sliderConfig.routes.forceDeleteAll,
                    () => this.initDataTable(),
                );
            }
        },

        // =========================
        // MODAL RESET
        // =========================
        bindModalReset() {
            // Add modal
            $("#addSliderModal").on("hidden.bs.modal", () => {
                this.resetAddForm();
            });

            // Edit modal
            $("#editSliderModal").on("hidden.bs.modal", () => {
                this.resetEditForm();
            });
        },

        // =========================
        // LOAD EDIT
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.sliderConfig.routes.edit,
                method: "GET",
                data: { id },
            }).done((res) => {
                const imageUrl = res.image
                    ? `${window.sliderConfig.assets.slider}/${res.image}`
                    : window.sliderConfig.assets.defaultImage;

                $("#edit_title").val(res.title);
                $("#edit_subtitle").val(res.subtitle);
                $("#edit_url").val(res.url);
                $("#edit_position").val(res.position);
                $("#edit_sort_order").val(res.sort_order);

                $("#edit_start_at").val(formatDateTimeLocal(res.start_at));
                $("#edit_end_at").val(formatDateTimeLocal(res.end_at));

                $("#edit_image_preview").attr("src", imageUrl);

                $("#slider_id").val(res.id);
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
                url: window.sliderConfig.routes.changeStatus,
                method: "POST",
                data: {
                    id,
                    new_status: newStatus,
                },
                headers: {
                    "X-CSRF-TOKEN": window.sliderConfig.csrf,
                },
            }).done((res) => {
                if (res.status === 200) {
                    $btn.toggleClass("btn-success btn-dark");
                    toastr.success(res.message);
                    this.reload();
                }
            });
        },

        formatDateTimeLocal(dateString) {
            if (!dateString) return "";

            return dateString.substring(0, 19);
        },

        // =========================
        // RESET FORM
        // =========================
        resetAddForm() {
            $("#add_slider_form")[0].reset();
        },

        resetEditForm() {
            $("#edit_slider_form")[0].reset();

            $("#slider_id").val("");

            $("#edit_image_preview").attr(
                "src",
                window.sliderConfig.assets.defaultImage,
            );
        },

        // =========================
        // TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? ["#restoreAll", "#forceDeleteMultiple"]
                : ["#deleteMultiple", "#addSlider"];

            const hide = isTrashed
                ? ["#deleteMultiple", "#addSlider"]
                : ["#restoreAll", "#forceDeleteMultiple"];

            show.forEach((s) => $(s).show());
            hide.forEach((h) => $(h).hide());
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
    window.App.SliderPage = SliderPage;

    // =========================
    // INIT
    // =========================
    $(function () {
        SliderPage.init();
    });
})(jQuery, window);
