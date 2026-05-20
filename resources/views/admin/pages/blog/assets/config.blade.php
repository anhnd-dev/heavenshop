<script>
    window.blogConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.blog.index'),
            'store' => route('admin.blog.store'),
            'edit' => route('admin.blog.edit'),
            'update' => route('admin.blog.update'),
    
            'delete' => route('admin.blog.delete'),
            'deleteAll' => route('admin.blog.delete.all'),
    
            'restore' => route('admin.blog.restore'),
            'restoreAll' => route('admin.blog.restore.all'),
    
            'forceDelete' => route('admin.blog.force.delete'),
            'forceDeleteAll' => route('admin.blog.force.delete.all'),
    
            'changeStatus' => route('admin.blog.change.status'),
        ],
    
        'assets' => [
            'blog' => asset('uploads/blog'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
