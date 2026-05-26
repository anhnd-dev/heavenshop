@if (count($cart))

    @foreach ($cart as $item)
        <li class="cart-item align-items-center">

            <div class="product-image">

                <a href="{{ route('product.show', $item['product_slug']) }}">

                    <img src="{{ asset('uploads/variant/' . $item['image']) }}" class="cart-thumb"
                        alt="{{ $item['product_name'] }}">

                </a>

            </div>

            <div class="product-detail" style="display: flex; flex-direction: column; gap: 10px;">

                <a href="{{ route('product.show', $item['product_slug']) }}">

                    {{ $item['product_name'] }}

                </a>

                @if (!empty($item['color']) || !empty($item['size']))
                    <div class="variant" style="font-size: 14px">

                        {{ $item['color'] ?? '' }}

                        @if (!empty($item['color']) && !empty($item['size']))
                            -
                        @endif

                        {{ $item['size'] ?? '' }}

                    </div>
                @endif

                <span class="item-ammount">

                    {{ $item['quantity'] }}
                    ×
                    {{ number_format($item['price'], 0, ',', '.') }}đ

                </span>

            </div>

            <div class="mini-cart-remove" data-variant="{{ $item['variant_id'] }}">
                <i class="fa fa-close"></i>
            </div>

        </li>
    @endforeach

    <li class="cart-total" style="padding-bottom: 16px;">

        <span class="mb-0 fw-600 text-dark-gray">

            Tạm tính:

        </span>

        <span class="mb-0 text-dark-gray fw-600">

            {{ number_format($subtotal, 0, ',', '.') }}đ

        </span>

    </li>

    <li class="button">

        <a href="{{ route('cart.index') }}" class="btn btn-dark-gray btn-small btn-round-edge">
            Xem giỏ hàng
        </a>

    </li>
@else
    <li style="
            padding:20px;
            text-align:center;
            color:#888;
        ">

        Giỏ hàng đang trống

    </li>

@endif
