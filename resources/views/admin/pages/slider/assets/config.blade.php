<script>
    window.sliderConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.slider.index'),
            'store' => route('admin.slider.store'),
            'edit' => route('admin.slider.edit'),
            'update' => route('admin.slider.update'),
    
            'delete' => route('admin.slider.delete'),
            'deleteAll' => route('admin.slider.delete.all'),
    
            'restore' => route('admin.slider.restore'),
            'restoreAll' => route('admin.slider.restore.all'),
    
            'forceDelete' => route('admin.slider.force.delete'),
            'forceDeleteAll' => route('admin.slider.force.delete.all'),
    
            'changeStatus' => route('admin.slider.change.status'),
        ],
    
        'assets' => [
            'slider' => asset('uploads/slider'),
            'defaultImage' => asset('default.png'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
