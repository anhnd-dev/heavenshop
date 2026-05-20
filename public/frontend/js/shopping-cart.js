$(document).ready(function () {
    // Tạo hàm debounced cho các hàm cần tối ưu
    const debouncedUpdateCartQuantity = debounce(updateCartQuantity, 300);
    const debouncedToggleDeleteButton = debounce(toggleDeleteButton, 300);
    const debouncedCalculateSubtotal = debounce(calculateSubtotal, 300);

    // Cập nhật giao diện giỏ hàng
    function updateCartUI() {
        loadCart();
        loadCartCount();
        loadCartDropdown();
    }

    updateCartUI();

    // Cập nhật số lượng sản phẩm
    $(document).on("change", ".quantity-input-cart", function () {
        let $input = $(this);
        let quantity = parseInt($input.val());
        let quantityStock = $("#quantity-stock").val();

        if (quantity > quantityStock) {
            toastr.error(
                `The number entered only ranges from 1 - ${quantityStock}`
            );
            $input.val(1);
            return;
        }

        let rowId = $input.data("row_id");
        let productId = $input.data("product_id");

        $input.val(quantity);
        debouncedUpdateCartQuantity(rowId, productId, quantity);
        updateCheckboxState(rowId);
        debouncedCalculateSubtotal();
    });

    // Xử lý tăng giảm số lượng sản phẩm
    $(document).on("click", ".plus-cart-quantity, .minus-cart-quantity", function () {
        let $input = $(this).siblings(".quantity-input-cart");
        let quantity = parseInt($input.val());
        let quantityStock = parseInt($("#quantity-stock").val());

        if ($(this).hasClass("plus-cart-quantity")) {
            quantity++;
            if (quantity > quantityStock) {
                toastr.error(
                    `The number entered only ranges from 1 - ${quantityStock}`
                );
                return;
            }
        } else if ($(this).hasClass("minus-cart-quantity") && quantity > 1) {
            quantity--;
        }

        let rowId = $input.data("row_id");
        let productId = $input.data("product_id");

        $input.val(quantity);
        debouncedUpdateCartQuantity(rowId, productId, quantity);
        updateCheckboxState(rowId);
        debouncedCalculateSubtotal();
    });

    // Xử lý checkbox chọn tất cả
    $(document).on("click change", "#select-all, .select-item", function () {
        let isChecked = this.checked;
        $(".select-item").prop("checked", isChecked);
        debouncedToggleDeleteButton();
        debouncedCalculateSubtotal();
    });

    // Xóa mục giỏ hàng
    $(document).on("click", ".remove-item-cart", function (e) {
        e.preventDefault();
        let rowId = $(this).data("row_id");

        confirmAction("Bạn có thực sự muốn xóa sản phẩm?", function () {
            $.ajax({
                url: window.routes.cartRemoveItem,
                method: "DELETE",
                data: { rowId: rowId },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                        swal("Deleted!", response.message, "success");
                        updateCartUI();
                    } else {
                        swal("Deleted!", response.message, "error");
                    }
                },
                error: function () {
                    toastr.error("Đã xảy ra lỗi khi xóa các mục.");
                },
            });
        });
    });

    // Xóa các mục được chọn
    $(document).on("click", "#delete-selected", function (e) {
        e.preventDefault();
        let selectedItems = [];

        $(".select-item:checked").each(function () {
            selectedItems.push($(this).data("row_id"));
        });

        if (selectedItems.length > 0) {
            confirmAction(
                "Bạn có thực sự muốn xóa các sản phẩm đã chọn?",
                function () {
                    $.ajax({
                        url: window.routes.cartDeleteSelectedItem,
                        method: "DELETE",
                        data: { rowIds: selectedItems },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.status == 200) {
                                swal("Deleted!", response.message, "success");
                                updateCartUI();
                            }
                        },
                        error: function (error) {
                            toastr.error(
                                "Đã xảy ra lỗi khi xóa các mục đã chọn."
                            );
                        },
                    });
                }
            );
        } else {
            toastr.error("Vui lòng chọn ít nhất một mục để xóa.");
        }
    });

    // Xử lý việc xóa tất cả giỏ hàng
    $(document).on("click", ".remove-all-cart", function (e) {
        e.preventDefault();

        confirmAction(
            "After deleting, you can still restore this file in the trash!",
            function () {
                $.ajax({
                    url: window.routes.cartRemoveAllItem,
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == 200) {
                            swal("Deleted!", response.message, "success");
                            updateCartUI();
                        }
                    },
                    error: function (error) {
                        toastr.error("Đã xảy ra lỗi khi xóa các mục đã chọn.");
                    },
                });
            }
        );
    });

    // Cập nhật phí vận chuyển khi thay đổi phương thức vận chuyển
    $(document).on("change", "#shipping-method", function () {
        saveShippingMethodToSession($(this).val());
        debouncedCalculateSubtotal();
    });
});

