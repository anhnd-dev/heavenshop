@extends('layouts.base')

@push('styles')
    <style>
        .flex-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 16px;
            position: relative;
        }

        .flex-options input {
            width: 24px;
        }

        .flex-options .item-qty {
            position: absolute;
            right: 0;
            top: 0;
            background: var(--very-light-gray);
            border-radius: 100%;
            height: 30px;
            width: 30px;
            font-size: 11px;
            text-align: center;
            font-weight: 500;
            color: var(--dark-gray);
            line-height: 30px;
        }

        .custom-checkbox {
            position: relative;
            width: 25px;
            height: 25px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #eee;
            border: 1px solid #ccc;
            cursor: pointer;
            padding: 0px;
            margin-right: 4px;
        }

        .custom-checkbox:checked {
            background-color: #2196F3;
        }

        .custom-checkbox:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 15px;
            height: 15px;
            background-color: white;
            transform: translate(-50%, -50%);
            display: none;
        }

        .custom-checkbox:checked:before {
            display: block;
        }

        .custom-checkbox:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: translate(-50%, -50%) rotate(45deg);
            display: none;
        }

        .custom-checkbox:checked:after {
            display: block;
        }

        .select-options {
            display: flex;
            margin-bottom: 12px;
        }

        .select-options>div {
            margin-right: 8px;
        }

        .select-options .form-select {
            padding: 4px 24px;
        }

        .df {
            display: flex;
        }

        .shop-footer button {
            border: none;
            background: none;
        }

        .shop-footer span {
            color: #000;
            font-weight: 700;
        }

        .shop-footer .wrap-price span:nth-child(1) {
            color: #fa6338;
        }

        .shop-footer .wrap-price span:nth-child(2) {
            color: #fa6338;
            border: 1px solid;
            padding: 2px;
            margin-left: 4px;
            font-size: 12px;
        }

        .quick-discount {
            background: #000;
            color: #fff;
            font-size: 13px;
            padding: 4px 12px;
            font-weight: 500;
            vertical-align: super;
        }

        #render-links>a {
            color: rgba(45, 104, 167, 0.8);
            font-size: 14px;
        }

        #render-links>a:hover {
            color: rgba(45, 104, 167, 1);
        }
    </style>
@endpush

