(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const ProductGalleryPage = {
        currentProductId: null,

        el: {},

        init() {
            this.cache();
            this.bind();
            this.bindCrud();
        },

        cache() {
            this.el.wrapper = ".gallery-wrapper";

            this.el.content = "#gallery-content";

            this.el.title = "#gallery-product-title";

            this.el.galleryAction = ".galleryBtn";

            this.el.galleryTrash = "#includeGalleryCheckboxTrash";

            this.el.addForm = "#add_gallery_form";
            this.el.editForm = "#edit_gallery_form";

            this.el.addModal = "#addGalleryModel";
            this.el.editModal = "#editGalleryModel";

            this.el.addBtn = "#add_gallery_btn";
            this.el.editBtn = "#edit_gallery_btn";

            this.el.checkAll = "#checkAllGallery";

            this.el.checkboxIds = ".checkbox_gallery_ids";

            this.el.deleteBtn = ".deleteGallery";
            this.el.deleteMultiple = "#deleteGalleryMultiple";

            this.el.restoreBtn = ".restoreGallery";
            this.el.restoreAll = "#restoreGalleryAll";

            this.el.forceBtn = ".forceGallery";
            this.el.forceMultiple = "#forceDeleteGalleryMultiple";
        },

        getRoute(routeName, id = null) {
            if (!this.currentProductId) {
                return null;
            }

            let url = window.galleryProductConfig.routes[routeName].replace(
                "__PRODUCT_ID__",
                this.currentProductId,
            );

            if (id !== null) {
                url = url.replace("__ID__", id);
            }

            return url;
        },

        bind() {
            // OPEN GALLERY
            $(document).on("click", this.el.galleryAction, (e) => {
                e.preventDefault();

                const btn = $(e.currentTarget);

                const productId = String(btn.data("product_id"));

                const productName = btn.data("product_name");

                // toggle close
                if (
                    String(this.currentProductId) === productId &&
                    $(this.el.wrapper).is(":visible")
                ) {
                    $(this.el.wrapper).slideUp(200);

                    this.currentProductId = null;

                    return;
                }

                this.currentProductId = productId;

                $("#gallery_product_id").val(productId);

                $(this.el.title).text(`Gallery - ${productName}`);

                $(this.el.wrapper).slideDown(200);

                this.loadGallery();
            });

            // INCLUDE TRASH
            $(document).on("change", this.el.galleryTrash, () => {
                this.toggleTrashUI($(this.el.galleryTrash).is(":checked"));

                this.loadGallery();
            });

            // CHECK ALL
            $(document).on("change", this.el.checkAll, (e) => {
                $(this.el.checkboxIds).prop(
                    "checked",
                    $(e.currentTarget).is(":checked"),
                );
            });

            $(document).on("click", ".editGallery", (e) => {
                e.preventDefault();

                this.loadEditGallery($(e.currentTarget).data("id"));
            });
        },

        bindCrud() {
            // ADD
            window.setupFormHandler({
                form: this.el.addForm,
                button: this.el.addBtn,
                modal: this.el.addModal,

                route: () => this.getRoute("store"),

                method: "POST",

                buttonLoadingText: window.galleryProductConfig.action.adding,
                buttonDefaultText: window.galleryProductConfig.action.add,

                callback: () => this.loadGallery(),
            });

            // EDIT
            window.setupFormHandler({
                form: this.el.editForm,
                button: this.el.editBtn,
                modal: this.el.editModal,

                route: () => {
                    const id = $("#edit_gallery_id").val();

                    return this.getRoute("update", id);
                },

                method: "PUT",

                buttonLoadingText: window.galleryProductConfig.action.updating,
                buttonDefaultText: window.galleryProductConfig.action.update,

                callback: () => this.loadGallery(),
            });

            // DELETE
            window.setupAjaxActionHandler({
                button: this.el.deleteBtn,

                route: () => this.getRoute("delete"),

                method: "DELETE",

                confirmText: window.galleryProductConfig.action.deleteText,

                getData: ($btn) => ({
                    id: $btn.data("id"),
                }),

                callback: () => {
                    this.loadGallery();
                },
            });

            // RESTORE
            window.setupAjaxActionHandler({
                button: this.el.restoreBtn,

                route: () => this.getRoute("restore"),

                confirmText: window.galleryProductConfig.action.restore_text,

                getData: ($btn) => ({
                    id: $btn.data("id"),
                }),

                callback: () => {
                    this.loadGallery();
                },
            });

            // FORCE DELETE
            window.setupAjaxActionHandler({
                button: this.el.forceBtn,

                route: () => this.getRoute("forceDelete"),

                method: "DELETE",

                confirmText: window.translations.force_delete_text,

                getData: ($btn) => ({
                    id: $btn.data("id"),
                }),

                callback: () => {
                    this.loadGallery();
                },
            });

            // DELETE ALL
            window.setupBulkActionHandler({
                button: this.el.deleteMultiple,

                route: () => this.getRoute("deleteAll"),

                method: "DELETE",

                checkboxSelector: ".checkbox_gallery_ids",

                checkAllSelector: "#checkAllGallery",

                confirmText: window.translations.confirmText,

                emptyText: window.translations.checkbox_required,

                callback: () => this.loadGallery(),
            });

            // RESTORE ALL
            window.setupBulkActionHandler({
                button: this.el.restoreAll,

                route: () => this.getRoute("restoreAll"),

                checkboxSelector: ".checkbox_gallery_ids",

                checkAllSelector: "#checkAllGallery",

                confirmText: window.translations.confirmText,

                emptyText: window.translations.checkbox_required,

                callback: () => this.loadGallery(),
            });

            // FORCE DELETE MULTIPLE
            window.setupBulkActionHandler({
                button: this.el.forceMultiple,

                route: () => this.getRoute("forceDeleteAll"),

                method: "DELETE",

                checkboxSelector: ".checkbox_gallery_ids",

                checkAllSelector: "#checkAllGallery",

                confirmText: "Bạn có chắc muốn xoá vĩnh viễn các mục đã chọn?",

                emptyText: window.translations.checkbox_required,

                callback: () => this.loadGallery(),
            });
        },

        loadEditGallery(id) {
            $.ajax({
                url: this.getRoute("edit"),

                method: "GET",

                data: {
                    id: id,
                },

                success: (gallery) => {
                    // id
                    $("#edit_gallery_id").val(gallery.id);

                    // alt
                    $("#edit_alt").val(gallery.alt ?? "");

                    // sort order
                    $("#edit_sort_order").val(gallery.sort_order ?? 0);

                    // status
                    // $("#edit_is_active").val(gallery.is_active ? "1" : "0");

                    // image preview
                    const image = gallery.file
                        ? `${window.galleryProductConfig.assets.gallery}/${gallery.file}`
                        : window.galleryProductConfig.assets.defaultImage;

                    $("#edit_preview_wrapper").html(`
                        <img
                            src="${image}"
                            class="img-thumbnail"
                            style="max-width: 150px"
                        >
                    `);

                    $("#edit_color_id").val(gallery.color_id).trigger("change");

                    // show modal
                    $(this.el.editModal).modal("show");
                },

                error: () => {
                    toastr.error("Không thể tải dữ liệu gallery");
                },
            });
        },

        loadGallery() {
            if (!this.currentProductId) {
                return;
            }

            $(this.el.content).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            `);

            $.ajax({
                url: this.getRoute("index"),

                method: "GET",

                data: {
                    include_trashed: $(this.el.galleryTrash).is(":checked")
                        ? 1
                        : 0,
                },

                success: (response) => {
                    $(this.el.content).html(response);
                },

                error: () => {
                    $(this.el.content).html(`
                        <div class="alert alert-danger mb-0">
                            Không thể tải gallery
                        </div>
                    `);
                },
            });
        },

        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? [this.el.restoreAll, this.el.forceMultiple]
                : [this.el.deleteMultiple, "#addGallery"];

            const hide = isTrashed
                ? [this.el.deleteMultiple, "#addGallery"]
                : [this.el.restoreAll, this.el.forceMultiple];

            show.forEach((selector) => {
                $(selector).show();
            });

            hide.forEach((selector) => {
                $(selector).hide();
            });
        },
    };

    window.App.ProductGalleryPage = ProductGalleryPage;

    $(function () {
        ProductGalleryPage.init();
    });
})(jQuery, window);
