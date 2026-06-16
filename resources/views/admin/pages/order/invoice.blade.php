<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">

    <title>Invoice {{ $order->order_code }}</title>

    <link rel="shortcut icon" href="{{ public_path(imagePath()['favicon']['path'] . '/' . $logoIcon['favicon']) }}"
        type="image/png">

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .container {
            width: 100%;
        }

        .header {
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo {
            max-width: 140px;
            margin-bottom: 10px;
        }

        .shop-name {
            font-size: 22px;
            font-weight: bold;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 28px;
        }

        .invoice-code {
            color: #666;
        }

        .section-title {
            background: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th {
            background: #f3f4f6;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;

            page-break-inside: avoid;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 6px 0;
        }

        .grand-total {
            border-top: 2px solid #000;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid #333;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td>

                    @php
                        if ($logoIcon['favicon']) {
                            $logoPath = public_path(imagePath()['favicon']['path'] . '/' . $logoIcon['favicon']);
                            $size = 100;
                        } else {
                            $logoPath = public_path('assets/images/default.png');
                            $size = 50;
                        }
                    @endphp

                    @if (file_exists($logoPath))
                        <img src="{{ $logoPath }}" width="{{ $size }}">
                    @endif

                    <div class="shop-name">
                        Heaven Shop
                    </div>

                    <div>
                        Website: heavenshop.me
                    </div>

                    <div>
                        Hotline: 0988 888 888
                    </div>

                    <div>
                        Email: support@heavenshop.me
                    </div>

                </td>

                <td class="invoice-title">

                    <h1>HÓA ĐƠN</h1>

                    <div class="invoice-code">
                        #{{ $order->order_code }}
                    </div>

                    <br>

                    <div>
                        Ngày đặt:
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>

                    <div>
                        Thanh toán:
                        {{ strtoupper($order->payment_method) }}
                    </div>

                    <div>
                        Trạng thái:
                        {{ ucfirst($order->payment_status) }}
                    </div>

                </td>
            </tr>
        </table>

        {{-- CUSTOMER --}}
        <div class="section-title">
            THÔNG TIN KHÁCH HÀNG
        </div>

        <table class="info-table">
            <tr>

                <td>
                    <strong>Người nhận</strong><br>

                    {{ $order->shipping_name }}<br>

                    {{ $order->shipping_phone }}<br>

                    {{ $order->shipping_email }}
                </td>

                <td>
                    <strong>Địa chỉ giao hàng</strong><br>

                    {{ $order->shipping_address }}<br>

                    {{ $order->shipping_ward }},
                    {{ $order->shipping_district }},
                    {{ $order->shipping_province }}
                </td>

            </tr>
        </table>

        {{-- PRODUCT --}}
        <div class="section-title">
            DANH SÁCH SẢN PHẨM
        </div>

        <table class="product-table">

            <thead>

                <tr>
                    <th width="45%">Sản phẩm</th>
                    <th width="15%">Đơn giá</th>
                    <th width="10%">SL</th>
                    <th width="15%">Tổng</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($order->items as $item)
                    <tr>

                        <td>

                            <strong>
                                {{ $item->product_name }}
                            </strong>

                            <br>

                            @if ($item->color_name || $item->size_name)
                                <small>

                                    {{ $item->color_name }}

                                    @if ($item->size_name)
                                        - {{ $item->size_name }}
                                    @endif

                                </small>
                            @endif

                        </td>

                        <td class="text-right">
                            {{ number_format($item->final_price) }}đ
                        </td>

                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->total) }}đ
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        {{-- SUMMARY --}}
        <div class="summary">

            <table>

                <tr>
                    <td>Tạm tính</td>

                    <td class="text-right">
                        {{ number_format($order->subtotal) }}đ
                    </td>
                </tr>

                <tr>
                    <td>Giảm giá</td>

                    <td class="text-right">
                        -{{ number_format($order->discount_amount) }}đ
                    </td>
                </tr>

                <tr>
                    <td>Phí vận chuyển</td>

                    <td class="text-right">
                        {{ number_format($order->shipping_fee) }}đ
                    </td>
                </tr>

                <tr class="grand-total">
                    <td>TỔNG THANH TOÁN</td>

                    <td class="text-right">
                        {{ number_format($order->grand_total) }}đ
                    </td>
                </tr>

            </table>

        </div>

        {{-- NOTE --}}
        @if ($order->note)
            <br>
            <br>

            <div class="section-title">
                GHI CHÚ
            </div>

            {{ $order->note }}
        @endif

        {{-- FOOTER --}}
        <div class="footer">

            <strong>
                Cảm ơn quý khách đã mua hàng tại Heaven Shop
            </strong>

            <br>

            Hóa đơn được tạo tự động bởi hệ thống.

        </div>

    </div>

</body>

</html>
