<script>
    window.cartConfig = {!! json_encode([
        'routes' => [
            'cartItems' => route('cart.items'),
            'cartUpdate' => route('cart.update'),
            'cartRemove' => route('cart.remove'),
            'changeVariant' => route('cart.changeVariant'),
            'select' => route('cart.select'),
            'selectAll' => route('cart.selectAll'),
            'cartClear' => route('cart.clear'),
            'applyCoupon' => route('cart.applyCoupon'),
            'removeCoupon' => route('cart.removeCoupon'),
    
            'customerAddress' => route('account.address.show'),
            'addressStore' => route('account.address.store'),
            'addressUpdate' => route('account.address.update'),
            'addressSetDefault' => route('account.address.setDefault'),
    
            'checkoutPlace' => route('checkout.place'),
    
            'login' => route('auth.ajax.login'),
            'register' => route('auth.ajax.register'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
