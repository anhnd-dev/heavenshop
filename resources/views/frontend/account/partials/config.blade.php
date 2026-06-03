<script>
    window.accountConfig = {!! json_encode([
        'routes' => [
            'avatarUpdate' => route('account.avatar.update'),
            'profileUpdate' => route('account.profile.update'),
            'passwordUpdate' => route('account.password.update'),
    
            'orderDetail' => route('account.orders.detail', '__ID__'),
            'cancelOrder' => route('account.orders.cancel', ['order' => '__ID__']),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
