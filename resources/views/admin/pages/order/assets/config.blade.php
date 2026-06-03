<script>
    window.orderConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.order.index'),

            'show' => route('admin.order.show', '__ID__'),

            // 'store' => route('admin.brand.store'),
            // 'edit' => route('admin.brand.edit'),
            // 'update' => route('admin.brand.update'),

            'delete' => route('admin.order.delete'),
            // 'deleteAll' => route('admin.brand.delete.all'),

            'restore' => route('admin.order.restore'),
            // 'restoreAll' => route('admin.brand.restore.all'),

            'forceDelete' => route('admin.order.force.delete'),
            // 'forceDeleteAll' => route('admin.brand.force.delete.all'),

            // 'changeStatus' => route('admin.brand.change.status'),
        ],

        // 'assets' => [
        //     'brand' => asset('uploads/brand'),
        //     'defaultImage' => asset('default.png'),
        // ],

        'csrf' => csrf_token(),
    ]) !!};
</script>