// Hàm debounce
function debounce(func, delay) {
    let debounceTimer;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

// Hàm tải giỏ hàng
function loadCart() {
    $.ajax({
        type: "GET",
        url: window.routes.loadCartView,
        success: function (response) {
            $("#loadCart").html(response);
        },
    });
}

// Hàm tải số lượng giỏ hàng
function loadCartCount() {
    $.ajax({
        type: "GET",
        url: window.routes.loadCartCount,
        success: function (data) {
            if (data.status == 200) {
                $("#cart-count").html(data.cartCount);
            }
        },
    });
}

// Hàm tải danh sách sản phẩm trong dropdown giỏ hàng
function loadCartDropdown() {
    $.ajax({
        type: "GET",
        url: window.routes.loadCartDropdown,
        success: function (data) {
            $("#cartItemList").html(data);
        },
    });
}

// Tính toán subtotal
function calculateSubtotal() {
    let freeShippingThreshold = 300000;
    let subtotal = 0;

    $(".select-item:checked").each(function () {
        const $item = $(this).closest(".bg-very-light-gray");
        let priceText = $item.find(".price").text();
        let price = parseFloat(
            priceText.replace(/₫/g, "").replace(/\./g, "").replace(/,/g, "")
        );
        let quantity = parseInt($item.find(".quantity-input-cart").val()) || 1;
        subtotal += price * quantity;
    });

    // Áp dụng giảm giá và phí vận chuyển
    applyDiscount(subtotal);

    // Đẩy subtotal lên view
    $("#subtotalAfter").val(subtotal);

    let remainingShippingAmount = freeShippingThreshold - subtotal;
    let shippingInfo = "";

    // Kiểm tra nếu subtotal >= 300k thì vô hiệu hóa lựa chọn phương thức giao hàng
    if (subtotal >= freeShippingThreshold) {
        saveShippingMethodToSession($('#free-shipping').data('shipping_method'));

        $("#shipping-method").val("").prop("disabled", true).hide();
        $("#free-shipping").show();

        shippingInfo = `
            <li style="font-size: 14px; line-height: 18px;">
                Bạn đã đủ điều kiện để được <strong>giao hàng Miễn Phí!</strong>
            </li>
        `;
    } else {
        $("#shipping-method").prop("disabled", false).show();
        $("#free-shipping").hide();

        shippingInfo = `
            <li style="font-size: 14px; line-height: 18px;">
                Mua thêm <strong style="font-weight:700;">${formatCurrency(
                    remainingShippingAmount
                )}</strong> hoặc nhiều hơn để thưởng <strong>giao hàng Miễn Phí!</strong>
            </li>
        `;
    }

    $("#shipping-info").html(shippingInfo);
}

// Hàm áp dụng giảm giá
function applyDiscount(subtotal) {
    $.ajax({
        url: window.routes.cartApplyCoupon,
        type: "POST",
        data: { subtotal: subtotal },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            let totalAmount = subtotal;
            let discount = 0;

            if (response.status === 200 && response.coupon) {
                const coupon = response.coupon;

                discount = calculateDiscount(coupon, subtotal);
                totalAmount -= discount;

                // Lưu coupon vào session
                saveCouponToSession(coupon);

                updateProgressBar(
                    subtotal,
                    coupon.threshold,
                    0,
                    coupon.discount_type,
                    coupon.discount_value
                );
            } else {
                forgetCouponToSession();
            }

            // Lấy phí vận chuyển
            const { shippingFee, shippingDeliveryTime } =
                calculateShippingFee(discount);

            totalAmount += shippingFee;

            updateCartUIWithDiscount(
                subtotal,
                discount,
                totalAmount,
                shippingFee,
                shippingDeliveryTime
            );

            if (response.remaining_amount && response.next_threshold) {
                updateProgressBar(
                    subtotal,
                    response.next_threshold,
                    response.remaining_amount,
                    response.discount_type,
                    response.discount_value
                );
            }
        },
        error: function () {
            toastr.error("Đã xảy ra lỗi khi áp dụng mã giảm giá.");
        },
    });
}

// Kiểm tra mã giảm giá hợp lệ
function isCouponValid(coupon) {
    const now = new Date();
    const startDate = new Date(coupon.start_date);
    const endDate = new Date(coupon.end_date);

    return coupon.is_active && now >= startDate && now <= endDate;
}

