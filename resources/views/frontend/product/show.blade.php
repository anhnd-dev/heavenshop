@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/product/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/collection/style.css') }}">
@endpush

@section('content')

    <section class="product-detail-section">

        <div class="container" style="max-width:1245px;margin-top:45px;">

            {{-- =========================
                BREADCRUMB
            ========================= --}}
            <ul class="product-breadcrumb">

                <li>
                    <a href="{{ route('home') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                @foreach ($product->category->breadcrumb as $category)
                    <li>
                        <a href="{{ route('collection.show', $category->slug) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach

            </ul>

            <div class="product-detail-wrapper">

                {{-- =========================
                    LEFT
                ========================= --}}
                <div class="product-left">

                    <div class="product-gallery-wrapper">

                        <div class="product-gallery">

                            {{-- THUMB --}}
                            <div class="product-gallery-thumb" id="galleryThumbs"></div>

                            {{-- LOADING --}}
                            <div class="gallery-loading d-none" id="galleryLoading">

                                <div class="gallery-loading-thumb"></div>

                                <div class="gallery-loading-main"></div>

                            </div>

                            {{-- MAIN --}}
                            <div class="product-main-image">

                                <img id="mainProductImage" src="" alt="{{ $product->name }}">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- =========================
                    RIGHT
                ========================= --}}
                <div class="product-right">

                    <div class="product-info">

                        {{-- TITLE --}}
                        <h1 class="product-title">
                            {{ $product->name }}
                        </h1>

                        {{-- RATING --}}
                        <div class="product-rating">

                            <div class="product-stars">
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                            </div>

                            <span>(5)</span>

                        </div>

                        {{-- PRICE --}}
                        <div class="product-price-wrap">

                            @if ($oldPrice)
                                <div class="product-old-price">

                                    {{ number_format($oldPrice, 0, ',', '.') }}đ

                                </div>
                            @endif

                            <div class="product-price-row">

                                <div class="product-price">

                                    {{ number_format($minPrice, 0, ',', '.') }}đ

                                </div>

                                @if ($discountPercent > 0)
                                    <div class="product-discount">

                                        -{{ $discountPercent }}%

                                    </div>
                                @endif

                            </div>

                        </div>

                        {{-- VOUCHER --}}
                        <div class="voucher-wrapper">

                            <div class="title">
                                Mã giảm giá
                            </div>

                            <div class="items">

                                <div class="voucher-item">
                                    Giảm 150K
                                </div>

                                <div class="voucher-item">
                                    Giảm 10%
                                </div>

                                <div class="voucher-item">
                                    Giảm 12%
                                </div>

                            </div>

                        </div>

                        {{-- COLOR --}}
                        <div class="product-option-block">

                            <div class="product-option-title">

                                Màu sắc:

                                <span class="selected-option" id="selectedColorText">
                                    --
                                </span>

                            </div>

                            <ul class="shop-color">

                                @foreach ($colors as $color)
                                    <li>

                                        <span class="color-item" data-id="{{ $color->id }}"
                                            data-name="{{ $color->name }}" style="background-color: {{ $color->code }}">
                                        </span>

                                    </li>
                                @endforeach

                            </ul>

                        </div>

                        {{-- SIZE --}}
                        <div class="product-option-block">

                            <div class="size-head">

                                <div class="product-option-title">

                                    Kích thước:

                                    <span class="selected-option" id="selectedSizeText">
                                        --
                                    </span>

                                </div>

                            </div>

                            <ul class="shop-size">

                                @foreach ($sizes as $size)
                                    <li>

                                        <span class="size-item" data-id="{{ $size->id }}"
                                            data-name="{{ $size->name }}">

                                            {{ $size->name }}

                                        </span>

                                    </li>
                                @endforeach

                            </ul>

                        </div>

                        {{-- ACTION --}}
                        <div class="product-action">

                            <div class="product-qty">

                                <button type="button" class="qty-minus">
                                    -
                                </button>

                                <input type="text" id="quantity-input" value="1">

                                <button type="button" class="qty-plus">
                                    +
                                </button>

                            </div>

                            <button type="button" class="btn-add-cart" onclick="addToCart()">

                                🛒 Thêm vào giỏ

                            </button>

                        </div>

                        {{-- STOCK --}}
                        <div class="stock-box">

                            Kho:

                            <strong id="stockText">
                                --
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================
                PRODUCT TABS
            ========================= --}}
            <div class="product-tabs-wrapper">

                {{-- TAB HEAD --}}
                <div class="product-tabs-nav">

                    <button class="product-tab-btn active" data-tab="description">
                        Description
                    </button>

                    <button class="product-tab-btn" data-tab="reviews">
                        Reviews (3)
                    </button>

                </div>

                {{-- DESCRIPTION --}}
                <div class="product-tab-content active" id="description">

                    <div class="description-wrapper">

                        <div class="description-content-collapse" id="descriptionContent">

                            {!! $product->description !!}

                        </div>

                        <div class="description-fade"></div>

                        <button class="description-toggle-btn" id="descriptionToggleBtn">

                            Xem thêm

                        </button>

                    </div>

                </div>

                {{-- REVIEWS --}}
                <div class="product-tab-content" id="reviews">

                    <div class="reviews-wrapper">

                        <div class="reviews-list">

                            <div class="reviews-count">
                                3 reviews for
                                <strong>{{ $product->name }}</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================
                RELATED PRODUCTS
            ========================= --}}
            @if ($relatedProducts->count())
                <div class="related-products-wrapper">

                    <div class="related-products-head">

                        <h2 class="related-products-title">
                            Gợi ý thêm
                        </h2>

                    </div>

                    <div class="related-products-grid">

                        @forelse ($relatedProducts as $relatedProduct)
                            @php

                                $relatedPrice = $relatedProduct->variants_min_price;

                                $relatedSalePrice = $relatedProduct->variants_min_sale_price;

                                $relatedFinalPrice =
                                    $relatedSalePrice && $relatedSalePrice < $relatedPrice
                                        ? $relatedSalePrice
                                        : $relatedPrice;

                                $relatedDiscountPercent = 0;

                                if ($relatedSalePrice && $relatedSalePrice < $relatedPrice) {
                                    $discountPercent = round(
                                        (($relatedPrice - $relatedSalePrice) / $relatedPrice) * 100,
                                    );
                                }

                                $relatedColors = collect($relatedProduct->variants)->groupBy('color_id');

                                $relatedSizes = collect($relatedProduct->variants)->groupBy('size_id');
                            @endphp

                            <li class="grid-item">

                                <div class="shop-box">

                                    {{-- IMAGE --}}
                                    <div class="shop-image">

                                        @if ($relatedDiscountPercent > 0)
                                            <div class="product-badge">

                                                -{{ $relatedDiscountPercent }}%

                                            </div>
                                        @endif

                                        <a href="{{ route('product.show', $relatedProduct->slug) }}">

                                            <img src="{{ asset('uploads/product/' . $relatedProduct->image) }}"
                                                alt="{{ $relatedProduct->name }}">

                                        </a>

                                        {{-- QUICK SIZE --}}
                                        @if ($relatedSizes->count())
                                            <div class="quick-size">

                                                <div class="quick-size-title">

                                                    <span>
                                                        Thêm nhanh vào giỏ hàng
                                                    </span>

                                                    <button type="button" class="add-to-cart"
                                                        data-product="{{ $relatedProduct->id }}">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>

                                                </div>

                                                <div class="quick-size-grid">

                                                    @foreach ($relatedSizes as $sizeId => $variants)
                                                        @php
                                                            $relatedVariant = $variants->first();

                                                            $hasStock = $variants->sum('stock') > 0;
                                                        @endphp

                                                        <button type="button"
                                                            class="size-item select-size {{ !$hasStock ? 'disabled' : '' }}"
                                                            data-product="{{ $relatedProduct->id }}"
                                                            data-size="{{ $sizeId }}"
                                                            {{ !$hasStock ? 'disabled' : '' }}>

                                                            {{ $relatedVariant->size->name ?? '' }}

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
                                    @if ($relatedColors->count())
                                        <div class="product-colors">

                                            @foreach ($relatedColors->take(5) as $colorId => $variants)
                                                @php
                                                    $relatedVariant = $variants->first();

                                                    $hasStock = $variants->sum('stock') > 0;
                                                @endphp

                                                <button type="button"
                                                    class="color-dot select-color {{ !$hasStock ? 'disabled' : '' }}"
                                                    data-product="{{ $relatedProduct->id }}"
                                                    data-color="{{ $colorId }}"
                                                    style="background: {{ $relatedVariant->color->code ?? '#ddd' }}"
                                                    {{ !$hasStock ? 'disabled' : '' }}>
                                                </button>
                                            @endforeach

                                            @if ($relatedColors->count() > 5)
                                                <span class="more-color">

                                                    +{{ $relatedColors->count() - 5 }}

                                                </span>
                                            @endif

                                        </div>
                                    @endif

                                    {{-- INFO --}}
                                    <div class="shop-footer">

                                        <a href="{{ route('product.show', $relatedProduct->slug) }}"
                                            class="product-name">

                                            {{ shortenText($relatedProduct->name, 45) }}

                                        </a>

                                        <div class="wrap-price">

                                            @if ($relatedSalePrice && $relatedSalePrice < $relatedPrice)
                                                <span class="sale-price">

                                                    {{ number_format($relatedSalePrice, 0, ',', '.') }}đ

                                                </span>

                                                <span class="origin-price">

                                                    {{ number_format($relatedPrice, 0, ',', '.') }}đ

                                                </span>
                                            @else
                                                <span class="sale-price">

                                                    {{ number_format($relatedFinalPrice, 0, ',', '.') }}đ

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
                                        Đang gợi ý thêm sản phẩm
                                    </h5>

                                </div>

                            </li>
                        @endforelse
                    </div>

                </div>
            @endif

        </div>

    </section>

