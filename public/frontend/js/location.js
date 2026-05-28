(function ($) {
    "use strict";

    $(function () {
        initLocation();
    });

    function initLocation() {
        $(".location-wrapper").each(function () {
            bindLocation($(this));
        });
    }

    function bindLocation($wrapper) {
        let $province = $wrapper.find(".province");
        let $district = $wrapper.find(".district");
        let $ward = $wrapper.find(".ward");

        let oldProvince = $province.data("selected");
        let oldDistrict = $district.data("selected");
        let oldWard = $ward.data("selected");

        // 🚀 Load province → district → ward (edit mode)
        loadOptions("/api/location/provinces", $province, oldProvince)
            .then(() => {
                if (oldProvince) {
                    return loadOptions(
                        "/api/location/districts",
                        $district,
                        oldDistrict,
                        { province_id: oldProvince },
                    );
                }
            })
            .then(() => {
                if (oldDistrict) {
                    return loadOptions("/api/location/wards", $ward, oldWard, {
                        district_id: oldDistrict,
                    });
                }
            });

        // Province → District
        $province.on("change", function () {
            let provinceId = $(this).val();

            resetSelect($district);
            resetSelect($ward);

            if (provinceId) {
                loadOptions("/api/location/districts", $district, null, {
                    province_id: provinceId,
                });
            }
        });

        // District → Ward
        $district.on("change", function () {
            let districtId = $(this).val();

            resetSelect($ward);

            if (districtId) {
                loadOptions("/api/location/wards", $ward, null, {
                    district_id: districtId,
                });
            }
        });
    }

    /**
     * Load options (AJAX)
     */
    function loadOptions(url, $target, selected = null, params = {}) {
        return new Promise((resolve, reject) => {
            $target.prop("disabled", true);
            $target.html("<option>Đang tải...</option>");

            $.get(url, params)
                .done(function (res) {
                    let options = '<option value="">-- Chọn --</option>';

                    if (res.data && res.data.length) {
                        res.data.forEach((item) => {
                            let isSelected =
                                selected == item.id ? "selected" : "";
                            options += `<option value="${item.id}" ${isSelected}>${item.name}</option>`;
                        });
                    }

                    $target.html(options);

                    if (selected) {
                        $target.val(selected);
                    }

                    // trigger select2 nếu có
                    $target.trigger("change.select2");

                    $target.prop("disabled", false);

                    resolve();
                })
                .fail(function () {
                    alert("Không tải được dữ liệu");
                    $target.prop("disabled", false);
                    reject();
                });
        });
    }

    /**
     * Reset select
     */
    function resetSelect($el) {
        $el.html('<option value="">-- Chọn --</option>')
            .val(null)
            .trigger("change.select2");
    }
})(jQuery);
