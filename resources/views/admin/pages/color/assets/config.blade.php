<script>
    window.colorConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.color.index'),
            'store' => route('admin.color.store'),
            'edit' => route('admin.color.edit'),
            'update' => route('admin.color.update'),
    
            'delete' => route('admin.color.delete'),
            'deleteAll' => route('admin.color.delete.all'),
    
            'restore' => route('admin.color.restore'),
            'restoreAll' => route('admin.color.restore.all'),
    
            'forceDelete' => route('admin.color.force.delete'),
            'forceDeleteAll' => route('admin.color.force.delete.all'),
    
            'changeStatus' => route('admin.color.change.status'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
