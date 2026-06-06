@extends('frontend.layouts.app')

@push('styles')
    <style>
        body {
            background: #f6f7fb;
            font-family: Arial, sans-serif;
        }

        .success-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin-top: 32px;
        }

        .success-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            width: 480px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .success-icon {
            width: 70px;
            height: 70px;
            background: #22c55e;
            color: white;
            font-size: 36px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;

            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.5);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(34, 197, 94, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .sub-text {
            color: #666;
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn.primary {
            background: #2563eb;
            color: #fff;
        }

        .btn.primary:hover {
            background: #1d4ed8;
        }

        .btn.outline {
            border: 1px solid #ddd;
            color: #333;
        }

        .btn.outline:hover {
            background: #f3f4f6;
        }

        .note {
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }
    </style>
@endpush

@section('content')
    <div class="success-wrapper">

        <div class="success-box">

            <div class="success-icon">
                ✓
            </div>

            <h1>Đặt hàng thành công!</h1>

            <p class="sub-text">
                Cảm ơn bạn đã mua hàng. Chúng tôi đã nhận được đơn của bạn và sẽ xử lý sớm nhất.
            </p>

            <div class="actions">
                <a href="{{ session('continue_shopping_url', url('/collections')) }}" class="btn primary">
                    Tiếp tục mua sắm
                </a>
                <a href="{{ route('account.orders', ['highlight' => $order_code]) }}" class="btn outline">
                    Xem đơn hàng
                </a>
            </div>

        </div>

    </div>
@endsection
