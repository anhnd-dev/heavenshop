<script>
    window.galleryProductConfig = {!! json_encode([
        'routes' => [
            // =========================
            // INDEX
            // =========================
            'index' => route('admin.product.gallery.index', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            // =========================
            // STORE
            // =========================
            'store' => route('admin.product.gallery.store', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            // =========================
            // UPDATE
            // =========================
            'update' => route('admin.product.gallery.update', [
                'product' => '__PRODUCT_ID__',
                'id' => '__ID__',
            ]),
    
            // =========================
            // DELETE
            // =========================
            'delete' => route('admin.product.gallery.delete', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            'deleteAll' => route('admin.product.gallery.delete.all', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            // =========================
            // RESTORE
            // =========================
            'restore' => route('admin.product.gallery.restore', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            'restoreAll' => route('admin.product.gallery.restore.all', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            // =========================
            // FORCE DELETE
            // =========================
            'forceDelete' => route('admin.product.gallery.force.delete', [
                'product' => '__PRODUCT_ID__',
            ]),
    
            'forceDeleteAll' => route('admin.product.gallery.force.delete.all', [
                'product' => '__PRODUCT_ID__',
            ]),
        ],
    
        'assets' => [
            'gallery' => asset('uploads/gallery'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
