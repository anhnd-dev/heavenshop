(function (window) {
    window.CartCache = {
        cache() {
            this.el.cartWrapper = "#cart-items-wrapper";
            this.el.fixedBar = "#fixed-bar-wrapper";
            this.el.loading = ".cart-loading-parent";

            this.el.coupon = "#coupon-code";

            this.el.addressId = "#customer_address_id";

            this.el.orderBtn = "#fixed-order-btn";

            this.el.authModal = "#checkoutAuthModal";

            this.el.addressModal = "#addressModal";

            this.el.addressForm = "#address_form";

            this.el.editAddressBtn = ".edit-address-btn";

            this.el.addAddressBtn = ".add-new-address-btn";

            this.el.addressIdInput = "#address_id";
        },
    };
})(window);
