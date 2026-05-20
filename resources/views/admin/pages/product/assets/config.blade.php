<script>
    window.productConfig = {!! json_encode([
        'routes' => [
            // Product
            'index' => route('admin.product.index'),
            'store' => route('admin.product.store'),
            'edit' => route('admin.product.edit'),
            'update' => route('admin.product.update'),
    
            'delete' => route('admin.product.delete'),
            'deleteAll' => route('admin.product.delete.all'),
    
            'restore' => route('admin.product.restore'),
            'restoreAll' => route('admin.product.restore.all'),
    
            'forceDelete' => route('admin.product.force.delete'),
            'forceDeleteAll' => route('admin.product.force.delete.all'),
    
            'changeStatus' => route('admin.product.change.status'),
        ],
    
        'assets' => [
            'product' => asset('uploads/product'),
            'variant' => asset('uploads/variant'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