@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Shop</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li>Shop</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-0 ps-6 pe-6 lg-ps-2 lg-pe-2 sm-ps-0 sm-pe-0">
        <div class="container-fluid">
            <div class="row flex-row-reverse">
                <div class="col-xxl-10 col-lg-9 ps-5 md-ps-15px md-mb-60px">
                    <div class="col-12"
                        data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <div class="select-options">
                            <div class="page-view-filter">
                                <div class="dropdown select-featured">
                                    <select class="form-select" name="orderby" id="orderby">
                                        <option value="-1" {{ $order == -1 ? 'selected' : '' }}>Default
                                        </option>
                                        <option value="1" {{ $order == 1 ? 'selected' : '' }}>Date, New To
                                            Old
                                        </option>
                                        <option value="2" {{ $order == 2 ? 'selected' : '' }}>Date, Old To
                                            New
                                        </option>
                                        <option value="3" {{ $order == 3 ? 'selected' : '' }}>Price, Low To
                                            High</option>
                                        <option value="4" {{ $order == 4 ? 'selected' : '' }}>Price, High To
                                            Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="dropdown select-featured">
                                <select class="form-select" id="pagesize">
                                    <option value="12" {{ $psize == 12 ? 'selected' : '' }}>12 Products Per
                                        Page
                                    </option>
                                    <option value="24" {{ $psize == 24 ? 'selected' : '' }}>24 Products Per
                                        Page
                                    </option>
                                    <option value="52" {{ $psize == 52 ? 'selected' : '' }}>52 Products Per
                                        Page
                                    </option>
                                    <option value="100" {{ $psize == 100 ? 'selected' : '' }}>100 Products Per
                                        Page</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <ul class="shop-modern shop-wrapper grid-loading grid grid-4col xl-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center"
                        data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                        <li class="grid-sizer"></li>

                        @foreach ($products as $product)
                            <li class="grid-item">
                                <div class="shop-box mb-10px">
                                    <div class="shop-image mb-15px">
                                        <a href="{{ route('product.details', ['slug' => $product->slug]) }}">
                                            <img src="{{ asset('/uploads/product/' . $product->image) }}"
                                                alt="{{ $product->name }}" />

                                            {{-- <span class="lable new">New</span> --}}
                                            <div class="shop-overlay bg-gradient-gray-light-dark-transparent"></div>
                                        </a>
                                    </div>
                                    <div class="shop-footer">
                                        <a href="{{ route('product.details', ['slug' => $product->slug]) }}"
                                            class="alt-font text-dark-gray fs-16 fw-100">{{ shortenText($product->name, 28) }}</a>

                                        <div class="df justify-content-between align-items-center">
                                            @if ($product->discount > 0)
                                                @php
                                                    $discountAmount = ($product->price * $product->discount) / 100;
                                                    $discountedPrice = $product->price - $discountAmount;
                                                @endphp

                                                <div class="wrap-price">
                                                    <span>
                                                        {{ number_format($discountedPrice, 0, ',', '.') }}₫
                                                    </span>
                                                    <span>
                                                        - {{ $product->discount }}%
                                                    </span>
                                                </div>
                                            @else
                                                <span>{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                            @endif

                                            <button type="button" id="{{ $product->id }}" class="quickViewBtn"
                                                data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                                                    class="feather icon-feather-shopping-bag"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    {!! customPagination($products) !!}
                </div>

                <div class="col-xxl-2 col-lg-3 shop-sidebar"
                    data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>

                    <div class="mb-30px">
                        <span class="alt-font fw-500 fs-19 text-dark-gray d-block mb-10px">Filter by categories</span>
                        <ul class="shop-filter category-filter fs-16">
                            @foreach ($categories as $category)
                                <li style="padding: 0px">
                                    <button class="collapsible" data-category-id="{{ $category->id }}"
                                        style="border: none; background: none; color: currentcolor;"><i class="fa fa-plus"
                                            style="margin-right: 4px;"></i> {{ $category->name }}</button>

                                    @if ($category->subcategories->count() > 0)
                                        <ul class="content" id="collapsible-{{ $category->id }}"
                                            style="padding-left: 18px; display: none;">
                                            @foreach ($category->subcategories as $subcategory)
                                                <li style="padding: 0px; display: flex">
                                                    <div class="form-check ps-0 custom-form-check flex-options">
                                                        <input class="checkbox_animated check-it"
                                                            id="{{ $subcategory->id }}"
                                                            data-category-id="{{ $category->id }}" name="subcategories"
                                                            @if (in_array($subcategory->id, explode(',', $q_subcategories))) checked="checked" @endif
                                                            value="{{ $subcategory->id }}" type="checkbox"
                                                            onchange="filterProductsBySubCategory()" />
                                                        <label class="form-check-label">{{ $subcategory->name }}</label>
                                                    </div>
                                                    <span class="item-qty">{{ $subcategory->products->count() }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-30px">
                        <span class="alt-font fw-500 fs-19 text-dark-gray d-block mb-10px">Filter by brands</span>
                        <ul class="fs-16 ps-0">
                            @foreach ($brands as $brand)
                                <li class="flex-options">
                                    <div class="form-check ps-0 custom-form-check flex-options">
                                        <input class="checkbox_animated check-it" id="{{ $brand->id }}" name="brands"
                                            @if (in_array($brand->id, explode(',', $q_brands))) checked="checked" @endif
                                            value="{{ $brand->id }}" type="checkbox"
                                            onchange="filterProductsByBrand()" />
                                        <label class="form-check-label">{{ $brand->name }}</label>
                                    </div>
                                    <span class="item-qty">{{ $brand->products->count() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-30px">
                        <span class="alt-font fw-500 fs-19 text-dark-gray d-block mb-10px">Filter by color</span>
                        <ul class="fs-16 ps-0">
                            @foreach ($colors as $color)
                                <li class="flex-options">
                                    <div class="form-check ps-0 custom-form-check flex-options">
                                        <input class="checkbox_animated check-it custom-checkbox" id="{{ $color->id }}"
                                            name="colors" @if (in_array($color->id, explode(',', $q_colors))) checked="checked" @endif
                                            value="{{ $color->id }}" type="checkbox"
                                            style="background-color: {{ $color->code }}; border: none;"
                                            onchange="filterProductsByColor()" />
                                        <label class="form-check-label">{{ $color->name }}</label>
                                    </div>
                                    <span class="item-qty">{{ $color->products_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-30px">
                        <span class="alt-font fw-500 fs-19 text-dark-gray d-block mb-10px">Filter by size</span>
                        <ul class="fs-16 ps-0">
                            @foreach ($sizes as $size)
                                <li class="flex-options">
                                    <div class="form-check ps-0 custom-form-check flex-options">
                                        <input class="checkbox_animated check-it" id="{{ $size->id }}"
                                            name="sizes" @if (in_array($size->id, explode(',', $q_sizes))) checked="checked" @endif
                                            value="{{ $size->id }}" type="checkbox"
                                            onchange="filterProductsBySize()" />
                                        <label class="form-check-label">{{ $size->name }}</label>
                                    </div>
                                    <span class="item-qty">{{ $size->products_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-30px">
                        <div class="d-flex align-items-center mb-20px">
                            <span class="alt-font fw-500 fs-19 text-dark-gray">New arrivals</span>
                            <div class="d-flex ms-auto">
                                <div
                                    class="slider-one-slide-prev-1 icon-very-small swiper-button-prev slider-navigation-style-08 me-5px">
                                    <i class="fa-solid fa-arrow-left text-dark-gray"></i>
                                </div>
                                <div
                                    class="slider-one-slide-next-1 icon-very-small swiper-button-next slider-navigation-style-08 ms-5px">
                                    <i class="fa-solid fa-arrow-right text-dark-gray"></i>
                                </div>
                            </div>
                        </div>
                        <div class="swiper slider-one-slide"
                            data-slider-options='{ "slidesPerView": 1, "loop": true, "autoplay": { "delay": 5000, "disableOnInteraction": false }, "navigation": { "nextEl": ".slider-one-slide-next-1", "prevEl": ".slider-one-slide-prev-1" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="shop-filter new-arribals">
                                        <div class="d-flex align-items-center mb-20px">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-01.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Textured
                                                    sweater</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$30.00</del>$23.00
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-20px">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-02.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Traveller
                                                    shirt</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$50.00</del>$43.00
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-03.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Crewneck
                                                    tshirt</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$20.00</del>$15.00
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="shop-filter new-arribals">
                                        <div class="d-flex align-items-center mb-20px">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-04.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Skinny
                                                    trousers</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$15.00</del>$10.00
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-20px">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-05.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Sleeve
                                                    sweater</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$35.00</del>$30.00
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <figure class="mb-0">
                                                <a href="single-product.html">
                                                    <img class="border-radius-4px w-80px"
                                                        src="{{ asset('frontend/images/demo-fashion-store-product-06.jpg') }}"
                                                        alt />
                                                </a>
                                            </figure>
                                            <div class="col ps-25px">
                                                <a href="single-product.html"
                                                    class="text-dark-gray alt-font fw-500 d-inline-block lh-normal">Pocket
                                                    white</a>
                                                <div class="fs-15 lh-normal">
                                                    <del class="me-5px">$20.00</del>$15.00
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="alt-font fw-500 fs-19 text-dark-gray d-block mb-10px">Filter by tags</span>
                        <div class="shop-filter tag-cloud fs-16">
                            <a href="#">Coats</a>
                            <a href="#">Cotton</a>
                            <a href="#">Dresses</a>
                            <a href="#">Jackets</a>
                            <a href="#">Polyester</a>
                            <a href="#">Printed</a>
                            <a href="#">Shirts</a>
                            <a href="#">Shorts</a>
                            <a href="#">Tops</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 0">
                    <button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add_cart_form">
                    @csrf

                    <input type="hidden" name="product_id" id="product_id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-7 pe-30px md-pe-15px md-mb-40px">
                                <div class="row overflow-hidden position-relative">
                                    <div
                                        class="col-12 col-lg-10 position-relative order-lg-2 product-image ps-30px md-ps-15px">
                                        <div class="swiper product-image-slider"
                                            data-slider-options="{ &quot;spaceBetween&quot;: 10, &quot;loop&quot;: true, &quot;autoplay&quot;: { &quot;delay&quot;: 2000, &quot;disableOnInteraction&quot;: false }, &quot;watchOverflow&quot;: true, &quot;navigation&quot;: { &quot;nextEl&quot;: &quot;.slider-product-next&quot;, &quot;prevEl&quot;: &quot;.slider-product-prev&quot; }, &quot;thumbs&quot;: { &quot;swiper&quot;: { &quot;el&quot;: &quot;.product-image-thumb&quot;, &quot;slidesPerView&quot;: &quot;auto&quot;, &quot;spaceBetween&quot;: 15, &quot;direction&quot;: &quot;vertical&quot;, &quot;navigation&quot;: { &quot;nextEl&quot;: &quot;.swiper-thumb-next&quot;, &quot;prevEl&quot;: &quot;.swiper-thumb-prev&quot; } } } }"
                                            data-thumb-slider-md-direction="horizontal">
                                            <div class="swiper-wrapper"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-2 order-lg-1 position-relative single-product-thumb">
                                        <div class="swiper-container product-image-thumb slider-vertical">
                                            <div class="swiper-wrapper"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-5 product-info">
                                <h4 class="alt-font fs-18 text-dark-gray fw-200 mb-1" id="productName">Relaxed corduroy
                                    shirt
                                </h4>
                                <div class="fs-14"><span class="text-dark-gray fw-500">SKU: </span>M492300</div>
                                <div class="d-block d-sm-flex align-items-center mb-5px">
                                    <div class="me-10px xs-me-0">
                                        <a href="#tab" class="section-link ls-minus-1px icon-small">
                                            <i class="bi bi-star-fill text-golden-yellow"></i>
                                            <i class="bi bi-star-fill text-golden-yellow"></i>
                                            <i class="bi bi-star-fill text-golden-yellow"></i>
                                            <i class="bi bi-star-fill text-golden-yellow"></i>
                                            <i class="bi bi-star-fill text-golden-yellow"></i>
                                        </a>
                                    </div>
                                    <a href="#tab" class="me-25px text-dark-gray fw-500 section-link xs-me-0">(165
                                        Reviews)</a>

                                </div>
                                <div class="product-price mb-10px">
                                    <span class="text-dark-gray fs-28 xs-fs-24 fw-700 ls-minus-1px"
                                        id="renderPrice"></span>
                                </div>

                                <div class="d-flex align-items-center mb-20px">
                                    <label class="text-dark-gray alt-font me-15px fw-500">Color</label>
                                    <ul class="shop-color mb-0" id="renderColor"></ul>
                                </div>

                                <div class="d-flex align-items-center mb-20px">
                                    <label class="text-dark-gray me-15px fw-500">Size</label>
                                    <ul class="shop-size mb-0" id="renderSize"></ul>
                                </div>

                                <div class="d-flex align-items-center flex-column flex-sm-row mb-20px position-relative">
                                    <button type="submit"
                                        class="btn btn-cart btn-extra-large btn-switch-text btn-box-shadow btn-none-transform btn-dark-gray left-icon btn-round-edge border-0 me-15px xs-me-0 order-3 order-sm-2">
                                        <span>
                                            <span><i class="feather icon-feather-shopping-bag"></i></span>
                                            <span class="btn-double-text ls-0px" data-text="Add to cart">Add to
                                                cart</span>
                                        </span>
                                    </button>
                                    <a href="#"
                                        class="wishlist d-flex align-items-center justify-content-center border border-radius-5px border-color-extra-medium-gray order-2 order-sm-3">
                                        <i class="feather icon-feather-heart icon-small text-dark-gray"></i>
                                    </a>
                                </div>

                                <div id="render-links"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="frmFilter" method="GET" action="{{ route('shop') }}" onsubmit="removeEmptyParams(event)">
        <input type="hidden" id="page" name="page" value="{{ $page }}" />
        <input type="hidden" id="psize" name="psize" value="{{ $psize }}" />
        <input type="hidden" id="order" name="order" value="{{ $order }}" />
        <input type="hidden" id="brands" name="brands" value="{{ $q_brands }}" />
        <input type="hidden" id="subcategories" name="subcategories" value="{{ $q_subcategories }}" />
        <input type="hidden" id="colors" name="colors" value="{{ $q_colors }}" />
        <input type="hidden" id="sizes" name="sizes" value="{{ $q_sizes }}" />
        <input type="hidden" id="prange" name="prange" value="" />
        <input type="hidden" id="searchText" name="searchText" value="" />
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle collapsible
            $(".collapsible").on("click", function() {
                $(this).toggleClass("active");
                $(this).next(".content").slideToggle();

                var icon = $(this).find("i");
                if ($(this).hasClass("active")) {
                    icon.removeClass("fa-plus").addClass("fa-minus");
                } else {
                    icon.removeClass("fa-minus").addClass("fa-plus");
                }
            });

            $('#pagesize').on("change", function() {
                $('#psize').val($('#pagesize option:selected').val());
                $('#frmFilter').submit();
            });

            $('#orderby').on("change", function() {
                $('#order').val($('#orderby option:selected').val());
                $('#frmFilter').submit();
            });

            // Pre-open active categories
            var activeCategoryIds = @json($activeCategoryIds);
            activeCategoryIds.forEach(function(categoryId) {
                var button = $('button[data-category-id="' + categoryId + '"]');
                button.addClass("active");
                button.next(".content").show();

                var icon = button.find("i");
                icon.removeClass("fa-plus").addClass("fa-minus");
            });

            // Nhận dữ liệu được kết xuất trên biểu mẫu
            $(document).on("click", ".quickViewBtn", function(e) {
                e.preventDefault();
                const product_id = $(this).attr('id');

                $.ajax({
                    url: "{{ route('quick.to.view') }}",
                    method: "GET",
                    data: {
                        id: product_id,
                    },
                    success: function(data) {
                        let mainSliderContent = '';
                        let thumbSliderContent = '';
                        let colorOptions = '';
                        let sizeOptions = '';

                        const {
                            id,
                            name,
                            slug,
                            price,
                            discount,
                            colors,
                            sizes
                        } = data.product;

                        $('#product_id').val(id);

                        data.galleries.forEach(gallery => {
                            const imageUrl =
                                `{{ asset('uploads/gallery/') }}/${gallery.image}`;

                            const mainSlide = `
                                <div class="swiper-slide gallery-box">
                                    <a href="${imageUrl}" data-group="lightbox-gallery" title="Relaxed corduroy shirt">
                                        <img class="w-100" src="${imageUrl}" alt="${gallery.name}">
                                    </a>
                                </div>
                            `;

                            mainSliderContent += mainSlide;

                            const thumbSlide = `
                                <div class="swiper-slide">
                                    <img class="w-100" src="${imageUrl}" alt="${gallery.name}">
                                </div>
                            `;

                            thumbSliderContent += thumbSlide;
                        });

                        // Inject the gallery content into the swiper-wrapper elements
                        $("#quickViewModal .product-image-slider .swiper-wrapper").html(
                            mainSliderContent);
                        $("#quickViewModal .product-image-thumb .swiper-wrapper").html(
                            thumbSliderContent);

                        // Render Product Name
                        const shortName = shortText(name, 33);
                        $('#productName').text(shortName);

                        // Calculate and inject price
                        let priceHtml = '';

                        if (discount > 0) {
                            const discountAmount = (price * discount) / 100;
                            const discountedPrice = price - discountAmount;

                            priceHtml = `
                                ${numberWithCommas(discountedPrice)}₫
                                <del class="text-medium-gray me-5px fw-400 fs-16" style="vertical-align: super;">${numberWithCommas(price)}₫</del>
                                <span class="quick-discount">-${discount}%</span>
                            `;
                        } else {
                            priceHtml = `${numberWithCommas(price)} ₫`;
                        }

                        $("#quickViewModal #renderPrice").html(priceHtml);

                        // Generate color options
                        colorOptions = colors.map(color => `
                            <li>
                                <input class="d-none" type="radio" id="color-${color.id}" name="color" value="${color.id}">
                                <label for="color-${color.id}">
                                    <span style="background-color: ${color.code}"></span>
                                </label>
                            </li>
                        `).join('');

                        $("#quickViewModal #renderColor").html(colorOptions);

                        // Generate size options
                        sizeOptions = sizes.map(size => `
                            <li>
                                <input class="d-none" type="radio" id="size-${size.id}" name="size" value="${size.id}">
                                <label for="size-${size.id}">
                                    <span>${size.name}</span>
                                </label>
                            </li>
                        `).join('');

                        $("#quickViewModal #renderSize").html(sizeOptions);

                        // Render links
                        const productDetailsUrl = `{{ url('/product-detail/') }}/${slug}`;
                        $('#render-links').html(`
                            <a href="${productDetailsUrl}">Xem chi tiết đầy đủ <i class="fa-solid fa-arrow-right"></i></a>
                        `);

                        // Reinitialize Swiper
                        var thumbSwiper = new Swiper('.product-image-thumb', {
                            spaceBetween: 15,
                            slidesPerView: 'auto',
                            direction: 'vertical',
                            navigation: {
                                nextEl: '.swiper-thumb-next',
                                prevEl: '.swiper-thumb-prev',
                            },
                        });

                        var mainSwiper = new Swiper('.product-image-slider', {
                            spaceBetween: 10,
                            loop: true,
                            autoplay: {
                                delay: 2000,
                                disableOnInteraction: false,
                            },
                            watchOverflow: true,
                            navigation: {
                                nextEl: '.slider-product-next',
                                prevEl: '.slider-product-prev',
                            },
                            thumbs: {
                                swiper: thumbSwiper,
                            },
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            // Xử lý biểu mẫu
            $(document).on("submit", "#add_cart_form", function(e) {
                e.preventDefault();

                // Get selected color and size values
                const selectedColor = $("input[name='color']:checked").val();
                const selectedSize = $("input[name='size']:checked").val();

                const qty = 1;

                // Validate color and size selection
                if (!selectedColor) {
                    toastr.error("Vui lòng chọn màu sắc.");
                    return;
                }

                if (!selectedSize) {
                    toastr.error("Vui lòng chọn kích thước.");
                    return;
                }

                const formData = new FormData(this);

                // Append color and size values to formData
                formData.append('color', selectedColor);
                formData.append('size', selectedSize);
                formData.append('quantity', qty);

                $.ajax({
                    url: "{{ route('add.to.cart') }}",
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#quickViewModal").modal("hide");
                        loadCartCount();
                        loadCartDropdown();
                    } else {
                        toastr.error(res.message);
                        $("#quickViewModal").modal("hide");
                    }
                });
            });
        });

        function filterProductsBySubCategory() {
            var selectedSubCategories = [];

            $('input[name="subcategories"]:checked').each(function() {
                selectedSubCategories.push(this.value);
            });

            // Nối mảng thành một chuỗi được phân tách bằng dấu phẩy
            var subcategories = selectedSubCategories.join(',');

            // Cập nhật đầu vào ẩn với ID thương hiệu đã chọn
            $('#subcategories').val(subcategories);

            // Gửi biểu mẫu
            $('#frmFilter').submit();
        }

        function filterProductsByBrand() {
            var selectedBrands = [];

            $('input[name="brands"]:checked').each(function() {
                selectedBrands.push(this.value);
            });

            // Nối mảng thành một chuỗi được phân tách bằng dấu phẩy
            var brands = selectedBrands.join(',');

            // Cập nhật đầu vào ẩn với ID thương hiệu đã chọn
            $('#brands').val(brands);

            // Gửi biểu mẫu
            $('#frmFilter').submit();
        }

        function filterProductsByColor() {
            var selectedColors = [];

            $('input[name="colors"]:checked').each(function() {
                selectedColors.push(this.value);
            });

            // Nối mảng thành một chuỗi được phân tách bằng dấu phẩy
            var colors = selectedColors.join(',');

            // Cập nhật đầu vào ẩn với ID thương hiệu đã chọn
            $('#colors').val(colors);

            // Gửi biểu mẫu
            $('#frmFilter').submit();
        }

        function filterProductsBySize() {
            var selectedSizes = [];

            $('input[name="sizes"]:checked').each(function() {
                selectedSizes.push(this.value);
            });

            // Nối mảng thành một chuỗi được phân tách bằng dấu phẩy
            var sizes = selectedSizes.join(',');

            // Cập nhật đầu vào ẩn với ID thương hiệu đã chọn
            $('#sizes').val(sizes);

            // Gửi biểu mẫu
            $('#frmFilter').submit();
        }

        function removeEmptyParams(event) {
            event.preventDefault(); // Prevent the form from submitting the default way

            const form = document.getElementById('frmFilter');
            const url = new URL(form.action, window.location.origin);

            // Clear existing search parameters
            url.search = '';

            // Iterate over form elements and add non-empty values to URL
            for (const element of form.elements) {
                if (element.name && element.value) {
                    url.searchParams.append(element.name, element.value);
                }
            }

            // Redirect to the constructed URL
            window.location.href = url.toString();
        }

        function shortText(text, maxLength) {
            if (text.length > maxLength) {
                return text.substring(0, maxLength) + '...';
            }
            return text;
        }

        // Utility function to format numbers with commas
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
@endpush