// Hàm tính toán giảm giá
function calculateDiscount(coupon, totalAmount) {
    if (!coupon || !isCouponValid(coupon)) {
        return 0;
    }

    let discount = 0;

    if (coupon.discount_type === "percentage") {
        discount = (totalAmount * coupon.discount_value) / 100;
    } else if (coupon.discount_type === "fixed") {
        discount = coupon.discount_value;
    }

    // Đảm bảo giảm giá không vượt quá tổng tiền
    return Math.min(discount, totalAmount);
}

// Tính phí vận chuyển
function calculateShippingFee(discount) {
    let shippingFee = 0;
    let shippingDeliveryTime = "Không rõ";
    let totalAmountAfter = 0;
    let totalAmount = 0;

    $(".select-item:checked").each(function () {
        const $item = $(this).closest(".bg-very-light-gray");
        let priceText = $item.find(".price").text();
        let price = parseFloat(
            priceText.replace(/₫/g, "").replace(/\./g, "").replace(/,/g, "")
        );
        let quantity = parseInt($item.find(".quantity-input-cart").val());
        totalAmount += price * quantity;
    });

    // Lấy phí vận chuyển từ chọn lựa phương thức vận chuyển
    let selectedShippingMethod = $("#shipping-method").val();

    if (selectedShippingMethod) {
        $.ajax({
            url: window.routes.getShippingFee,
            type: "POST",
            data: { method: selectedShippingMethod, totalAmount: totalAmount },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                shippingFee = response.fee || 0;
                shippingDeliveryTime = response.delivery_time || "Không rõ";

                totalAmountAfter =
                    totalAmount + parseInt(shippingFee) - discount;

                updateCartUIWithDiscount(
                    totalAmount,
                    discount,
                    totalAmountAfter,
                    shippingFee,
                    shippingDeliveryTime
                );
            },
            error: function () {
                toastr.error("Đã xảy ra lỗi khi tính phí vận chuyển.");
            },
        });
    }

    return {
        shippingFee,
        shippingDeliveryTime,
    };
}

// Hàm cập nhật thanh tiến trình
function updateProgressBar(
    totalAmount,
    nextThreshold,
    remainingAmount,
    discountType,
    discountValue
) {
    let $progressBar = $("#progress-bar");
    let $progressText = $("#progress-text");
    let $progressIcon = $("#progress-icon");

    // Tính phần trăm tiến độ
    let progressPercent = Math.min((totalAmount / nextThreshold) * 100, 100);

    let roundedPercent = Math.round(progressPercent);
    if (roundedPercent >= 97 && roundedPercent < 100) {
        roundedPercent = 100;
    }

    // Cập nhật thanh tiến trình và văn bản
    $progressBar.css("width", roundedPercent + "%");
    $progressText.text(Math.round(roundedPercent) + "%");

    // Cập nhật biểu tượng
    if (roundedPercent === 100) {
        $progressIcon.html('<i class="fa-solid fa-check"></i>');
    } else {
        $progressIcon.html('<i class="fa-solid fa-gift"></i>');
    }

    // Cập nhật thông báo cho người dùng
    if (remainingAmount > 0) {
        $("#progress-message").html(
            `Mua thêm <strong style="font-weight:700; color: #c44a01">${formatCurrency(
                remainingAmount
            )}</strong> để kiếm phiếu giảm giá <span style="color: #c44a01">${discountValue} ${
                discountType === "percentage" ? "%" : "₫"
            }</span> MIỄN PHÍ!`
        );
    } else {
        $("#progress-message").html(
            `Đủ điều kiện để nhận khuyến mại ${discountValue}${
                discountType === "percentage" ? "%" : "₫"
            } sau khi đặt hàng.`
        );
    }
}

// Cập nhật số lượng giỏ hàng
function updateCartQuantity(rowId, productId, quantity) {
    $.ajax({
        url: window.routes.cartUpdateQuantity,
        type: "POST",
        data: {
            rowId: rowId,
            productId: productId,
            quantity: quantity,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.status == 200) {
                toastr.success(response.message, "Success");
                loadCartCount();
                loadCartDropdown();
                updateCheckboxState(rowId);
                calculateSubtotal();
            } else {
                toastr.error(response.message, "Error");
            }
        },
        error: function (error) {
            toastr.error("Đã xảy ra lỗi khi cập nhật số lượng.");
        },
    });
}

