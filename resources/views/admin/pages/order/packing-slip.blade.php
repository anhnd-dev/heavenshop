<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">

    <title>Phiếu giao hàng - {{ $order->order_code }}</title>

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            margin-bottom: 20px;
        }

        .header td {
            vertical-align: top;
        }

        .logo {
            max-height: 70px;
        }

        .shop-name {
            font-size: 22px;
            font-weight: bold;
            margin-top: 5px;
        }

        .document-title {
            text-align: right;
        }

        .document-title h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }

        .order-code {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .section-title {
            background: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .customer-box {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 20px;
        }

        .customer-box strong {
            display: inline-block;
            min-width: 120px;
        }

        .product-table {
            margin-top: 10px;
        }

        .product-table th {
            background: #f8f8f8;
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

        .qty {
            font-size: 16px;
            font-weight: bold;
        }

        .note-box {
            border: 1px solid #ddd;
            padding: 12px;
            margin-top: 20px;
        }

        .signature {
            margin-top: 50px;
        }

        .signature td {
            text-align: center;
            width: 50%;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 70px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        tr {
            page-break-inside: avoid;
        }

        .customer-box,
        .note-box,
        .signature {
            page-break-inside: avoid;
        }
    </style>

</head>

<body>

    {{-- HEADER --}}
    <table class="header">
        <tr>

            <td width="50%">

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

            </td>

            <td width="50%" class="document-title">

                <h1>PHIẾU GIAO HÀNG</h1>

                <div class="order-code">
                    #{{ $order->order_code }}
                </div>

                <div>
                    Ngày đặt:
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </div>

            </td>

        </tr>
    </table>

    {{-- CUSTOMER --}}
    <div class="section-title">
        THÔNG TIN NGƯỜI NHẬN
    </div>

    <div class="customer-box">

        <p>
            <strong>Họ tên:</strong>
            {{ $order->shipping_name }}
        </p>

        <p>
            <strong>Số điện thoại:</strong>
            {{ $order->shipping_phone }}
        </p>

        <p>
            <strong>Địa chỉ:</strong>

            {{ $order->shipping_address }},
            {{ $order->shipping_ward }},
            {{ $order->shipping_district }},
            {{ $order->shipping_province }}
        </p>

    </div>

    {{-- PRODUCTS --}}
    <div class="section-title">
        DANH SÁCH SẢN PHẨM
    </div>

    <table class="product-table">

        <thead>

            <tr>

                <th width="5%">
                    #
                </th>

                <th width="50%">
                    Sản phẩm
                </th>

                <th width="15%">
                    Màu sắc
                </th>

                <th width="10%">
                    Size
                </th>

                <th width="10%">
                    SL
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach ($order->items as $index => $item)
                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>

                        <strong>
                            {{ $item->product_name }}
                        </strong>

                        @if ($item->product_sku)
                            <br>
                            SKU:
                            {{ $item->product_sku }}
                        @endif

                    </td>

                    <td class="text-center">
                        {{ $item->color_name ?: '-' }}
                    </td>

                    <td class="text-center">
                        {{ $item->size_name ?: '-' }}
                    </td>

                    <td class="text-center qty">
                        {{ $item->quantity }}
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

    {{-- NOTE --}}
    @if ($order->note)
        <div class="section-title" style="margin-top:20px;">
            GHI CHÚ GIAO HÀNG
        </div>

        <div class="note-box">
            {{ $order->note }}
        </div>
    @endif

    {{-- SIGNATURE --}}
    <table class="signature">

        <tr>

            <td>

                <div class="signature-title">
                    NHÂN VIÊN GIAO HÀNG
                </div>

                (Ký và ghi rõ họ tên)

            </td>

            <td>

                <div class="signature-title">
                    NGƯỜI NHẬN HÀNG
                </div>

                (Ký và ghi rõ họ tên)

            </td>

        </tr>

    </table>

    {{-- FOOTER --}}
    <div class="footer">

        Phiếu giao hàng được tạo tự động bởi hệ thống Heaven Shop.

    </div>

</body>

</html>
