(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const ProductGalleryPage = {
        el: {},

        currentProductId: null,

        // =========================
        // INIT
        // =========================
        init() {
            this.cache();
            this.bind();
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            // wrapper
            this.el.wrapper = ".gallery-wrapper";

            this.el.content = "#gallery-content";

            this.el.title = "#gallery-product-title";

            // gallery button
            this.el.galleryAction = ".galleryBtn";

            this.el.galleryTrash = "#includeGalleryCheckboxTrash";

            // form
            this.el.addForm = "#add_gallery_form";

            this.el.editForm = "#edit_gallery_form";

            // modal
            this.el.addModal = "#addGalleryModel";

            this.el.editModal = "#editGalleryModel";

            // button
            this.el.addBtn = "#add_gallery_btn";

            this.el.editBtn = "#edit_gallery_btn";

            // checkbox
            this.el.checkAll = "#checkAllGallery";

            this.el.checkboxIds = ".checkbox_gallery_ids";

            // delete
            this.el.deleteBtn = ".deleteGallery";

            this.el.deleteMultiple = "#deleteGalleryMultiple";

            // restore
            this.el.restoreBtn = ".restoreGallery";

            this.el.restoreAll = "#restoreGalleryAll";

            // force
            this.el.forceBtn = ".forceGallery";

            this.el.forceMultiple = "#forceDeleteGalleryMultiple";
        },

        // =========================
        // EVENTS
        // =========================
        bind() {
            // =========================
            // OPEN GALLERY
            // =========================
            $(document).on("click", this.el.galleryAction, (e) => {
                e.preventDefault();

                const btn = $(e.currentTarget);

                const productId = btn.data("product_id");

                const productName = btn.data("product_name");

                // toggle same product
                if (
                    this.currentProductId === productId &&
                    $(this.el.wrapper).is(":visible")
                ) {
                    $(this.el.wrapper).slideUp(200);

                    this.currentProductId = null;

                    return;
                }

                this.currentProductId = productId;

                // bind CRUD after product selected
                this.bindCrud();

                // hidden input
                $("#gallery_product_id").val(productId);

                // title
                $(this.el.title).text(`Gallery - ${productName}`);

                // show wrapper
                $(this.el.wrapper).slideDown(200);

                // load gallery
                this.loadGallery();
            });

            // =========================
            // INCLUDE TRASH
            // =========================
            $(document).on("change", this.el.galleryTrash, () => {
                this.toggleTrashUI($(this.el.galleryTrash).is(":checked"));

                this.loadGallery();
            });

            // =========================
            // CHECK ALL
            // =========================
            $(document).on("change", this.el.checkAll, (e) => {
                $(this.el.checkboxIds).prop(
                    "checked",
                    $(e.currentTarget).is(":checked"),
                );
            });
        },

        // =========================
        // CRUD
        // =========================
        bindCrud() {
            // =========================
            // URLS
            // =========================
            const storeUrl = window.galleryProductConfig.routes.store.replace(
                "__PRODUCT_ID__",
                this.currentProductId,
            );

            const deleteUrl = window.galleryProductConfig.routes.delete.replace(
                "__PRODUCT_ID__",
                this.currentProductId,
            );

            const deleteAllUrl =
                window.galleryProductConfig.routes.deleteAll.replace(
                    "__PRODUCT_ID__",
                    this.currentProductId,
                );

            const restoreUrl =
                window.galleryProductConfig.routes.restore.replace(
                    "__PRODUCT_ID__",
                    this.currentProductId,
                );

            const restoreAllUrl =
                window.galleryProductConfig.routes.restoreAll.replace(
                    "__PRODUCT_ID__",
                    this.currentProductId,
                );

            const forceDeleteUrl =
                window.galleryProductConfig.routes.forceDelete.replace(
                    "__PRODUCT_ID__",
                    this.currentProductId,
                );

            const forceDeleteAllUrl =
                window.galleryProductConfig.routes.forceDeleteAll.replace(
                    "__PRODUCT_ID__",
                    this.currentProductId,
                );

            // =========================
            // ADD
            // =========================
            window.setupAddHandler(
                this.el.addForm,
                this.el.addBtn,
                this.el.addModal,

                storeUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // EDIT
            // =========================
            if ($(this.el.editForm).length) {
                window.setupEditHandler(
                    this.el.editForm,
                    this.el.editBtn,
                    this.el.editModal,

                    (id) => {
                        return window.galleryProductConfig.routes.update
                            .replace("__PRODUCT_ID__", this.currentProductId)
                            .replace("__ID__", id);
                    },

                    () => {
                        this.loadGallery();
                    },
                );
            }

            // =========================
            // DELETE
            // =========================
            window.setupDeleteHandler(
                this.el.deleteBtn,

                deleteUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // DELETE MULTIPLE
            // =========================
            window.setupDeleteMultipleHandler(
                this.el.deleteMultiple,

                deleteAllUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // RESTORE
            // =========================
            window.setupRestoreHandler(
                this.el.restoreBtn,

                restoreUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // RESTORE ALL
            // =========================
            window.setupRestoreAllHandler(
                this.el.restoreAll,

                restoreAllUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // FORCE DELETE
            // =========================
            window.setupForceHandler(
                this.el.forceBtn,

                forceDeleteUrl,

                () => {
                    this.loadGallery();
                },
            );

            // =========================
            // FORCE DELETE MULTIPLE
            // =========================
            window.setupForceMultipleHandler(
                this.el.forceMultiple,

                forceDeleteAllUrl,

                () => {
                    this.loadGallery();
                },
            );
        },

        // =========================
        // LOAD GALLERY
        // =========================
        loadGallery() {
            if (!this.currentProductId) {
                return;
            }

            $(this.el.content).html(`
                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                </div>
            `);

            const url = window.galleryProductConfig.routes.index.replace(
                "__PRODUCT_ID__",
                this.currentProductId,
            );

            $.ajax({
                url,

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

        // =========================
        // TOGGLE TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? [this.el.restoreAll, this.el.forceMultiple]
                : [this.el.deleteMultiple, "#addGallery"];

            const hide = isTrashed
                ? [this.el.deleteMultiple, "#addGallery"]
                : [this.el.restoreAll, this.el.forceMultiple];

            show.forEach((e) => {
                $(e).show();
            });

            hide.forEach((e) => {
                $(e).hide();
            });
        },
    };

    window.App.ProductGalleryPage = ProductGalleryPage;

    $(function () {
        ProductGalleryPage.init();
    });
})(jQuery, window);
