<script>
    window.brandConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.brand.index'),
            'store' => route('admin.brand.store'),
            'edit' => route('admin.brand.edit'),
            'update' => route('admin.brand.update'),
    
            'delete' => route('admin.brand.delete'),
            'deleteAll' => route('admin.brand.delete.all'),
    
            'restore' => route('admin.brand.restore'),
            'restoreAll' => route('admin.brand.restore.all'),
    
            'forceDelete' => route('admin.brand.force.delete'),
            'forceDeleteAll' => route('admin.brand.force.delete.all'),
    
            'changeStatus' => route('admin.brand.change.status'),
        ],
    
        'assets' => [
            'brand' => asset('uploads/brand'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
