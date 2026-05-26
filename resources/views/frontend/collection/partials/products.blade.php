<div class="collection-products">

    {{-- RESULT --}}
    <div class="collection-result">

        <span>
            Hiển thị
            {{ $products->firstItem() ?? 0 }}
            -
            {{ $products->lastItem() ?? 0 }}
            trong
            {{ $products->total() }}
            sản phẩm
        </span>

    </div>

    {{-- PRODUCTS --}}
    <ul class="shop-modern shop-wrapper grid grid-4col xl-grid-3col sm-grid-2col xs-grid-2col gutter-large text-start">

        <li class="grid-sizer"></li>

        @forelse ($products as $product)

            @php

                $price = $product->variants_min_price;

                $salePrice = $product->variants_min_sale_price;

                $finalPrice = $salePrice && $salePrice < $price ? $salePrice : $price;

                $discountPercent = 0;

                if ($salePrice && $salePrice < $price) {
                    $discountPercent = round((($price - $salePrice) / $price) * 100);
                }

                $colors = collect($product->variants)->groupBy('color_id');

                $sizes = collect($product->variants)->groupBy('size_id');
            @endphp

            <li class="grid-item">

                <div class="shop-box">

                    {{-- IMAGE --}}
                    <div class="shop-image">

                        @if ($discountPercent > 0)
                            <div class="product-badge">

                                -{{ $discountPercent }}%

                            </div>
                        @endif

                        <a href="{{ route('product.show', $product->slug) }}">

                            <img src="{{ asset('uploads/product/' . $product->image) }}" alt="{{ $product->name }}">

                        </a>

                        {{-- QUICK SIZE --}}
                        @if ($sizes->count())
                            <div class="quick-size">

                                <div class="quick-size-title">

                                    <span>
                                        Thêm nhanh vào giỏ hàng
                                    </span>

                                    <button type="button" class="add-to-cart" data-product="{{ $product->id }}">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>

                                </div>

                                <div class="quick-size-grid">

                                    @foreach ($sizes as $sizeId => $variants)
                                        @php
                                            $variant = $variants->first();

                                            $hasStock = $variants->sum('stock') > 0;
                                        @endphp

                                        <button type="button"
                                            class="size-item select-size {{ !$hasStock ? 'disabled' : '' }}"
                                            data-product="{{ $product->id }}" data-size="{{ $sizeId }}"
                                            {{ !$hasStock ? 'disabled' : '' }}>

                                            {{ $variant->size->name ?? '' }}

                                        </button>
                                    @endforeach

                                </div>

                            </div>
                        @endif

                        {{-- PROMO --}}
                        <div class="product-promo">

                            FREESHIP

                            <span>
                                ĐƠN TỪ 499K
                            </span>

                        </div>

                    </div>

                    {{-- COLORS --}}
                    @if ($colors->count())
                        <div class="product-colors">

                            @foreach ($colors->take(5) as $colorId => $variants)
                                @php
                                    $variant = $variants->first();

                                    $hasStock = $variants->sum('stock') > 0;
                                @endphp

                                <button type="button"
                                    class="color-dot select-color {{ !$hasStock ? 'disabled' : '' }}"
                                    data-product="{{ $product->id }}" data-color="{{ $colorId }}"
                                    style="background: {{ $variant->color->code ?? '#ddd' }}"
                                    {{ !$hasStock ? 'disabled' : '' }}>
                                </button>
                            @endforeach

                            @if ($colors->count() > 5)
                                <span class="more-color">

                                    +{{ $colors->count() - 5 }}

                                </span>
                            @endif

                        </div>
                    @endif

                    {{-- INFO --}}
                    <div class="shop-footer">

                        <a href="{{ route('product.show', $product->slug) }}" class="product-name">

                            {{ shortenText($product->name, 45) }}

                        </a>

                        <div class="wrap-price">

                            @if ($salePrice && $salePrice < $price)
                                <span class="sale-price">

                                    {{ number_format($salePrice, 0, ',', '.') }}đ

                                </span>

                                <span class="origin-price">

                                    {{ number_format($price, 0, ',', '.') }}đ

                                </span>
                            @else
                                <span class="sale-price">

                                    {{ number_format($finalPrice, 0, ',', '.') }}đ

                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </li>

        @empty

            <li class="grid-item w-100">

                <div class="empty-product">

                    <div class="empty-product-icon">

                        <i class="fa-solid fa-box-open"></i>

                    </div>

                    <h5>
                        Không tìm thấy sản phẩm
                    </h5>

                    <p>
                        Vui lòng thử bộ lọc khác
                    </p>

                </div>

            </li>
        @endforelse

    </ul>

    {{-- PAGINATION --}}
    @if ($products->hasPages())
        <div class="mt-5">

            {!! customPagination($products) !!}

        </div>
    @endif

</div>
