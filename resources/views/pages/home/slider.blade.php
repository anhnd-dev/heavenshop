<section class="p-0">
    <div class="swiper full-screen top-space-margin md-h-600px sm-h-500px magic-cursor magic-cursor-vertical swiper-number-pagination-progress swiper-number-pagination-progress-vertical"
        data-slider-options='{ "slidesPerView": 1, "direction": "horizontal", "loop": true, "parallax": true, "speed": 1000, "pagination": { "el": ".swiper-number", "clickable": true }, "autoplay": { "delay": 4000, "disableOnInteraction": false },  "keyboard": { "enabled": true, "onlyInViewport": true }, "breakpoints": { "1199": { "direction": "vertical" }}, "effect": "slide" }'
        data-swiper-number-pagination-progress="true">
        <div class="swiper-wrapper">
            @foreach ($sliders as $slider)
                @php
                    $data = json_decode($slider->data_value);
                @endphp

                <div class="swiper-slide overflow-hidden">
                    <div class="cover-background position-absolute top-0 start-0 w-100 h-100" data-swiper-parallax="500"
                        style="background-image: url('{{ asset('uploads/slider/' . $data->image_url) }}');">
                        <div class="container h-100">
                            <div class="row align-items-center h-100 justify-content-start">
                                <div
                                    class="col-md-10 position-relative text-white d-flex flex-column justify-content-center h-100">
                                    <div data-anime='{ "opacity": [0, 1], "translateY": [50, 0], "easing": "easeOutQuad", "duration": 500, "delay": 300 }'
                                        class="alt-font text-dark-gray mb-25px fs-20 sm-mb-15px">
                                        <span class="text-highlight">{{ $data->description }}<span
                                                class="bg-base-color h-8px bottom-0px"></span></span>
                                    </div>
                                    <div class="alt-font fs-120 xs-fs-95 lh-100 mb-40px text-dark-gray fw-600 transform-origin-right ls-minus-5px sm-mb-25px"
                                        data-anime='{ "el": "childs", "rotateX": [90, 0], "opacity": [0,1], "staggervalue": 150, "easing": "easeOutQuad" }'>
                                        @php
                                            $title = $data->title;

                                            if (strpos($title, ' ') !== false) {
                                                $words = explode(' ', $title);
                                            } else {
                                                $words = [$title];
                                            }
                                        @endphp

                                        <span class="d-block">{{ $words[0] ?? '' }}</span>
                                        <span class="d-block fw-300">{{ $words[1] ?? ''}}</span>
                                    </div>
                                    <div
                                        data-anime='{ "opacity": [0, 1], "translateY": [100, 0], "easing": "easeOutQuad", "duration": 800, "delay": 400 }'>
                                        <a href="{{ $data->link_url }}"
                                            class="btn btn-dark-gray btn-box-shadow btn-large">View
                                            collection</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination-wrapper">
            <div class="pagination-progress-vertical d-flex align-items-center justify-content-center">
                <div class="number-prev text-dark-gray fs-16 fw-500"></div>
                <div class="swiper-pagination-progress">
                    <span class="swiper-progress"></span>
                </div>
                <div class="number-next text-dark-gray fs-16 fw-500"></div>
            </div>
        </div>
    </div>
</section>
