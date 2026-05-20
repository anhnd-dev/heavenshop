$(document).ready(function () {
    // Cập nhật giao diện thanh toán
    updateCartUI();

    // Tải danh sách tỉnh/thành phố khi trang được tải
    loadProvinces("#province, #uprovince");

    // Xử lý thay đổi tỉnh/thành phố
    $(document).on("change", "#province", function () {
        var province_id = $(this).val();
        if (province_id) {
            loadDistricts(province_id, "#district");
        } else {
            $("#district").html('<option value="">Chọn quận/huyện</option>').prop("disabled", true);
            $("#ward").html('<option value="">Chọn phường/xã</option>').prop("disabled", true);
        }
    });

    // Xử lý thay đổi quận/huyện
    $(document).on("change", "#district", function () {
        var district_id = $(this).val();
        if (district_id) {
            loadWards(district_id, "#ward");
        } else {
            $("#ward").html('<option value="">Chọn phường/xã</option>').prop("disabled", true);
        }
    });

    // Xử lý biểu mẫu thêm địa chỉ vận chuyển
    $(document).on("submit", "#add_shipping_address_form", function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_shipping_address_btn").text("Đang lưu");

        $.ajax({
            url: window.routes.storeShippingAddress,
            method: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                updateCartUI();
            } else {
                toastr.error(res.message);
            }
            $("#add_shipping_address_btn").text("Lưu");
        }).fail(function () {
            toastr.error("Lưu địa chỉ thất bại. Vui lòng thử lại.");
            $("#add_shipping_address_btn").text("Lưu");
        });
    });

    // Nhận dữ liệu và hiển thị biểu mẫu chỉnh sửa địa chỉ
    $(document).on("click", ".editIcon", async function (e) {
        e.preventDefault();
        const id = $(this).attr("id");

        try {
            const data = await $.ajax({
                url: window.routes.editShippingAddress,
                method: "GET",
                data: { id: id },
            });

            $("#shipping_address_id").val(data.id);
            $("#first_name").val(data.first_name);
            $("#last_name").val(data.last_name);
            $("#phone").val(data.phone);
            $("#address").val(data.address);

            const province_id = data.province_id;
            const district_id = data.district_id;
            const ward_id = data.ward_id;

            // Tải dữ liệu tỉnh/thành phố, quận/huyện, phường/xã
            const [provinceData, districtData, wardData] = await Promise.all([
                loadProvinces("#uprovince", data.province_id),
                province_id ? loadDistricts(province_id, "#udistrict", data.district_id) : Promise.resolve({ results: [] }),
                district_id ? loadWards(district_id, "#uward", ward_id) : Promise.resolve({ results: [] })
            ]);

        } catch (error) {
            console.error("Error loading address data:", error);
            toastr.error("Có lỗi khi tải dữ liệu địa chỉ.");
        }
    });

    // Xử lý biểu mẫu cập nhật địa chỉ vận chuyển
    $(document).on("submit", "#update_shipping_address_form", function (e) {
        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const fd = new FormData(this);

        fd.append("_method", "PUT");
        $('#update_shipping_address_btn').text('Đang cập nhật');

        $.ajax({
            url: window.routes.updateShippingAddress,
            type: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                updateCartUI();
            } else {
                toastr.error(res.message);
            }
            $('#update_shipping_address_btn').text('Cập nhật');
            $('#editShippingAddressModal').modal("hide");
        }).fail(function () {
            toastr.error("Cập nhật địa chỉ thất bại. Vui lòng thử lại.");
            $("#update_shipping_address_btn").text("Cập nhật");
            $('#editShippingAddressModal').modal("hide");
        });
    });
});

// Hàm tải giao diện thanh toán
function updateCartUI() {
    $.ajax({
        type: "GET",
        url: window.routes.loadCheckoutView,
        success: function (response) {
            $("#loadCheckout").html(response);
        },
        error: function () {
            // toastr.error("Có lỗi khi tải giao diện thanh toán.");
        }
    });
}

// Hàm tải danh sách tỉnh/thành phố
function loadProvinces(selector, selectedId) {
    return $.ajax({
        url: window.routes.loadCheckoutProvince,
        type: "GET",
        success: function (data) {
            const provinceOptions = '<option value="">Chọn tỉnh/thành phố</option>';
            const options = data.results
                .map(province => `<option value="${province.id}" ${province.id == selectedId ? "selected" : ""}>${province.name}</option>`)
                .join("");
            $(selector).html(provinceOptions + options);
        },
        error: function () {
            toastr.error("Có lỗi khi tải danh sách tỉnh/thành phố.");
        }
    });
}

// Hàm tải danh sách quận/huyện
function loadDistricts(provinceId, selector, selectedId) {
    return $.ajax({
        url: window.routes.loadCheckoutDistrict,
        type: "GET",
        data: { province_id: provinceId },
        success: function (data) {
            const districtOptions = '<option value="">Chọn quận/huyện</option>';
            const options = data.results
                .map(district => `<option value="${district.id}" ${district.id == selectedId ? "selected" : ""}>${district.name}</option>`)
                .join("");
            $(selector).html(districtOptions + options).prop("disabled", false);
        },
        error: function () {
            toastr.error("Có lỗi khi tải danh sách quận/huyện.");
        }
    });
}

// Hàm tải danh sách phường/xã
function loadWards(districtId, selector, selectedId) {
    return $.ajax({
        url: window.routes.loadCheckoutWard,
        type: "GET",
        data: { district_id: districtId },
        success: function (data) {
            const wardOptions = '<option value="">Chọn phường/xã</option>';
            const options = data.results
                .map(ward => `<option value="${ward.id}" ${ward.id == selectedId ? "selected" : ""}>${ward.name}</option>`)
                .join("");
            $(selector).html(wardOptions + options).prop("disabled", false);
        },
        error: function () {
            toastr.error("Có lỗi khi tải danh sách phường/xã.");
        }
    });
}
