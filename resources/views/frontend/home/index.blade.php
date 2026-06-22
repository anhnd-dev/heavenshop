@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/collection/style.css') }}">
@endpush

@section('content')
    @include('frontend.home.sections.sliders')

    @include('frontend.home.sections.hero')

    @include('frontend.home.sections.categories')

    @include('frontend.home.sections.best-sellers')

    @include('frontend.home.sections.promotion')

    @include('frontend.home.sections.collections')

    @include('frontend.home.sections.brands')

    @include('frontend.home.sections.featured-products')

    @include('frontend.home.sections.ads')

    {{-- @include('frontend.home.sections.magazine') --}}
@endsection

@push('scripts')
    <script>
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
