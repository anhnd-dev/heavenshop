<section class="bg-very-light-gray overflow-hidden position-relative ps-3 xs-ps-0">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-3 ps-5 pe-5 xl-pe-0 lg-ps-0 text-center text-lg-start md-mb-40px">
                <div class="mb-10px">
                    <span class="text-dark-gray fw-500 text-highlight">Bộ sưu tập 2026<span
                            class="bg-base-color h-8px bottom-0px"></span></span>
                </div>
                <p class="xs-pe-15px xs-ps-15px">
                    Khuyến mãi mùa hè chớp nhoáng: giảm giá 70% cho một số sản phẩm dành cho nam.
                </p>
                <a href="{{ route('collection.show', 'nam') }}" class="btn btn-dark-gray btn-box-shadow btn-medium">Xem bộ
                    sưu tập</a>
            </div>
            <div class="col-12 col-lg-9 position-relative">
                <div
                    class="outside-box-right-10 lg-outside-box-right-20 md-outside-box-right-25 xs-outside-box-right-0">
                    <div class="swiper slider-three-slide"
                        data-slider-options='{ "slidesPerView": 1, "spaceBetween": 30, "loop": true, "autoplay": { "delay": 4000, "disableOnInteraction": false }, "pagination": { "el": ".slider-four-slide-pagination-1", "clickable": true, "dynamicBullets": false }, "keyboard": { "enabled": true, "onlyInViewport": true }, "breakpoints": { "1400": { "slidesPerView": 4 }, "1024": { "slidesPerView": 3 }, "768": { "slidesPerView": 3 }, "576": { "slidesPerView": 2 }, "320": { "slidesPerView": 1 } }, "effect": "slide" }'>

                        <div class="swiper-wrapper">
                            @foreach ($collections as $collection)
                                <div class="swiper-slide">
                                    <div
                                        class="interactive-banner-style-09 border-radius-6px overflow-hidden position-relative">

                                        <img src="{{ asset('uploads/category/' . $collection->image) }}"
                                            alt="{{ $collection->name }}">

                                        <div class="opacity-full bg-gradient-gray-light-dark-transparent"></div>

                                        <div
                                            class="image-content h-100 w-100 ps-15 pe-15 pt-11 pb-11 lg-p-11 d-flex justify-content-bottom align-items-start flex-column">

                                            <div
                                                class="mt-auto d-flex align-items-start w-100 z-index-1 position-relative overflow-hidden flex-column">

                                                <span class="text-white fw-500 fs-22">
                                                    {{ $collection->name }}
                                                </span>

                                                @if ($collection->description)
                                                    <span
                                                        class="content-title text-white fs-14 fw-500 opacity-7 text-uppercase ls-05px">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($collection->description), 40) }}
                                                    </span>
                                                @endif

                                                <a href="{{ route('collection.show', $collection->slug) }}"
                                                    class="content-title-hover fs-14 lh-24 fw-500 ls-05px text-uppercase text-white opacity-6 text-decoration-line-bottom">
                                                    Explore collection
                                                </a>

                                                <span
                                                    class="content-arrow lh-50 rounded-circle bg-base-color w-50px h-50px ms-20px text-center">
                                                    <i
                                                        class="bi bi-arrow-right-short text-dark-gray icon-very-medium"></i>
                                                </span>
                                            </div>

                                            <div
                                                class="position-absolute left-0px top-0px w-100 h-100 bg-gradient-regal-blue-transparent opacity-9">
                                            </div>

                                            <div class="box-overlay bg-gradient-gray-light-dark-transparent"></div>

                                            <a href="{{ route('collection.show', $collection->slug) }}"
                                                class="position-absolute z-index-1 top-0px left-0px h-100 w-100"></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fs-180 lg-fs-150 md-fs-130 fw-700 position-absolute bottom-minus-50px md-bottom-minus-40px ls-minus-5px left-0px right-0px text-center w-100 opacity-1 d-none d-md-block"
        data-bottom-top="transform:scale(1, 1) translate3d(0px, 0px, 0px);"
        data-top-bottom="transform:scale(1, 1) translate3d(-100px, 0px, 0px);">
        new collection
    </div>
</section>
