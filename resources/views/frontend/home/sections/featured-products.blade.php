@if ($featuredProducts->isNotEmpty())
    <section class="ps-7 pe-7 pb-3 lg-ps-3 lg-pe-3 md-pb-5 xs-px-0">
        <div class="container">
            <div class="row mb-5 xs-mb-8">
                <div class="col-12 text-center">
                    <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">
                        @if (app()->getLocale() === 'en')
                            {{ __('frontend.home.featured.title') }}
                            <span class="text-highlight fw-600">
                                {{ __('frontend.home.featured.content') }}
                                <span class="bg-base-color h-5px bottom-2px"></span>
                            </span>
                        @else
                            {{ __('frontend.home.featured.content') }}
                            <span class="text-highlight fw-600">
                                {{ __('frontend.home.featured.title') }}
                                <span class="bg-base-color h-5px bottom-2px"></span>
                            </span>
                        @endif
                    </h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <ul class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center"
                        data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                        <li class="grid-sizer"></li>

                        @forelse ($featuredProducts as $product)
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

                                            <img src="{{ asset('uploads/product/' . $product->image) }}"
                                                alt="{{ $product->name }}">

                                        </a>

                                        {{-- QUICK SIZE --}}
                                        @if ($sizes->count())
                                            <div class="quick-size">

                                                <div class="quick-size-title">

                                                    <span>
                                                        Thêm nhanh vào giỏ hàng
                                                    </span>

                                                    <button type="button" class="add-to-cart"
                                                        data-product="{{ $product->id }}">
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
                                                            data-product="{{ $product->id }}"
                                                            data-size="{{ $sizeId }}"
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
                                                ĐƠN TỪ {{ number_format($free_ship / 1000, 0) }}K
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
                                                    data-product="{{ $product->id }}"
                                                    data-color="{{ $colorId }}"
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
                                        Đang cập nhật sản phẩm nổi bật
                                    </h5>

                                </div>

                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
