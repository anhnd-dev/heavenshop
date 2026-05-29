<script>
    window.appConfig = {!! json_encode([
        'routes' => [
            'login' => route('auth.ajax.login'),
            'register' => route('auth.ajax.register'),
        ],
    
        'csrf' => csrf_token(),
    ]) !!};
</script>
