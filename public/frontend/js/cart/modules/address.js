(function (window) {
    "use strict";

    window.AddressModule = {
        // tránh gọi duplicate API
        currentAddressId: null,

        // =========================
        // EVENTS
        // =========================
        bindAddressEvents() {
            const self = this;

            $(document).on("click", self.el.addAddressBtn, function () {
                self.openCreateAddressModal();
            });

            $(document).on("click", self.el.editAddressBtn, function () {
                self.openEditAddressModal($(this).data("address-id"));
            });

            $(document).on("submit", self.el.addressForm, function (e) {
                e.preventDefault();
                self.saveAddress($(this));
            });

            $(document).on("change", ".saved-address-radio", function () {
                const id = $(this).val();

                self.loadSavedAddress(id);

                $("#customer_address_id").val(id);

                $(".saved-address-card").removeClass("active");

                $(this).closest(".saved-address-card").addClass("active");

                $(".default-badge").remove();

                $(this)
                    .closest(".saved-address-card")
                    .find(".saved-address-content")
                    .append('<span class="default-badge">Mặc định</span>');

                self.post(window.cartConfig.routes.addressSetDefault, {
                    id: id,
                });
            });
        },

        // =========================
        // CREATE
        // =========================
        openCreateAddressModal() {
            const form = $(this.el.addressForm);

            if (form.length) {
                form[0].reset();
            }

            $("#address_mode").val("create");
            $("#address_id").val("");

            $("#addressModalTitle").text("Thêm địa chỉ mới");

            form.find(".district").html(
                '<option value="">Chọn quận/huyện</option>',
            );
            form.find(".ward").html('<option value="">Chọn phường/xã</option>');

            $(this.el.addressModal).modal("show");
        },

        // =========================
        // CLOSE
        // =========================
        closeAddressModal() {
            $(this.el.addressModal).modal("hide");
        },

        // =========================
        // EDIT
        // =========================
        openEditAddressModal(id) {
            const self = this;

            this.get(window.cartConfig.routes.customerAddress, { id })
                .done(function (res) {
                    const form = $(self.el.addressForm);

                    $("#address_mode").val("edit");
                    $("#addressModalTitle").text(
                        `Cập nhật địa chỉ [${res.id}]`,
                    );
                    $("#address_id").val(res.id);

                    form.find("[name='full_name']").val(res.full_name);
                    form.find("[name='phone']").val(res.phone);
                    form.find("[name='address']").val(res.address);

                    form.find(".address-province").data(
                        "selected",
                        res.province_id,
                    );

                    form.find(".address-district").data(
                        "selected",
                        res.district_id,
                    );

                    form.find(".address-ward").data("selected", res.ward_id);

                    $(self.el.addressModal).modal("show");

                    App.Location.reload(form.find(".location-wrapper"));
                })
                .fail(function () {
                    toastr.error("Không thể tải địa chỉ");
                });
        },
        // =========================
        // SAVE
        // =========================
        saveAddress(form) {
            const mode = $("#address_mode").val();

            const url =
                mode === "create"
                    ? window.cartConfig.routes.addressStore
                    : window.cartConfig.routes.addressUpdate;

            this.post(url, form.serialize())
                .done((res) => {
                    toastr.success(res.message);

                    this.closeAddressModal();

                    location.reload();
                })
                .fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || "Có lỗi xảy ra");
                });
        },

        // =========================
        // LOAD SAVED ADDRESS
        // =========================
        loadSavedAddress(id) {
            if (!id) return;

            if (this.currentAddressId === id) return;

            this.currentAddressId = id;

            $(this.el.addressId).val(id);

            $(".saved-address-radio")
                .prop("checked", false)
                .filter(`[value="${id}"]`)
                .prop("checked", true);

            this.get(window.cartConfig.routes.customerAddress, { id })
                .done(async (res) => {
                    $("input[name='shipping_name']").val(res.full_name);

                    $("input[name='shipping_phone']").val(res.phone);

                    $("input[name='shipping_email']").val(res.email);

                    $("input[name='shipping_address']").val(res.address);

                    $(".province").val(res.province_id).trigger("change");

                    const $wrapper = $(".checkout-location");

                    $wrapper
                        .find(".province")
                        .data("selected", res.province_id);

                    $wrapper
                        .find(".district")
                        .data("selected", res.district_id);

                    $wrapper.find(".ward").data("selected", res.ward_id);

                    App.Location.reload($wrapper);
                })
                .fail(() => {
                    toastr.error("Không thể tải địa chỉ");
                });
        },

        // =========================
        // AUTO LOAD
        // =========================
        autoLoadDefaultAddress() {
            const $checked = $(".saved-address-radio:checked");

            if ($checked.length) {
                $checked.trigger("change");
                return;
            }

            const $first = $(".saved-address-radio").first();

            if ($first.length) {
                $first.prop("checked", true).trigger("change");
            }
        },
    };
})(window);
