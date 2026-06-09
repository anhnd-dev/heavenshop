<script>
    window.orderConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.order.index'),

            'show' => route('admin.order.show', '__ID__'),

            'delete' => route('admin.order.delete'),
            // 'deleteAll' => route('admin.brand.delete.all'),

            'restore' => route('admin.order.restore'),
            // 'restoreAll' => route('admin.brand.restore.all'),
        ],

        // 'assets' => [
        //     'brand' => asset('uploads/brand'),
        //     'defaultImage' => asset('default.png'),
        // ],

        'csrf' => csrf_token(),
    ]) !!};

    window.orderDetailConfig = {!! json_encode([
        'routes' => [
            'updateStatus' => route('admin.order.updateStatus'),

            'updatePaymentStatus' => route('admin.order.updatePaymentStatus'),
        ],

        'csrf' => csrf_token(),
    ]) !!};
</script>
