<script>
    window.sizeConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.size.index'),
            'store' => route('admin.size.store'),
            'edit' => route('admin.size.edit'),
            'update' => route('admin.size.update'),
    
            'delete' => route('admin.size.delete'),
            'deleteAll' => route('admin.size.delete.all'),
    
            'restore' => route('admin.size.restore'),
            'restoreAll' => route('admin.size.restore.all'),
    
            'forceDelete' => route('admin.size.force.delete'),
            'forceDeleteAll' => route('admin.size.force.delete.all'),
    
            'changeStatus' => route('admin.size.change.status'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
