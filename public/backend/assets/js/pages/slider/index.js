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

            this.el.deleteMultipleBtn = "#deleteMultiple";

            this.el.restoreAllBtn = "#restoreAll";

            this.el.forceDeleteMultipleBtn = "#forceDeleteMultiple";

            this.el.addModal = "#addSliderModal";
            this.el.editModal = "#editSliderModal";

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

            // check all
            this.el.checkAll.on("click", function () {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
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
            /*
            |--------------------------------------------------------------------------
            | Add
            |--------------------------------------------------------------------------
            */

            window.setupFormHandler({
                form: this.el.addForm,
                button: this.el.addBtn,
                modal: this.el.addModal,

                route: window.sliderConfig.routes.store,

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

                route: window.sliderConfig.routes.update,

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

                route: window.sliderConfig.routes.delete,

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

                route: window.sliderConfig.routes.deleteAll,

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

                route: window.sliderConfig.routes.restore,

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

                route: window.sliderConfig.routes.restoreAll,

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

                route: window.sliderConfig.routes.forceDelete,

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

                route: window.sliderConfig.routes.forceDeleteAll,

                method: "DELETE",

                confirmText:
                    "Bạn có chắc muốn xóa vĩnh viễn các danh mục đã chọn?",

                callback: () => this.reload(),
            });
        },

        // =========================
        // MODAL RESET
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
                url: window.sliderConfig.routes.edit,
                method: "GET",
                data: { id },
            }).done((res) => {
                console.log(res);

                const imageUrl = res.image
                    ? `${window.sliderConfig.assets.slider}/${res.image}`
                    : window.sliderConfig.assets.defaultImage;

                $("#edit_title").val(res.title);
                $("#edit_subtitle").val(res.subtitle);
                $("#edit_url").val(res.url);
                $("#edit_position").val(res.position);
                $("#edit_sort_order").val(res.sort_order);

                $("#edit_start_at").val(
                    res.start_at ? res.start_at.slice(0, 16) : "",
                );

                $("#edit_end_at").val(
                    res.end_at ? res.end_at.slice(0, 16) : "",
                );

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

        // =========================
        // RESET FORM
        // =========================
        resetAddForm() {
            $(this.el.addForm)[0].reset();
        },

        resetEditForm() {
            $(this.el.editForm)[0].reset();

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
