<script>
    window.couponConfig = {!! json_encode([
        'routes' => [
            'index' => route('admin.coupon.index'),
            'store' => route('admin.coupon.store'),
            'edit' => route('admin.coupon.edit'),
            'update' => route('admin.coupon.update'),
    
            'delete' => route('admin.coupon.delete'),
            'deleteAll' => route('admin.coupon.delete.all'),
    
            'restore' => route('admin.coupon.restore'),
            'restoreAll' => route('admin.coupon.restore.all'),
    
            'forceDelete' => route('admin.coupon.force.delete'),
            'forceDeleteAll' => route('admin.coupon.force.delete.all'),
    
            'changeStatus' => route('admin.coupon.change.status'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
