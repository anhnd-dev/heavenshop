<script>
    window.galleryProductConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.product.gallery.index', ['product' => '__PRODUCT_ID__']),
    
            'store' => route('admin.product.gallery.store', ['product' => '__PRODUCT_ID__']),
    
            'delete' => route('admin.product.gallery.delete', ['product' => '__PRODUCT_ID__']),
    
            'deleteAll' => route('admin.product.gallery.delete.all', ['product' => '__PRODUCT_ID__']),
    
            'restore' => route('admin.product.gallery.restore', ['product' => '__PRODUCT_ID__']),
    
            'restoreAll' => route('admin.product.gallery.restore.all', ['product' => '__PRODUCT_ID__']),
    
            'forceDelete' => route('admin.product.gallery.force.delete', ['product' => '__PRODUCT_ID__']),
    
            'forceDeleteAll' => route('admin.product.gallery.force.delete.all', ['product' => '__PRODUCT_ID__']),
        ],
    
        'assets' => [
            'gallery' => asset('uploads/gallery'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
