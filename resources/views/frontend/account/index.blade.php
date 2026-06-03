@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/account/style.css') }}">
@endpush

@section('content')
    <div class="account-page">

        @include('frontend.account.partials.sidebar')

        <main class="account-content">

            @yield('account-content')

        </main>

    </div>
@endsection

@include('frontend.account.partials.config')

@push('scripts')
    <script src="{{ asset('frontend/js/account/index.js') }}"></script>
@endpush
