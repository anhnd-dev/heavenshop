(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const Location = {
        routes: {
            provinces: "/api/location/provinces",
            districts: "/api/location/districts",
            wards: "/api/location/wards",
        },

        init(selector = ".location-wrapper") {
            $(selector).each((_, element) => {
                this.bind($(element));
            });
        },

        bind($wrapper) {
            if ($wrapper.data("location-initialized")) {
                return;
            }

            $wrapper.data("location-initialized", true);

            const $province = $wrapper.find(".province");

            const $district = $wrapper.find(".district");

            const $ward = $wrapper.find(".ward");

            const oldProvince = $province.data("selected");

            const oldDistrict = $district.data("selected");

            const oldWard = $ward.data("selected");

            this.loadOptions(this.routes.provinces, $province, oldProvince)
                .then(() => {
                    if (!oldProvince) {
                        return;
                    }

                    return this.loadOptions(
                        this.routes.districts,
                        $district,
                        oldDistrict,
                        {
                            province_id: oldProvince,
                        },
                    );
                })
                .then(() => {
                    if (!oldDistrict) {
                        return;
                    }

                    return this.loadOptions(this.routes.wards, $ward, oldWard, {
                        district_id: oldDistrict,
                    });
                });

            $province.on("change.location", () => {
                const provinceId = $province.val();

                this.reset($district);

                this.reset($ward);

                if (!provinceId) {
                    return;
                }

                this.loadOptions(this.routes.districts, $district, null, {
                    province_id: provinceId,
                });
            });

            $district.on("change.location", () => {
                const districtId = $district.val();

                this.reset($ward);

                if (!districtId) {
                    return;
                }

                this.loadOptions(this.routes.wards, $ward, null, {
                    district_id: districtId,
                });
            });
        },

        loadOptions(url, $target, selected = null, params = {}) {
            return new Promise((resolve, reject) => {
                $target
                    .prop("disabled", true)
                    .html('<option value="">Đang tải...</option>');

                $.get(url, params)
                    .done((res) => {
                        let html = '<option value="">-- Chọn --</option>';

                        (res.data || []).forEach((item) => {
                            html += `
                                    <option
                                        value="${item.id}"
                                        ${
                                            selected == item.id
                                                ? "selected"
                                                : ""
                                        }>
                                        ${item.name}
                                    </option>
                                `;
                        });

                        $target.html(html);

                        if (selected) {
                            $target.val(selected);
                        }

                        $target
                            .prop("disabled", false)
                            .trigger("change.select2");

                        resolve(res);
                    })
                    .fail(() => {
                        toastr.error("Không tải được dữ liệu địa chỉ");

                        $target.prop("disabled", false);

                        reject();
                    });
            });
        },

        reset($select) {
            $select
                .html('<option value="">-- Chọn --</option>')
                .val("")
                .trigger("change.select2");
        },

        reload($wrapper) {
            $wrapper.removeData("location-initialized");

            this.bind($wrapper);
        },
    };

    window.App.Location = Location;

    $(function () {
        Location.init();
    });
})(jQuery, window);