// Hiển thị hoặc ẩn nút xóa các mục đã chọn
function toggleDeleteButton() {
    let anyChecked =
        $(".select-item").length === $(".select-item:checked").length;
    $("#select-all").prop("checked", anyChecked);
    $("#delete-selected").toggleClass("hidden", !anyChecked);
    calculateSubtotal();
}

// Hàm hiển thị xác nhận hành động
function confirmAction(message, callback) {
    swal(
        {
            title: window.translations.title,
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: window.translations.confirmText,
            cancelButtonText: window.translations.cancelText,
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                callback();
            } else {
                swal("Error!", "Operation failed", "error");
            }
        }
    );
}

// Hàm cập nhật trạng thái checkbox
function updateCheckboxState(rowId) {
    $(".select-item").each(function () {
        if ($(this).data("row_id") === rowId) {
            $(this).prop("checked", true);
        }
    });
}

// Cập nhật giao diện giỏ hàng với mã giảm giá
function updateCartUIWithDiscount(
    subtotal,
    discount,
    totalAmount,
    shippingFee,
    shippingDeliveryTime
) {
    const $subtotalAmount = $(".subtotal-amount");
    const $discount = $(".discount");
    const $totalAmount = $(".total-amount");
    const $shippingFee = $(".shipping-fee");
    const $shippingDeliveryTime = $(".shipping-time");

    $subtotalAmount.text(formatCurrency(subtotal));

    $discount
        .removeClass("hidden")
        .find("span")
        .text("-" + formatCurrency(discount));

    $shippingFee.text(formatCurrency(shippingFee));
    $totalAmount.text(formatCurrency(totalAmount));
    $shippingDeliveryTime.text(shippingDeliveryTime);
}

// Hàm chuyển đổi đơn vị tiền
function formatCurrency(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "₫";
}

// Hàm kiểm tra người dùng đăng nhập chuyển hướng sang trang đặt hàng
function checkAuthAndProceedToCheckout() {
    let selectedItems = [];

    // Thu thập tất cả các mục được chọn
    $(".select-item:checked").each(function () {
        selectedItems.push($(this).data("row_id"));
    });

    // Kiểm tra nếu không có sản phẩm nào được chọn
    if (selectedItems.length === 0) {
        toastr.error("Vui lòng chọn các mặt hàng bạn muốn thanh toán");
        return;
    }

    let shippingMethod = $("#shipping-method").val();
    let subtotal = parseFloat($("#subtotalAfter").val());
    let freeShippingThreshold = 300000;

    // Kiểm tra điều kiện để tiến hành checkout
    let canProceedToCheckout = (subtotal >= freeShippingThreshold) || shippingMethod;

    if (canProceedToCheckout) {
        $.ajax({
            type: "GET",
            url: window.routes.checkAuth,
            success: function (response) {
                if (response.isLoggedIn) {
                    // Nếu đã đăng nhập, chuyển sang bước "Đặt hàng"
                    window.location.href = window.routes.checkout;
                } else {
                    // Nếu chưa đăng nhập, hiển thị modal đăng nhập
                    $("#loginModal").modal("show");
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại.");
            }
        });
    } else {
        toastr.error("Vui lòng chọn phương thức vận chuyển trước khi tiếp tục.");
    }
}


// Lưu coupon vào session
function saveCouponToSession(coupon) {
    $.ajax({
        url: window.routes.saveCouponSession,
        type: "POST",
        data: { coupon: coupon },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // if (response.status !== 200) {
            //     toastr.error("Không thể lưu mã giảm giá vào session.");
            // }
        },
        error: function () {
            toastr.error("Đã xảy ra lỗi khi lưu mã giảm giá vào session.");
        },
    });
}

// Hủy coupon từ session
function forgetCouponToSession() {
    $.ajax({
        url: window.routes.forgetCouponSession,
        type: "GET",
        success: function (response) {
            // if (response.status !== 200) {
            //     toastr.error("Không thể lưu mã giảm giá vào session.");
            // }
        },
        error: function () {
            toastr.error("Đã xảy ra lỗi khi lưu mã giảm giá vào session.");
        },
    });
}

// Lưu phương thức vận chuyển vào session
function saveShippingMethodToSession(method) {
    $.ajax({
        url: window.routes.saveShippingMethodSession,
        type: "POST",
        data: { method: method },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // if (response.status !== 200) {
            //     toastr.error("Không thể lưu phương thức vận chuyển vào session.");
            // }
        },
        error: function () {
            toastr.error(
                "Đã xảy ra lỗi khi lưu phương thức vận chuyển vào session."
            );
        },
    });
}
