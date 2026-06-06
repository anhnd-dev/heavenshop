@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/collection/style.css') }}">
@endpush

@section('content')

    {{-- PAGE TITLE --}}
    <section class="top-space-margin half-section bg-gradient-very-light-gray">

        <div class="container">

            <div class="row align-items-center justify-content-center">

                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">

                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">
                        {{ $category->name }}
                    </h1>

                </div>

                {{-- BREADCRUMB --}}
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">

                    <ul>

                        <li>
                            <a href="{{ route('home') }}">
                                Home
                            </a>
                        </li>

                        @php
                            $segments = explode('/', $category->slug);
                            $path = '';
                        @endphp

                        @foreach ($segments as $segment)
                            @php
                                $path .= ($path ? '/' : '') . $segment;

                                $crumbCategory = \App\Models\Category::query()
                                    ->select('name', 'slug')
                                    ->where('slug', $path)
                                    ->first();
                            @endphp

                            @if ($crumbCategory)
                                <li>
                                    <a href="{{ route('collection.show', $crumbCategory->slug) }}">
                                        {{ $crumbCategory->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    </section>

    {{-- COLLECTION --}}
    <section class="pt-0 ps-4 pe-4 lg-ps-2 lg-pe-2 sm-ps-0 sm-pe-0">

        <div class="container-fluid">

            <div class="row flex-row-reverse">

                {{-- PRODUCTS --}}
                <div class="col-xxl-9 col-lg-9 ps-5 md-ps-15px md-mb-60px">

                    <div id="filter-tags-container">

                        @include('frontend.collection.partials.active-filters')

                    </div>

                    {{-- PRODUCT CONTAINER --}}
                    <div id="product-container">

                        @include('frontend.collection.partials.products', [
                            'free_ship' => $shippingFreeThreshold,
                        ])

                    </div>

                </div>

                {{-- SIDEBAR --}}
                <div class="col-xxl-3 col-lg-3 shop-sidebar">

                    <div class="filter-sidebar">

                        {{-- HEADER --}}
                        <div class="filter-header">

                            <h5>
                                Bộ lọc
                            </h5>

                            <span class="filter-result"></span>

                        </div>

                        {{-- CATEGORIES --}}
                        @if ($relatedCategories->count())
                            <div class="filter-group active">

                                <div class="filter-title">

                                    <span>Danh mục</span>

                                    <i class="fa-solid fa-angle-down"></i>

                                </div>

                                <div class="filter-content">

                                    <ul class="filter-list">

                                        @foreach ($relatedCategories as $related)
                                            <li class="filter-item">

                                                <a href="{{ route('collection.show', $related->slug) }}"
                                                    class="filter-link {{ request()->is('collections/' . $related->slug) ? 'active' : '' }}">

                                                    <div class="filter-left">

                                                        <span class="fake-radio"></span>

                                                        <span class="filter-name">
                                                            {{ $related->name }}
                                                        </span>

                                                    </div>

                                                    <span class="filter-count">
                                                        {{ $related->products_count }}
                                                    </span>

                                                </a>

                                            </li>
                                        @endforeach

                                    </ul>

                                </div>

                            </div>
                        @endif

                        {{-- BRANDS --}}
                        <div class="filter-group {{ $q_brands ? 'active' : '' }}">

                            <div class="filter-title">

                                <span>Thương hiệu</span>

                                <i class="fa-solid fa-angle-down"></i>

                            </div>

                            <div class="filter-content">

                                <ul class="filter-list">

                                    @foreach ($brands as $brand)
                                        <li class="filter-item">

                                            <label class="filter-checkbox">

                                                <div class="filter-left">

                                                    <input type="checkbox" name="brands" value="{{ $brand->id }}"
                                                        class="custom-checkbox filter-trigger" @checked(in_array($brand->id, explode(',', $q_brands)))>

                                                    <span class="filter-name">
                                                        {{ $brand->name }}
                                                    </span>

                                                </div>

                                                <span class="filter-count">
                                                    {{ $brand->products_count }}
                                                </span>

                                            </label>

                                        </li>
                                    @endforeach

                                </ul>

                            </div>

                        </div>

                        {{-- SIZES --}}
                        <div class="filter-group {{ $q_sizes ? 'active' : '' }}">

                            <div class="filter-title">

                                <span>Kích thước</span>

                                <i class="fa-solid fa-angle-down"></i>

                            </div>

                            <div class="filter-content">

                                <div class="size-filter-grid">

                                    @foreach ($sizes as $size)
                                        <label class="size-filter-item">

                                            <input type="checkbox" name="sizes" value="{{ $size->id }}"
                                                class="filter-trigger" @checked(in_array($size->id, explode(',', $q_sizes)))>

                                            <span>
                                                {{ $size->name }}
                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                        {{-- COLORS --}}
                        <div class="filter-group {{ $q_colors ? 'active' : '' }}">

                            <div class="filter-title">

                                <span>Màu sắc</span>

                                <i class="fa-solid fa-angle-down"></i>

                            </div>

                            <div class="filter-content">

                                <div class="color-filter-grid">

                                    @foreach ($colors as $color)
                                        <label class="color-filter-item">

                                            <input type="checkbox" name="colors" value="{{ $color->id }}"
                                                class="filter-trigger" @checked(in_array($color->id, explode(',', $q_colors)))>

                                            <span class="color-swatch" style="background: {{ $color->code }}"></span>

                                            <small>
                                                {{ $color->name }}
                                            </small>

                                        </label>
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@push('scripts')
    <script>
        // =====================================================
        // FILTER ACCORDION
        // =====================================================

        $(document).on('click', '.filter-title', function() {

            $(this).parent().toggleClass('active');

        });

        // =====================================================
        // BUILD PARAMS
        // =====================================================

        function getCheckedValues(name) {

            let values = [];

            $('input[name="' + name + '"]:checked').each(function() {

                values.push($(this).val());

            });

            return values.join(',');

        }

        function buildUrl(page = 1) {

            let baseUrl = window.location.pathname;

            let params = new URLSearchParams();

            // =========================================
            // DEFAULT VALUES
            // =========================================

            let psize = $('#pagesize').val();

            let order = $('#orderby').val();

            let brands = getCheckedValues('brands');

            let colors = getCheckedValues('colors');

            let sizes = getCheckedValues('sizes');

            // =========================================
            // ONLY APPEND WHEN NEEDED
            // =========================================

            // page size
            if (psize !== '12') {

                params.append('psize', psize);

            }

            // order
            if (order !== '1') {

                params.append('order', order);

            }

            // brands
            if (brands.length) {

                params.append('brands', brands);

            }

            // colors
            if (colors.length) {

                params.append('colors', colors);

            }

            // sizes
            if (sizes.length) {

                params.append('sizes', sizes);

            }

            // pagination
            if (page > 1) {

                params.append('page', page);

            }

            // =========================================
            // FINAL URL
            // =========================================

            let queryString = params.toString();

            return queryString ?
                `${baseUrl}?${queryString}` :
                baseUrl;

        }

        // =====================================================
        // LOAD PRODUCTS
        // =====================================================

        function loadProducts(url) {

            $('#product-container')
                .addClass('collection-loading');

            $.ajax({

                url: url,

                type: 'GET',

                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },

                success: function(response) {

                    // delay nhẹ để loading mượt hơn
                    setTimeout(function() {

                        $('#product-container')
                            .html(response.products);

                        $('#filter-tags-container')
                            .html(response.filters);

                        $('.filter-result')
                            .text(response.total + ' kết quả');

                        $('#product-container')
                            .removeClass('collection-loading');

                        window.history.pushState({}, '', url);

                        initCollectionGrid();

                    }, 350);

                },

                error: function() {

                    window.location.href = url;

                }

            });

        }
        // =====================================================
        // FILTER CHANGE
        // =====================================================

        $(document).on('change', '.filter-trigger', function() {

            let url = buildUrl(1);

            loadProducts(url);

        });

        // =====================================================
        // PAGE SIZE
        // =====================================================

        $(document).on('change', '#pagesize', function() {

            let url = buildUrl(1);

            loadProducts(url);

        });

        // =====================================================
        // ORDER
        // =====================================================

        $(document).on('change', '#orderby', function() {

            let url = buildUrl(1);

            loadProducts(url);

        });

        // =====================================================
        // PAGINATION
        // =====================================================

        $(document).on('click', '.pagination a', function(e) {

            e.preventDefault();

            let pageUrl = new URL($(this).attr('href'));

            let page = parseInt(pageUrl.searchParams.get('page') || 1);

            let url = buildUrl(page);

            loadProducts(url);

        });
        // =====================================================
        // BROWSER BACK/FORWARD
        // =====================================================

        window.onpopstate = function() {

            location.reload();

        };

        // =====================================================
        // ISOTOPE
        // =====================================================

        function initCollectionGrid() {

            let $grid = $('.shop-wrapper');

            if (!$grid.length) {
                return;
            }

            if (typeof $.fn.isotope !== 'undefined') {

                if ($grid.data('isotope')) {

                    $grid.isotope('destroy');

                }

                $grid.isotope({

                    itemSelector: '.grid-item',

                    percentPosition: true

                });

            }

        }

        // =====================================================
        // REMOVE SINGLE FILTER
        // =====================================================

        $(document).on('click', '.remove-filter', function() {

            let type = $(this).data('type');

            let id = $(this).data('id');

            $('input[name="' + type + '"][value="' + id + '"]')
                .prop('checked', false)
                .trigger('change');

        });

        // =====================================================
        // CLEAR ALL FILTERS
        // =====================================================

        $(document).on('click', '.clear-all-filters', function() {

            $('.filter-trigger').prop('checked', false);

            $('#pagesize').val('12');

            $('#orderby').val('1');

            let url = buildUrl(1);

            loadProducts(url);

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
    </script>
@endpush
