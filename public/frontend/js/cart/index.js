(function ($, window) {
    "use strict";

    window.App = window.App || {};

    const CartPage = {
        el: {},

        init() {
            this.cache();

            this.bindCartEvents();
            this.bindCheckoutEvents();
            this.bindAddressEvents();

            this.restoreCheckoutData();
            this.autoLoadDefaultAddress();
            this.loadCartItems();
        },
    };

    Object.assign(
        CartPage,

        window.CartCache,
        window.CartApi,

        window.CartModule,
        window.CheckoutModule,
        window.AddressModule,
        window.ValidationModule,
    );

    window.App.CartPage = CartPage;

    $(function () {
        CartPage.init();
    });
})(jQuery, window);
