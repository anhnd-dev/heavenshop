<script>
    window.categoryConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.category.index'),
            'store' => route('admin.category.store'),
            'edit' => route('admin.category.edit'),
            'update' => route('admin.category.update'),
    
            'delete' => route('admin.category.delete'),
            'deleteAll' => route('admin.category.delete.all'),
    
            'restore' => route('admin.category.restore'),
            'restoreAll' => route('admin.category.restore.all'),
    
            'forceDelete' => route('admin.category.force.delete'),
            'forceDeleteAll' => route('admin.category.force.delete.all'),
    
            'changeStatus' => route('admin.category.change.status'),
    
            'select' => route('admin.category.select'),
        ],
    
        'assets' => [
            'category' => asset('uploads/category'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
