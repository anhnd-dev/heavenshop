(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const ProductPage = {
        el: {},
        dataTable: null,

        addDescEditor: null,
        addContentEditor: null,

        editDescEditor: null,
        editContentEditor: null,

        addVariantIndex: 1,
        editVariantIndex: 0,

        currentGalleryProductId: null,

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
        },

        // =========================
        // CACHE
        // =========================
        cache() {
            this.el.table = $("#product_datatable");
            this.el.includeTrashed = $("#includeTrashedCheckbox");

            // form
            this.el.addForm = "#add_product_form";
            this.el.editForm = "#edit_product_form";

            // modal
            this.el.addModal = "#addProductModel";
            this.el.editModal = "#editProductModel";

            // button
            this.el.addBtn = "#add_product_btn";
            this.el.editBtn = "#edit_product_btn";

            // slug
            this.el.addName = "#add_name";
            this.el.editName = "#edit_name";

            this.el.addSlug = "#add_slug";
            this.el.editSlug = "#edit_slug";

            // variant
            this.el.addVariantBtn = "#addVariantBtn";
            this.el.editVariantBtn = "#editVariantBtn";

            this.el.addVariantWrapper = "#add_variant_wrapper";
            this.el.editVariantWrapper = "#edit_variant_wrapper";

            // delete
            this.el.deleteIcon = ".deleteIcon";
            this.el.deleteAll = "#deleteMultiple";

            this.el.restoreIcon = ".restoreIcon";
            this.el.restoreAll = "#restoreAll";

            this.el.forceIcon = ".forceIcon";
            this.el.forceDeleteAll = "#forceDeleteMultiple";

            this.el.checkAll = $("#checkAll");

            // gallery
            this.el.galleryWrapper = ".gallery-wrapper";

            this.el.galleryForm = "#add_gallery_form";
            this.el.galleryBtn = "#add_gallery_btn";

            this.el.galleryAction = ".galleryBtn";

            this.el.galleryTrash = "#includeGalleryCheckboxTrash";
        },

        // =========================
        // CKEDITOR
        // =========================
        initEditors() {
            initializeCKEditor("#add_description").then((editor) => {
                this.addDescEditor = editor;
            });

            initializeCKEditor("#add_content").then((editor) => {
                this.addContentEditor = editor;
            });

            initializeCKEditor("#edit_description").then((editor) => {
                this.editDescEditor = editor;
            });

            initializeCKEditor("#edit_content").then((editor) => {
                this.editContentEditor = editor;
            });
        },

        // =========================
        // PLUGINS
        // =========================
        initPlugins() {
            window.initSelect2List(
                ["#add_category_id", "#add_brand_id"],
                "#addProductModel",
            );

            window.initSelect2List(
                ["#edit_category_id", "#edit_brand_id"],
                "#editProductModel",
            );
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
                    url: window.productConfig.routes.index,
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
                        data: "image",

                        render(data) {
                            const image = data
                                ? `${window.productConfig.assets.product}/${data}`
                                : window.productConfig.assets.defaultImage;

                            return `
                                <img src="${image}"
                                    width="60"
                                    class="img-thumbnail">
                            `;
                        },

                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "name",
                    },

                    {
                        data: "category_name",
                    },

                    {
                        data: "price_range",
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "total_stock",
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "is_active",

                        render(data) {
                            return data == 1
                                ? `<span class="badge badge-success">Hiển thị</span>`
                                : `<span class="badge badge-danger">Ẩn</span>`;
                        },
                    },

                    {
                        data: "is_featured",

                        render(data) {
                            return data == 1
                                ? `<span class="badge badge-warning">Nổi bật</span>`
                                : `<span class="badge badge-secondary">Thường</span>`;
                        },
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

            // include trashed
            this.el.includeTrashed.on("change", function () {
                self.toggleTrashUI($(this).is(":checked"));
                self.reload();
            });

            // check all
            this.el.checkAll.on("click", function () {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
            });

            // slug
            if (window.bindSlugGenerator) {
                window.bindSlugGenerator(
                    this.el.addName + ", " + this.el.editName,
                    this.el.addSlug + ", " + this.el.editSlug,
                );
            }

            // add variant
            $(document).on("click", this.el.addVariantBtn, function () {
                self.appendVariant(
                    self.el.addVariantWrapper,
                    self.addVariantIndex,
                    "variants",
                );

                self.addVariantIndex++;
            });

            // add edit variant
            $(document).on("click", this.el.editVariantBtn, function () {
                self.appendVariant(
                    self.el.editVariantWrapper,
                    self.editVariantIndex,
                    "new_variants",
                );

                self.editVariantIndex++;
            });

            // =========================
            // AUTO GENERATE SKU (FIXED)
            // =========================
            let skuTimeout;

            $(document).on(
                "change",
                ".variant-color, .variant-size",
                function () {
                    const item = $(this).closest(".variant-item");

                    const colorSelect = item.find(".variant-color");
                    const sizeSelect = item.find(".variant-size");

                    const colorId = colorSelect.val();
                    const sizeId = sizeSelect.val();

                    const colorText = colorSelect
                        .find("option:selected")
                        .text()
                        .trim();
                    const sizeText = sizeSelect
                        .find("option:selected")
                        .text()
                        .trim();

                    if (!colorId || !sizeId) return;

                    // =========================
                    // 1. CHECK DUPLICATE (IMPORTANT)
                    // =========================
                    let isDuplicate = false;

                    $(".variant-item")
                        .not(item)
                        .each(function () {
                            if (
                                $(this).find(".variant-color").val() ===
                                    colorId &&
                                $(this).find(".variant-size").val() === sizeId
                            ) {
                                isDuplicate = true;
                            }
                        });

                    if (isDuplicate) {
                        alert("Biến thể này đã tồn tại!");

                        colorSelect.val("");
                        sizeSelect.val("");

                        item.find(".variant-sku").val("");

                        return;
                    }

                    // =========================
                    // 2. AUTO GENERATE SKU (UX)
                    // =========================
                    clearTimeout(skuTimeout);

                    skuTimeout = setTimeout(() => {
                        if (!colorText || !sizeText) return;

                        const sku = self.generateSku(colorText, sizeText);

                        item.find(".variant-sku").val(sku);
                    }, 120);
                },
            );

            // remove variant
            $(document).on("click", ".remove-variant", function () {
                const variantId = $(this)
                    .closest(".variant-item")
                    .find('input[name*="[id]"]')
                    .val();

                if (variantId) {
                    let removed = $("#removed_variants").val() || "[]";

                    removed = JSON.parse(removed);

                    removed.push(variantId);

                    $("#removed_variants").val(JSON.stringify(removed));
                }

                $(this).closest(".variant-item").remove();
            });

            // edit
            $(document).on("click", ".editIcon", function (e) {
                e.preventDefault();

                self.loadEdit($(this).attr("id"));
            });

            // gallery checkbox
            // gallery button
            $(document).on("click", this.el.galleryAction, function () {
                const productId = $(this).data("product_id");

                const productName = $(this).data("product_name");

                // toggle same product
                if (
                    self.currentGalleryProductId === productId &&
                    $(self.el.galleryWrapper).is(":visible")
                ) {
                    $(self.el.galleryWrapper).hide();

                    self.currentGalleryProductId = null;

                    return;
                }

                self.currentGalleryProductId = productId;

                // set hidden input
                $("#gallery_product_id").val(productId);

                // title
                $("#gallery-product-title").text(`Gallery - ${productName}`);

                // show wrapper
                $(self.el.galleryWrapper).show();

                // load gallery
                self.loadGallery();
            });

            // include trash gallery
            $(document).on("change", this.el.galleryTrash, function () {
                self.loadGallery();
            });
        },

        // =========================
        // CRUD BINDING (reuse services)
        // =========================
        bindCrud() {
            // add
            window.setupAddHandler(
                this.el.addForm,
                this.el.addBtn,
                this.el.addModal,
                window.productConfig.routes.store,

                () => {
                    this.resetAddForm();

                    this.reload();
                },

                (formData) => {
                    formData.append(
                        "description",
                        this.addDescEditor.getData(),
                    );

                    formData.append("content", this.addContentEditor.getData());

                    return formData;
                },
            );

            // edit
            window.setupEditHandler(
                this.el.editForm,
                this.el.editBtn,
                this.el.editModal,
                window.productConfig.routes.update,

                () => {
                    this.reload();
                },

                (formData) => {
                    formData.append(
                        "description",
                        this.editDescEditor.getData(),
                    );

                    formData.append(
                        "content",
                        this.editContentEditor.getData(),
                    );

                    return formData;
                },
            );

            // delete
            window.setupDeleteHandler(
                this.el.deleteIcon,
                window.productConfig.routes.delete,

                () => {
                    this.reload();
                },
            );

            window.setupDeleteMultipleHandler(
                this.el.deleteAll,
                window.productConfig.routes.deleteAll,
                () => this.reload(),
            );

            window.setupRestoreHandler(
                this.el.restoreIcon,
                window.productConfig.routes.restore,
                () => this.reload(),
            );

            window.setupRestoreAllHandler(
                this.el.restoreAll,
                window.productConfig.routes.restoreAll,
                () => this.reload(),
            );

            window.setupForceHandler(
                this.el.forceIcon,
                window.productConfig.routes.forceDelete,
                () => this.reload(),
            );

            window.setupForceMultipleHandler(
                this.el.forceDeleteAll,
                window.productConfig.routes.forceDeleteAll,
                () => this.reload(),
            );

            // gallery add
            window.setupAddHandler(
                this.el.galleryForm,
                this.el.galleryBtn,
                "#addGalleryModel",

                window.productConfig.routes.galleryStore,

                () => {
                    this.loadGallery();
                },
            );
        },

        generateSku(colorText = "", sizeText = "") {
            const slug = (
                $("#add_slug").val() ||
                $("#edit_slug").val() ||
                "prd"
            )
                .toLowerCase()
                .replace(/\s+/g, "-");

            const color = colorText.toLowerCase().replace(/\s+/g, "-");
            const size = sizeText.toLowerCase().replace(/\s+/g, "-");

            const random = Math.random().toString(36).substring(2, 7);

            return `${slug}-${color}-${size}-${random}`;
        },

        // =========================
        // APPEND VARIANT
        // =========================
        appendVariant(wrapper, index, fieldName) {
            const template = $("#variant-template")
                .html()
                .replaceAll("__INDEX__", index)
                .replaceAll("__NAME__", fieldName)
                .replaceAll("__TITLE__", `Biến thể #${index + 1}`);

            $(wrapper).append(template);
        },

        // =========================
        // LOAD EDIT
        // =========================
        loadEdit(id) {
            $.ajax({
                url: window.productConfig.routes.edit,
                method: "GET",

                data: { id },

                success: (product) => {
                    // =========================
                    // PRODUCT
                    // =========================
                    $("#product_id").val(product.id);

                    $("#edit_name").val(product.name);

                    $("#edit_slug").val(product.slug);

                    $("#edit_category_id")
                        .val(product.category_id)
                        .trigger("change");

                    $("#edit_brand_id").val(product.brand_id).trigger("change");

                    // =========================
                    // TAGS
                    // =========================
                    $("#edit_tags").tagsinput("removeAll");

                    if (product.tags) {
                        const tags = Array.isArray(product.tags)
                            ? product.tags
                            : product.tags.split(",");

                        tags.filter((tag) => tag && tag.trim() !== "").forEach(
                            (tag) => {
                                $("#edit_tags").tagsinput("add", tag.trim());
                            },
                        );
                    }

                    // =========================
                    // STATUS
                    // =========================
                    $("#edit_is_featured").prop("checked", product.is_featured);

                    $("#edit_is_active")
                        .val(product.is_active ? "1" : "0")
                        .trigger("change");

                    // =========================
                    // CKEDITOR
                    // =========================
                    this.editDescEditor.setData(product.description ?? "");

                    this.editContentEditor.setData(product.content ?? "");

                    // =========================
                    // PRODUCT IMAGE
                    // =========================
                    const image = product.image
                        ? `${window.productConfig.assets.product}/${product.image}`
                        : window.productConfig.assets.defaultImage;

                    $("#edit_image_preview").attr("src", image);

                    // =========================
                    // RESET VARIANTS
                    // =========================
                    $(this.el.editVariantWrapper).html("");

                    this.editVariantIndex = product.variants.length;

                    // =========================
                    // LOAD VARIANTS
                    // =========================
                    if (product.variants.length > 0) {
                        product.variants.forEach((variant, index) => {
                            // template
                            const template = $("#variant-template")
                                .html()
                                .replaceAll("__INDEX__", index)
                                .replaceAll("__NAME__", "edit_variants")
                                .replaceAll(
                                    "__TITLE__",
                                    `Biến thể #${index + 1}`,
                                );

                            // append
                            $(this.el.editVariantWrapper).append(template);

                            // current item
                            const item = $(this.el.editVariantWrapper)
                                .find(".variant-item")
                                .last();

                            // =========================
                            // IMPORTANT
                            // =========================
                            item.find(".variant-id").val(variant.id);

                            item.find(".variant-existing-image").val(
                                variant.image ?? "",
                            );

                            // =========================
                            // VALUES
                            // =========================
                            item.find(".variant-color").val(variant.color_id);

                            item.find(".variant-size").val(variant.size_id);

                            item.find(".variant-price").val(variant.price);

                            item.find(".variant-sale-price").val(
                                variant.sale_price,
                            );

                            item.find(".variant-stock").val(variant.stock);

                            item.find(".variant-sku").val(variant.sku);

                            // status
                            item.find(".variant-status").val(
                                variant.is_active ? "1" : "0",
                            );

                            // =========================
                            // IMAGE
                            // =========================
                            const variantImage = variant.image
                                ? `${window.productConfig.assets.variant}/${variant.image}`
                                : window.productConfig.assets.defaultImage;

                            item.find(".variant-preview")
                                .attr("src", variantImage)
                                .removeClass("d-none");
                        });
                    }

                    // =========================
                    // SHOW MODAL
                    // =========================
                    $(this.el.editModal).modal("show");
                },
            });
        },

        // =========================
        // LOAD GALLERY
        // =========================
        loadGallery() {
            if (!this.currentGalleryProductId) {
                return;
            }

            $("#gallery-content").html(`
                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                </div>
            `);

            const url = window.galleryProductConfig.routes.index.replace(
                "__PRODUCT_ID__",
                this.currentGalleryProductId,
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
                    $("#gallery-content").html(response);
                },

                error: () => {
                    $("#gallery-content").html(`
                        <div class="alert alert-danger mb-0">

                            Không thể tải thư viện ảnh

                        </div>
                    `);
                },
            });
        },

        // =========================
        // RESET FORM
        // =========================
        resetAddForm() {
            $(this.el.addForm)[0].reset();

            this.addDescEditor.setData("");
            this.addContentEditor.setData("");

            $(this.el.addVariantWrapper).html("");

            this.addVariantIndex = 0;

            $(this.el.addVariantBtn).trigger("click");
        },

        // =========================
        // TRASH UI
        // =========================
        toggleTrashUI(isTrashed) {
            const show = isTrashed
                ? [this.el.restoreAll, this.el.forceDeleteAll]
                : [this.el.deleteAll, "#addProduct"];

            const hide = isTrashed
                ? [this.el.deleteAll, "#addProduct"]
                : [this.el.restoreAll, this.el.forceDeleteAll];

            show.forEach((e) => $(e).show());
            hide.forEach((e) => $(e).hide());
        },

        // =========================
        // RELOAD
        // =========================
        reload() {
            this.dataTable?.ajax.reload();
        },
    };

    window.App.ProductPage = ProductPage;

    $(function () {
        ProductPage.init();
    });
})(jQuery, window);