@endsection

@push('scripts')
    <script>
        let variants = @json($variantData);

        let galleries = @json($galleryByColor);

        let selectedColor = null;

        let selectedSize = null;

        let currentStock = 0;

        // =========================
        // RENDER GALLERY
        // =========================
        function renderGallery(colorId) {

            let colorGalleries = galleries[colorId];

            if (!colorGalleries || colorGalleries.length === 0) {

                $('#galleryThumbs').html('');

                $('#mainProductImage').attr('src', '');

                return;
            }

            showGalleryLoading();

            setTimeout(() => {

                let thumbHtml = '';

                colorGalleries.forEach((gallery, index) => {

                    thumbHtml += `
                    <div class="product-thumb-item ${index === 0 ? 'active' : ''}"
                        data-image="${gallery.file}">

                        <img src="${gallery.file}" alt="">
                    </div>
                `;
                });

                $('#galleryThumbs').html(thumbHtml);

                $('#mainProductImage')
                    .attr('src', colorGalleries[0].file);

                hideGalleryLoading();

            }, 300);
        }

        // =========================
        // GALLERY LOADING
        // =========================
        function showGalleryLoading() {

            $('#galleryThumbs').css({
                opacity: 0
            });

            $('.product-main-image img').css({
                opacity: 0
            });

            $('#galleryLoading').removeClass('d-none');
        }

        function hideGalleryLoading() {

            $('#galleryLoading').addClass('d-none');

            $('#galleryThumbs').css({
                opacity: 1
            });

            $('.product-main-image img').css({
                opacity: 1
            });
        }

        // =========================
        // PRODUCT TABS
        // =========================
        $('.product-tab-btn').on('click', function() {

            let tab = $(this).data('tab');

            $('.product-tab-btn').removeClass('active');

            $(this).addClass('active');

            $('.product-tab-content').removeClass('active');

            $('#' + tab).addClass('active');
        });

        // =========================
        // THUMB CLICK
        // =========================
        $(document).on('click', '.product-thumb-item', function() {

            $('.product-thumb-item').removeClass('active');

            $(this).addClass('active');

            $('#mainProductImage').attr(
                'src',
                $(this).data('image')
            );
        });

        // =========================
        // COLOR SELECT
        // =========================
        $(document).on('click', '.color-item', function() {

            $('.color-item').removeClass('active');

            $(this).addClass('active');

            selectedColor = $(this).data('id');

            $('#selectedColorText').text(
                $(this).data('name')
            );

            renderGallery(selectedColor);

            updateAvailableSizes();

            autoSelectFirstAvailableSize();
        });

        // =========================
        // SIZE SELECT
        // =========================
        $(document).on('click', '.size-item', function() {

            if ($(this).hasClass('disabled')) {
                return;
            }

            $('.size-item').removeClass('active');

            $(this).addClass('active');

            selectedSize = $(this).data('id');

            $('#selectedSizeText').text(
                $(this).data('name')
            );

            updateVariant();
        });

        // =====================================
        // SELECT SIZE
        // =====================================

        $(document).on('click', '.select-size:not(.disabled)', function() {

            let parent = $(this).closest('.shop-box');

            parent.find('.select-size')
                .removeClass('active');

            $(this).addClass('active');

        });

        // =====================================
        // SELECT COLOR
        // =====================================

        $(document).on('click', '.select-color:not(.disabled)', function() {

            let parent = $(this).closest('.shop-box');

            parent.find('.select-color')
                .removeClass('active');

            $(this).addClass('active');

        });

        // =========================================
        // ADD TO CART
        // =========================================

        $(document).on('click', '.add-to-cart', function() {

            let parent = $(this).closest('.shop-box');

            let productId = $(this).data('product');

            let sizeId = parent.find('.select-size.active').data('size');

            let colorId = parent.find('.select-color.active').data('color');


            if (!colorId || !sizeId) {

                toastr.warning('Vui lòng chọn màu và kích thước');

                return;
            }

            let button = $(this);

            button.prop('disabled', true);

            $.ajax({

                url: '/cart/add',

                type: 'POST',

                data: {

                    product_id: productId,

                    color_id: colorId,

                    size_id: sizeId,

                    quantity: 1,

                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function(response) {

                    toastr.success(response.message);

                    // reload mini cart
                    loadHeaderCart();

                    // reset active nếu muốn
                    parent.find('.select-size').removeClass('active');
                    parent.find('.select-color').removeClass('active');

                },

                error: function(xhr) {

                    toastr.error(
                        xhr.responseJSON?.message ||
                        'Không thể thêm giỏ hàng'
                    );
                },

                complete: function() {

                    button.prop('disabled', false);
                }
            });
        });

        // =========================
        // UPDATE AVAILABLE SIZE
        // =========================
        function updateAvailableSizes() {

            selectedSize = null;

            $('#selectedSizeText').text('--');

            $('.size-item').each(function() {

                let sizeId = $(this).data('id');

                let exists = variants.find(v =>

                    v.color_id == selectedColor &&
                    v.size_id == sizeId &&
                    v.stock > 0
                );

                if (exists) {

                    $(this).removeClass('disabled');

                } else {

                    $(this)
                        .removeClass('active')
                        .addClass('disabled');
                }
            });
        }

        // =========================
        // AUTO SELECT FIRST SIZE
        // =========================
        function autoSelectFirstAvailableSize() {

            let firstAvailable = $('.size-item:not(.disabled)').first();

            if (firstAvailable.length) {

                firstAvailable.trigger('click');
            }
        }

        // =========================
        // FIND VARIANT
        // =========================
        function findVariant() {

            return variants.find(v =>

                v.color_id == selectedColor &&
                v.size_id == selectedSize
            );
        }

        // =========================
        // UPDATE VARIANT
        // =========================
        function updateVariant() {

            let variant = findVariant();

            if (!variant) {

                currentStock = 0;

                $('#stockText').text('Hết hàng');

                $('.btn-add-cart').prop('disabled', true);

                return;
            }

            currentStock = parseInt(
                variant.stock
            );

            $('#stockText').text(
                `${currentStock} sản phẩm`
            );

            $('.product-price').text(
                Number(variant.price)
                .toLocaleString('vi-VN') + 'đ'
            );

            if (variant.sale_price) {

                $('.product-old-price').text(
                    Number(variant.sale_price)
                    .toLocaleString('vi-VN') + 'đ'
                );

            } else {

                $('.product-old-price').text('');
            }

            if (currentStock <= 0) {

                $('#stockText').text('Hết hàng');

                $('.btn-add-cart').prop('disabled', true);

            } else {

                $('.btn-add-cart').prop('disabled', false);
            }
        }

        // =========================
        // QTY PLUS
        // =========================
        $('.qty-plus').on('click', function() {

            let qty = parseInt(
                $('#quantity-input').val()
            ) || 1;

            if (qty >= currentStock) {

                toastr.warning(
                    `Chỉ còn ${currentStock} sản phẩm`
                );

                return;
            }

            $('#quantity-input').val(qty + 1);
        });

        // =========================
        // QTY MINUS
        // =========================
        $('.qty-minus').on('click', function() {

            let qty = parseInt(
                $('#quantity-input').val()
            ) || 1;

            if (qty > 1) {

                $('#quantity-input').val(qty - 1);
            }
        });

        // =========================
        // QTY INPUT
        // =========================
        $('#quantity-input').on('input', function() {

            let qty = parseInt(
                $(this).val()
            ) || 1;

            if (qty < 1) {

                qty = 1;
            }

            if (
                currentStock > 0 &&
                qty > currentStock
            ) {

                qty = currentStock;

                toastr.warning(
                    `Tối đa ${currentStock} sản phẩm`
                );
            }

            $(this).val(qty);
        });

        // =========================
        // AUTO SELECT FIRST COLOR
        // =========================
        $(document).ready(function() {

            setTimeout(() => {

                $('.color-item')
                    .first()
                    .trigger('click');

            }, 100);
        });

        // =========================
        // ADD TO CART
        // =========================
        function addToCart() {

            let qty = parseInt(
                $('#quantity-input').val()
            );

            let variant = findVariant();

            if (!variant) {

                toastr.error(
                    'Vui lòng chọn phân loại'
                );

                return;
            }

            $.ajax({

                url: "{{ route('cart.add') }}",

                method: "POST",

                data: {

                    _token: "{{ csrf_token() }}",

                    variant_id: variant.id,

                    quantity: qty
                },

                success: function(res) {

                    if (res.status === 200) {

                        toastr.success(res.message);

                        $('.cart-count').text(
                            res.cart_count
                        );

                        // reload mini cart dropdown
                        loadHeaderCart();

                    } else {

                        toastr.error(res.message);
                    }
                },

                error: function(xhr) {

                    if (xhr.responseJSON?.message) {

                        toastr.error(
                            xhr.responseJSON.message
                        );

                    } else {

                        toastr.error(
                            'Không thể thêm giỏ hàng'
                        );
                    }
                }
            });
        }

        // =========================
        // DESCRIPTION COLLAPSE
        // =========================
        const descriptionWrapper = $('.description-wrapper');

        const descriptionContent = $('#descriptionContent');

        const descriptionButton = $('#descriptionToggleBtn');

        const collapsedHeight = 700;

        if (
            descriptionContent[0].scrollHeight <= collapsedHeight
        ) {

            descriptionWrapper.addClass('no-collapse');
        }

        descriptionButton.on('click', function() {

            descriptionContent.toggleClass('expanded');

            descriptionWrapper.toggleClass('expanded');

            if (
                descriptionContent.hasClass('expanded')
            ) {

                descriptionButton.text('Thu gọn');

            } else {

                descriptionButton.text('Xem thêm');
            }
        });
    </script>
@endpush
