@extends('frontend.account.index')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/account/profile.css') }}">
@endpush

@section('account-content')
    <div class="account-card">

        <div class="account-profile">

            <div class="profile-avatar">

                <img id="avatarPreview"
                    src="{{ $customer->avatar ? asset('uploads/account/' . $customer->avatar) : asset('default.png') }}"
                    alt="{{ $customer->name }}">

                <input type="file" id="avatarInput" accept="image/*" hidden>

                <div class="avatar-overlay">
                    <i class="fa-solid fa-camera"></i>
                </div>

            </div>

            <div class="profile-info">
                <h2>{{ $customer->name }}</h2>
                <p>{{ $customer->phone }}</p>
            </div>

        </div>

        {{-- THÔNG TIN CÁ NHÂN --}}
        <div class="info-card">

            <div class="card-header">
                <h1 class="section-title">
                    Thông tin tài khoản
                </h1>

                <button class="update-btn" data-modal-open="#profileModal">
                    Cập nhật
                </button>
            </div>

            <div class="info-list">

                <div class="info-row">
                    <span class="info-label">Họ và tên</span>
                    <span class="info-value">
                        {{ $customer->name }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Số điện thoại</span>
                    <span class="info-value">
                        {{ $customer->phone }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Giới tính</span>
                    <span class="info-value">

                        @switch($customer->gender)
                            @case('male')
                                Nam
                            @break

                            @case('female')
                                Nữ
                            @break

                            @default
                                Chưa cập nhật
                        @endswitch

                    </span>
                </div>

            </div>

        </div>

        {{-- ĐĂNG NHẬP --}}
        <div class="info-card">

            <div class="card-header">
                <h2 class="section-title">
                    Thông tin đăng nhập
                </h2>

                <button class="update-btn" data-modal-open="#passwordModal">
                    Đổi mật khẩu
                </button>
            </div>

            <div class="info-list">

                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        {{ $customer->email ?: 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Mật khẩu</span>
                    <span class="info-value">
                        ••••••••••••••••
                    </span>
                </div>

            </div>

        </div>

    </div>

    @include('frontend.account.partials.profile-modal')
    @include('frontend.account.partials.password-modal')
@endsection
