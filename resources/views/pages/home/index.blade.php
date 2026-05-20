@extends('layouts.base')

@section('content')
    @include('pages.home.slider')

    <section class="half-section">
        <div class="container">
            <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-sm-2"
                data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 800, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <div class="col icon-with-text-style-01 md-mb-35px">
                    <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                        <div class="feature-box-icon me-20px">
                            <i class="line-icon-Box-Close icon-large text-dark-gray"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="alt-font fs-20 fw-500 d-block text-dark-gray">{{ __('frontend.home.free_ship.title') }}</span>
                            <p class="fs-16 lh-24">{{ __('frontend.home.free_ship.content') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col icon-with-text-style-01 md-mb-35px">
                    <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                        <div class="feature-box-icon me-20px">
                            <i class="line-icon-Reload-3 icon-large text-dark-gray"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="alt-font fs-20 fw-500 d-block text-dark-gray">{{ __('frontend.home.refund.title') }}</span>
                            <p class="fs-16 lh-24">{{ __('frontend.home.refund.title') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col icon-with-text-style-01 xs-mb-35px">
                    <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                        <div class="feature-box-icon me-20px">
                            <i class="line-icon-Credit-Card2 icon-large text-dark-gray"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="alt-font fs-20 fw-500 d-block text-dark-gray">{{ __('frontend.home.payment.title') }}</span>
                            <p class="fs-16 lh-24">{{ __('frontend.home.payment.title') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col icon-with-text-style-01">
                    <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                        <div class="feature-box-icon me-20px">
                            <i class="line-icon-Phone-2 icon-large text-dark-gray"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="alt-font fs-20 fw-500 d-block text-dark-gray">{{ __('frontend.home.support.title') }}</span>
                            <p class="fs-16 lh-24">{{ __('frontend.home.support.title') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.home.category')

    @include('pages.home.product_seller')

    @if ($coupon)
        <section class="p-15px bg-dark-gray text-white">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <span class="fs-15 text-uppercase fw-500">{{ __('frontend.home.promotion') }} <span
                                class="fs-14 fw-700 lh-28 alt-font text-dark-gray text-uppercase bg-base-color d-inline-block border-radius-30px ps-15px pe-15px ms-5px align-middle">{{ $coupon->code }}</span></span>
                    </div>
                </div>
            </div>
        </section>
    @else

    @endif


    @include('pages.home.collection')

    @include('pages.home.brand')

    @include('pages.home.product_featured')

    <section class="p-0 border-top border-bottom border-color-extra-medium-gray">
        <div class="container-fluid">
            <div class="row position-relative">
                <div class="col swiper text-center swiper-width-auto"
                    data-slider-options='{ "slidesPerView": "auto", "spaceBetween":0, "speed": 10000, "loop": true, "pagination": { "el": ".slider-four-slide-pagination-2", "clickable": false }, "allowTouchMove": false, "autoplay": { "delay":0, "disableOnInteraction": false }, "navigation": { "nextEl": ".slider-four-slide-next-2", "prevEl": ".slider-four-slide-prev-2" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
                    <div class="swiper-wrapper marquee-slide">
                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.order') }}
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.collection') }}
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.payment') }}
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.shipping') }}
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.card') }}
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div
                                class="alt-font fs-26 fw-500 text-dark-gray border-color-extra-medium-gray border-end pt-30px pb-30px ps-60px pe-60px sm-p-25px">
                                {{ __('frontend.home.swipe_slide.secure') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.home.magazine')
@endsection
