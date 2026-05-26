@extends('layouts.base')

@section('content')
    <section class="top-space-margin bg-gradient-very-light-gray pt-20px pb-20px ps-45px pe-45px sm-ps-15px sm-pe-15px">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 breadcrumb breadcrumb-style-01 fs-14">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="shop.html">Shop</a></li>
                        <li>{{ $product->name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    @php
        print_r(Session::get('cart'));
    @endphp

    <section class="pt-60px pb-0 md-pt-30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 pe-50px md-pe-15px md-mb-40px">
                    <div class="row overflow-hidden position-relative">
                        <div class="col-12 col-lg-10 position-relative order-lg-2 product-image ps-30px md-ps-15px">
                            <div class="swiper product-image-slider"
                                data-slider-options="{ &quot;spaceBetween&quot;: 10, &quot;loop&quot;: true, &quot;autoplay&quot;: { &quot;delay&quot;: 2000, &quot;disableOnInteraction&quot;: false }, &quot;watchOverflow&quot;: true, &quot;navigation&quot;: { &quot;nextEl&quot;: &quot;.slider-product-next&quot;, &quot;prevEl&quot;: &quot;.slider-product-prev&quot; }, &quot;thumbs&quot;: { &quot;swiper&quot;: { &quot;el&quot;: &quot;.product-image-thumb&quot;, &quot;slidesPerView&quot;: &quot;auto&quot;, &quot;spaceBetween&quot;: 15, &quot;direction&quot;: &quot;vertical&quot;, &quot;navigation&quot;: { &quot;nextEl&quot;: &quot;.swiper-thumb-next&quot;, &quot;prevEl&quot;: &quot;.swiper-thumb-prev&quot; } } } }"
                                data-thumb-slider-md-direction="horizontal">
                                <div class="swiper-wrapper">

                                    @foreach ($galleries as $gallery)
                                        <div class="swiper-slide gallery-box">
                                            <a href="{{ asset('uploads/gallery/' . $gallery->image) }}"
                                                data-group="lightbox-gallery" title="Relaxed corduroy shirt">
                                                <img class="w-100" src="{{ asset('uploads/gallery/' . $gallery->image) }}"
                                                    alt>
                                            </a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-2 order-lg-1 position-relative single-product-thumb">
                            <div class="swiper-container product-image-thumb slider-vertical">
                                <div class="swiper-wrapper">

                                    @foreach ($galleries as $gallery)
                                        <div class="swiper-slide"><img class="w-100"
                                                src="{{ asset('uploads/gallery/' . $gallery->image) }}" alt></div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 product-info">
                    <span class="fw-500 text-dark-gray d-block">{{ $product->brand->name }}</span>
                    <h4 class="alt-font text-dark-gray fw-400 mb-15px fs-22" style="line-height: normal;">
                        {{ shortenText($product->name, 90) }}</h4>
                    <div class="d-block d-sm-flex align-items-center mb-15px">
                        <div class="me-10px xs-me-0">
                            <a href="#tab" class="section-link ls-minus-1px icon-small">
                                <i class="bi bi-star-fill text-golden-yellow"></i>
                                <i class="bi bi-star-fill text-golden-yellow"></i>
                                <i class="bi bi-star-fill text-golden-yellow"></i>
                                <i class="bi bi-star-fill text-golden-yellow"></i>
                                <i class="bi bi-star-fill text-golden-yellow"></i>
                            </a>
                        </div>
                        <a href="#tab" class="me-15px text-dark-gray fw-500 section-link xs-me-0">165 Reviews</a>
                        <div><span class="text-dark-gray fw-500">SKU: </span>{{ $product->SKU }}</div>
                    </div>
                    <div class="product-price mb-10px">
                        <span class="text-dark-gray fs-28 xs-fs-24 fw-700 ls-minus-1px">
                            @if ($product->discount > 0)
                                <del class="text-medium-gray me-10px fw-400">{{ number_format($product->price, 0, ',', '.') }}
                                    ₫</del>
                                @php
                                    $discountAmount = ($product->price * $product->discount) / 100;
                                    $discountedPrice = $product->price - $discountAmount;
                                @endphp
                                {{ number_format($discountedPrice, 0, ',', '.') }} ₫
                            @else
                                {{ number_format($product->price, 0, ',', '.') }} ₫
                            @endif
                        </span>
                    </div>

                    <p>{!! $product->content !!}</p>

                    <div class="d-flex align-items-center mb-20px">
                        <label class="text-dark-gray alt-font me-15px fw-500">Color</label>
                        <ul class="shop-color mb-0">
                            @foreach ($product->colors as $color)
                                <li>
                                    <input class="d-none" type="radio" id="color-{{ $color->id }}" name="color"
                                        value="{{ $color->id }}">
                                    <label for="color-{{ $color->id }}"><span
                                            style="background-color: {{ $color->code }}"></span></label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="d-flex align-items-center mb-35px">
                        <label class="text-dark-gray me-15px fw-500">Size</label>
                        <ul class="shop-size mb-0">
                            @foreach ($product->sizes as $size)
                                <li>
                                    <input class="d-none" type="radio" id="size-{{ $size->id }}" name="size"
                                        value="{{ $size->id }}">
                                    <label for="size-{{ $size->id }}"><span>{{ $size->name }}</span></label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="d-flex align-items-center flex-column flex-sm-row mb-20px position-relative">
                        <div class="quantity me-15px xs-mb-15px order-1">
                            <button type="button" class="qty-minus">-</button>
                            <input type="hidden" id="quantity-stock" value="{{ $product->quantity }}">
                            <input class="qty-text" type="text" id="quantity-input" value="1" />
                            <button type="button" class="qty-plus">+</button>
                        </div>
                        <button type="button" onclick="addToCart({{ $product->id }})"
                            class="btn btn-cart btn-extra-large btn-switch-text btn-box-shadow btn-none-transform btn-dark-gray left-icon btn-round-edge border-0 me-15px xs-me-0 order-3 order-sm-2">
                            <span>
                                <span><i class="feather icon-feather-shopping-bag"></i></span>
                                <span class="btn-double-text ls-0px" data-text="Add to cart">Add to cart</span>
                            </span>
                        </button>
                        <a href="#"
                            class="wishlist d-flex align-items-center justify-content-center border border-radius-5px border-color-extra-medium-gray order-2 order-sm-3">
                            <i class="feather icon-feather-heart icon-small text-dark-gray"></i>
                        </a>
                    </div>

                    <div class="row mb-20px">
                        <div class="col-auto icon-with-text-style-08">
                            <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                                <div class="feature-box-icon me-10px">
                                    <i class="feather icon-feather-repeat align-middle text-dark-gray"></i>
                                </div>
                                <div class="feature-box-content">
                                    <a href="#" class="alt-font fw-500 text-dark-gray d-block">Compare</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto icon-with-text-style-08">
                            <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                                <div class="feature-box-icon me-10px">
                                    <i class="feather icon-feather-mail align-middle text-dark-gray"></i>
                                </div>
                                <div class="feature-box-content">
                                    <a href="#" class="alt-font fw-500 text-dark-gray d-block">Ask a
                                        question</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto icon-with-text-style-08">
                            <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                                <div class="feature-box-icon me-10px">
                                    <i class="feather icon-feather-share-2 align-middle text-dark-gray"></i>
                                </div>
                                <div class="feature-box-content">
                                    <a href="#" class="alt-font fw-500 text-dark-gray d-block">Share</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-20px h-1px w-100 bg-extra-medium-gray d-block"></div>

                    <div class="row mb-15px">
                        <div class="col-12 icon-with-text-style-08">
                            <div class="feature-box feature-box-left-icon d-inline-flex align-middle">
                                <div class="feature-box-icon me-10px">
                                    <i
                                        class="feather icon-feather-truck top-8px position-relative align-middle text-dark-gray"></i>
                                </div>
                                <div class="feature-box-content">
                                    <span><span class="alt-font text-dark-gray fw-500">Estimated delivery:</span>
                                        March 03 - March 07</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 icon-with-text-style-08 mb-10px">
                            <div class="feature-box feature-box-left-icon d-inline-flex align-middle">
                                <div class="feature-box-icon me-10px">
                                    <i
                                        class="feather icon-feather-archive top-8px position-relative align-middle text-dark-gray"></i>
                                </div>
                                <div class="feature-box-content">
                                    <span><span class="alt-font text-dark-gray fw-500">Free shipping &
                                            returns:</span> On all orders over $50</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-very-light-gray ps-30px pe-30px pt-25px pb-25px mb-20px xs-p-25px border-radius-4px">
                        <span class="alt-font fs-17 fw-500 text-dark-gray mb-15px d-block lh-initial">Guarantee safe
                            and secure checkout</span>
                        <div>
                            <a href="#"><img src="images/visa.svg" class="h-30px me-5px mb-5px" alt></a>
                            <a href="#"><img src="images/mastercard.svg" class="h-30px me-5px mb-5px" alt></a>
                            <a href="#"><img src="images/american-express.svg" class="h-30px me-5px mb-5px"
                                    alt></a>
                            <a href="#"><img src="images/discover.svg" class="h-30px me-5px mb-5px" alt></a>
                            <a href="#"><img src="images/diners-club.svg" class="h-30px me-5px mb-5px" alt></a>
                            <a href="#"><img src="images/union-pay.svg" class="h-30px" alt></a>
                        </div>
                    </div>

                    <div>
                        <div class="w-100 d-block">
                            <span class="text-dark-gray alt-font fw-500">Stock:</span>
                            <span id="stockQty"></span>
                        </div>
                        <div class="w-100 d-block"><span class="text-dark-gray alt-font fw-500">Category:</span> <a
                                href="{{ route('shop') }}?subcategories={{ $product->subcategory->id }}">{{ $product->subcategory->name }}</a>
                        </div>
                        <div><span class="text-dark-gray alt-font fw-500">Tags: </span><a href="#">Shirts,</a>
                            <a href="#">Cotton,</a> <a href="#">Printed</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tab" class="pt-4 sm-pt-40px">
        <div class="container">
            <div class="row">
                <div class="col-12 tab-style-04">
                    <ul class="nav nav-tabs border-0 justify-content-center alt-font fs-19">
                        <li class="nav-item"><a data-bs-toggle="tab" href="#tab_five1"
                                class="nav-link active">Description<span class="tab-border bg-dark-gray"></span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_five2">Additional
                                information<span class="tab-border bg-dark-gray"></span></a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_five3">Shipping and
                                return<span class="tab-border bg-dark-gray"></span></a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_five4"
                                data-tab="review-tab">Reviews (3)<span class="tab-border bg-dark-gray"></span></a></li>
                    </ul>
                    <div class="mb-5 h-1px w-100 bg-extra-medium-gray sm-mt-10px xs-mb-8"></div>
                    <div class="tab-content">

                        <div class="tab-pane fade in active show" id="tab_five1">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-6 md-mb-40px">
                                    <div class="d-flex align-items-center mb-5px">
                                        <div class="col-auto pe-5px"><i class="bi bi-heart-fill text-red fs-16"></i>
                                        </div>
                                        <div class="col alt-font fw-500 text-dark-gray">We make you feel special</div>
                                    </div>
                                    <h4 class="alt-font text-dark-gray fw-500 mb-20px w-90 lg-w-100">Unique and quirky
                                        designs for the latest trends product.</h4>
                                    <p class="w-90">Lorem ipsum is simply dummy text of the printing and typesetting
                                        industry lorem ipsum has been the standard dummy text.</p>
                                    <div>
                                        <div class="feature-box feature-box-left-icon-middle mb-10px">
                                            <div
                                                class="feature-box-icon feature-box-icon-rounded w-30px h-30px rounded-circle bg-very-light-gray me-10px">
                                                <i class="fa-solid fa-check fs-12 text-dark-gray"></i>
                                            </div>
                                            <div class="feature-box-content">
                                                <span class="d-block text-dark-gray fw-500">Made from soft yet durable
                                                    100% organic cotton twill.</span>
                                            </div>
                                        </div>
                                        <div class="feature-box feature-box-left-icon-middle mb-10px">
                                            <div
                                                class="feature-box-icon feature-box-icon-rounded w-30px h-30px rounded-circle bg-very-light-gray me-10px">
                                                <i class="fa-solid fa-check fs-12 text-dark-gray"></i>
                                            </div>
                                            <div class="feature-box-content">
                                                <span class="d-block text-dark-gray fw-500">Front and back yoke seams
                                                    allow a full range of shoulder.</span>
                                            </div>
                                        </div>
                                        <div class="feature-box feature-box-left-icon-middle mb-10px">
                                            <div
                                                class="feature-box-icon feature-box-icon-rounded w-30px h-30px rounded-circle bg-very-light-gray me-10px">
                                                <i class="fa-solid fa-check fs-12 text-dark-gray"></i>
                                            </div>
                                            <div class="feature-box-content">
                                                <span class="d-block text-dark-gray fw-500">Interior storm flap and
                                                    zipper garage at chin for comfort.</span>
                                            </div>
                                        </div>
                                        <div class="feature-box feature-box-left-icon-middle">
                                            <div
                                                class="feature-box-icon feature-box-icon-rounded w-30px h-30px rounded-circle bg-very-light-gray me-10px">
                                                <i class="fa-solid fa-check fs-12 text-dark-gray"></i>
                                            </div>
                                            <div class="feature-box-content">
                                                <span class="d-block text-dark-gray fw-500">Color may slightly vary
                                                    depending on your screen.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-8">
                                    <img src="images/demo-fashion-store-product-detail-07.jpg" alt class="w-100" />
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade in" id="tab_five2">
                            <div class="row m-0">
                                <div class="col-12">
                                    <div class="row">
                                        <div
                                            class="col-lg-2 col-md-3 col-sm-4 pt-10px pb-10px xs-pb-0 text-dark-gray alt-font fw-500">
                                            Color:</div>
                                        <div class="col-lg-10 col-md-9 col-sm-8 pt-10px pb-10px xs-pt-0">Black, yellow
                                        </div>
                                    </div>
                                    <div class="row bg-very-light-gray">
                                        <div
                                            class="col-lg-2 col-md-3 col-sm-4 pt-10px pb-10px xs-pb-0 text-dark-gray alt-font fw-500">
                                            Style/Type:</div>
                                        <div class="col-lg-10 col-md-9 col-sm-8 pt-10px pb-10px xs-pt-0">Sports, Formal
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div
                                            class="col-lg-2 col-md-3 col-sm-4 pt-10px pb-10px xs-pb-0 text-dark-gray alt-font fw-500">
                                            Lining:</div>
                                        <div class="col-lg-10 col-md-9 col-sm-8 pt-10px pb-10px xs-pt-0">100% polyester
                                            taffeta with a DWR finish</div>
                                    </div>
                                    <div class="row bg-very-light-gray">
                                        <div
                                            class="col-lg-2 col-md-3 col-sm-4 pt-10px pb-10px xs-pb-0 text-dark-gray alt-font fw-500">
                                            Material:</div>
                                        <div class="col-lg-10 col-md-9 col-sm-8 pt-10px pb-10px xs-pt-0">Lather, Cotton,
                                            Silk</div>
                                    </div>
                                    <div class="row">
                                        <div
                                            class="col-lg-2 col-md-3 col-sm-4 pt-10px pb-10px xs-pb-0 text-dark-gray alt-font fw-500">
                                            Free shipping:</div>
                                        <div class="col-lg-10 col-md-9 col-sm-8 pt-10px pb-10px xs-pt-0">On all orders
                                            over $50</div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade in" id="tab_five3">
                            <div class="row">
                                <div class="col-12 col-md-6 last-paragraph-no-margin sm-mb-30px">
                                    <div class="alt-font fs-22 text-dark-gray mb-15px fw-500">Shipping information</div>
                                    <p class="mb-0"><span class="fw-500 text-dark-gray">Standard:</span> Arrives in 5-8
                                        business days</p>
                                    <p><span class="fw-500 text-dark-gray">Express:</span> Arrives in 2-3 business days
                                    </p>
                                    <p class="w-80 md-w-100">These shipping rates are not applicable for orders shipped
                                        outside of the US. Some oversized items may require an additional shipping
                                        charge. Free Shipping applies only to merchandise taxes and gift cards do not
                                        count toward the free shipping total.</p>
                                </div>
                                <div class="col-12 col-md-6 last-paragraph-no-margin">
                                    <div class="alt-font fs-22 text-dark-gray mb-15px fw-500">Return information</div>
                                    <p class="w-80 md-w-100">Orders placed between 10/1/2023 and 12/23/2023 can be
                                        returned by 2/27/2023.</p>
                                    <p class="w-80 md-w-100">Return or exchange any unused or defective merchandise by
                                        mail or at one of our US or Canada store locations. Returns made within 30 days
                                        of the order delivery date will be issued a full refund to the original form of
                                        payment.</p>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade in" id="tab_five4">
                            <div class="row align-items-center mb-6 sm-mb-10">
                                <div class="col-lg-4 col-md-12 col-sm-7 md-mb-30px text-center text-lg-start">
                                    <h5 class="alt-font text-dark-gray fw-500 mb-0 w-85 lg-w-100"><span
                                            class="fw-600">25,000+</span> people are like our product and say good
                                        story.</h5>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-5 text-center sm-mb-20px p-0 md-ps-15px md-pe-15px">
                                    <div class="border-radius-4px bg-very-light-gray p-30px xl-p-20px">
                                        <h2 class="mb-5px alt-font text-dark-gray fw-600">4.9</h2>
                                        <span class="text-golden-yellow icon-small d-block ls-minus-1px mb-5px">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </span>
                                        <span
                                            class="ps-15px pe-15px pt-10px pb-10px lh-normal bg-dark-gray text-white fs-12 fw-600 text-uppercase border-radius-4px d-inline-block text-center">2,488
                                            Reviews</span>
                                    </div>
                                </div>
                                <div class="col-9 col-lg-4 col-md-5 col-sm-8 progress-bar-style-02">
                                    <div class="ps-20px md-ps-0">
                                        <div class="text-dark-gray mb-15px fw-600">Average customer ratings</div>

                                        <div class="progress mb-20px border-radius-6px">
                                            <div class="progress-bar bg-green m-0" role="progressbar" aria-valuenow="95"
                                                aria-valuemin="0" aria-valuemax="100" aria-label="rating"></div>
                                        </div>


                                        <div class="progress mb-20px border-radius-6px">
                                            <div class="progress-bar bg-green m-0" role="progressbar" aria-valuenow="66"
                                                aria-valuemin="0" aria-valuemax="100" aria-label="rating"></div>
                                        </div>


                                        <div class="progress mb-20px border-radius-6px">
                                            <div class="progress-bar bg-green m-0" role="progressbar" aria-valuenow="40"
                                                aria-valuemin="0" aria-valuemax="100" aria-label="rating"></div>
                                        </div>


                                        <div class="progress mb-20px border-radius-6px">
                                            <div class="progress-bar bg-green m-0" role="progressbar" aria-valuenow="25"
                                                aria-valuemin="0" aria-valuemax="100" aria-label="rating"></div>
                                        </div>


                                        <div class="progress sm-mb-0 border-radius-6px">
                                            <div class="progress-bar bg-green m-0" role="progressbar" aria-valuenow="05"
                                                aria-valuemin="0" aria-valuemax="100" aria-label="rating"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-3 col-lg-2 col-md-3 col-sm-4 mt-45px">
                                    <div class="mb-15px lh-0 xs-lh-normal xs-mb-10px">
                                        <span class="text-golden-yellow fs-15 ls-minus-1px d-none d-sm-inline-block">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </span>
                                        <span class="fs-13 text-dark-gray fw-600 ms-10px xs-ms-0">80%</span>
                                    </div>
                                    <div class="mb-15px lh-0 xs-lh-normal xs-mb-10px">
                                        <span class="text-golden-yellow fs-15 ls-minus-1px d-none d-sm-inline-block">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="feather icon-feather-star"></i>
                                        </span>
                                        <span class="fs-13 text-dark-gray fw-600 ms-10px xs-ms-0">10%</span>
                                    </div>
                                    <div class="mb-15px lh-0 xs-lh-normal xs-mb-10px">
                                        <span class="text-golden-yellow fs-15 ls-minus-1px d-none d-sm-inline-block">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                        </span>
                                        <span class="fs-13 text-dark-gray fw-600 ms-10px xs-ms-0">05%</span>
                                    </div>
                                    <div class="mb-15px lh-0 xs-lh-normal xs-mb-10px">
                                        <span class="text-golden-yellow fs-15 ls-minus-1px d-none d-sm-inline-block">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                        </span>
                                        <span class="fs-13 text-dark-gray fw-600 ms-10px xs-ms-0">03%</span>
                                    </div>
                                    <div class="lh-0 xs-lh-normal">
                                        <span class="text-golden-yellow fs-15 ls-minus-1px d-none d-sm-inline-block">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                            <i class="feather icon-feather-star"></i>
                                        </span>
                                        <span class="fs-13 text-dark-gray fw-600 ms-10px xs-ms-0">02%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-4 md-mb-35px">
                                <div
                                    class="col-12 border-bottom border-color-extra-medium-gray pb-40px mb-40px xs-pb-30px xs-mb-30px">
                                    <div class="d-block d-md-flex w-100 align-items-center">
                                        <div class="w-300px md-w-250px sm-w-100 sm-mb-10px text-center">
                                            <img src="images/avtar-27.jpg" class="rounded-circle w-90px mb-10px" alt>
                                            <span class="text-dark-gray fw-600 d-block">Herman miller</span>
                                            <div class="fs-14 lh-18">06 April 2023</div>
                                        </div>
                                        <div
                                            class="w-100 last-paragraph-no-margin sm-ps-0 position-relative text-center text-md-start">
                                            <span
                                                class="text-golden-yellow ls-minus-1px mb-5px sm-me-10px sm-mb-0 d-inline-block d-md-block">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                            <a href="#"
                                                class="w-65px bg-light-red border-radius-15px fs-13 text-dark-gray fw-600 text-center position-absolute sm-position-relative d-inline-block d-md-block right-0px top-0px"><i
                                                    class="fa-solid fa-heart text-red me-5px"></i><span>08</span></a>
                                            <p class="w-85 sm-w-100 sm-mt-15px">Lorem ipsum dolor sit sed do eiusmod
                                                tempor incididunt labore enim ad minim veniam, quis nostrud exercitation
                                                ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                                                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                                nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-12 border-bottom border-color-extra-medium-gray pb-40px mb-40px xs-pb-30px xs-mb-30px">
                                    <div class="d-block d-md-flex w-100 align-items-center">
                                        <div class="w-300px md-w-250px sm-w-100 sm-mb-10px text-center">
                                            <img src="images/avtar-28.jpg" class="rounded-circle w-90px mb-10px" alt>
                                            <span class="text-dark-gray fw-600 d-block">Wilbur haddock</span>
                                            <div class="fs-14 lh-18">26 April 2023</div>
                                        </div>
                                        <div
                                            class="w-100 last-paragraph-no-margin sm-ps-0 position-relative text-center text-md-start">
                                            <span
                                                class="text-golden-yellow ls-minus-1px mb-5px sm-me-10px sm-mb-0 d-inline-block d-md-block">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                            <a href="#"
                                                class="w-65px bg-light-red border-radius-15px fs-13 text-dark-gray fw-600 text-center position-absolute sm-position-relative d-inline-block d-md-block right-0px top-0px"><i
                                                    class="fa-solid fa-heart text-red me-5px"></i><span>06</span></a>
                                            <p class="w-85 sm-w-100 sm-mt-15px">Lorem ipsum dolor sit sed do eiusmod
                                                tempor incididunt labore enim ad minim veniamnisi ut aliquip ex ea
                                                commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                                occaecat cupidatat non proident.</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-12 border-bottom border-color-extra-medium-gray pb-40px mb-40px xs-pb-30px md-mb-25px">
                                    <div class="d-block d-md-flex w-100 align-items-center">
                                        <div class="w-300px md-w-250px sm-w-100 sm-mb-10px text-center">
                                            <img src="images/avtar-29.jpg" class="rounded-circle w-90px mb-10px" alt>
                                            <span class="text-dark-gray fw-600 d-block">Colene landin</span>
                                            <div class="fs-14 lh-18">28 April 2023</div>
                                        </div>
                                        <div
                                            class="w-100 last-paragraph-no-margin sm-ps-0 position-relative text-center text-md-start">
                                            <span
                                                class="text-golden-yellow ls-minus-1px mb-5px sm-me-10px sm-mb-0 d-inline-block d-md-block">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                            <a href="#"
                                                class="w-65px bg-light-red border-radius-15px fs-13 text-dark-gray fw-600 text-center position-absolute sm-position-relative d-inline-block d-md-block right-0px top-0px"><i
                                                    class="fa-regular fa-heart text-red me-5px"></i><span>00</span></a>
                                            <p class="w-85 sm-w-100 sm-mt-15px">Lorem ipsum dolor sit sed do eiusmod
                                                tempor incididunt labore enim adquis nostrud exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                                pariatur. Excepteur sint occaecat cupidatat non proident.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 last-paragraph-no-margin text-center">
                                    <a href="#"
                                        class="btn btn-link btn-hover-animation-switch btn-extra-large text-dark-gray">
                                        <span>
                                            <span class="btn-text">Show more reviews</span>
                                            <span class="btn-icon"><i class="fa-solid fa-chevron-down"></i></span>
                                            <span class="btn-icon"><i class="fa-solid fa-chevron-down"></i></span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="p-7 lg-p-5 sm-p-7 bg-very-light-gray">
                                        <div class="row justify-content-center mb-30px sm-mb-10px">
                                            <div class="col-md-9 text-center">
                                                <h4 class="alt-font text-dark-gray fw-500 mb-15px">Add a review</h4>
                                            </div>
                                        </div>
                                        <form action="https://craftohtml.themezaa.com/email-templates/contact-form.php"
                                            method="post" class="row contact-form-style-02">
                                            <div class="col-lg-5 col-md-6 mb-20px">
                                                <label class="form-label mb-15px">Your name*</label>
                                                <input class="input-name border-radius-4px form-control required"
                                                    type="text" name="name" placeholder="Enter your name">
                                            </div>
                                            <div class="col-lg-5 col-md-6 mb-20px">
                                                <label class="form-label mb-15px">Your email address*</label>
                                                <input class="border-radius-4px form-control required" type="email"
                                                    name="email" placeholder="Enter your email address">
                                            </div>
                                            <div class="col-lg-2 mb-20px">
                                                <label class="form-label">Your rating*</label>
                                                <div>
                                                    <span class="ls-minus-1px icon-small d-block mt-20px md-mt-0">
                                                        <i class="feather icon-feather-star text-golden-yellow"></i>
                                                        <i class="feather icon-feather-star text-golden-yellow"></i>
                                                        <i class="feather icon-feather-star text-golden-yellow"></i>
                                                        <i class="feather icon-feather-star text-golden-yellow"></i>
                                                        <i class="feather icon-feather-star text-golden-yellow"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-20px">
                                                <label class="form-label mb-15px">Your review</label>
                                                <textarea class="border-radius-4px form-control" cols="40" rows="4" name="comment"
                                                    placeholder="Your message"></textarea>
                                            </div>
                                            <div class="col-lg-9 md-mb-25px">
                                                <div
                                                    class="position-relative terms-condition-box text-start is-invalid mt-10px">
                                                    <label class="d-inline-block">
                                                        <input type="checkbox" name="terms_condition"
                                                            id="terms_condition" value="1"
                                                            class="terms-condition check-box align-middle required">
                                                        <span class="box fs-15">I accept the crafto terms and conditions
                                                            and I have read the privacy policy.</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 text-start text-lg-end">
                                                <input type="hidden" name="redirect" value>
                                                <button
                                                    class="btn btn-dark-gray btn-small btn-box-shadow btn-round-edge submit"
                                                    type="submit">Submit review</button>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-results mt-20px d-none"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.product.related')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("change", "#quantity-input", function() {
                let $input = $(this);
                let quantity = parseInt($input.val());
                let quantityStock = $("#quantity-stock").val();

                if (quantity > quantityStock) {
                    toastr.error(
                        `The number entered only ranges from 1 - ${quantityStock}`
                    );
                    $input.val(1);
                    return;
                }

                $input.val(quantity);
            });

            $(document).on("click", ".qty-plus, .qty-minus", function() {
                let $input = $(this).siblings("#quantity-input");
                let quantity = parseInt($input.val());
                let quantityStock = parseInt($("#quantity-stock").val());

                if ($(this).hasClass("qty-plus")) {
                    quantity++;
                    if (quantity > quantityStock) {
                        toastr.error(
                            `Số lượng trong kho chỉ còn lại ${quantityStock}`
                        );
                        $input.val(quantityStock);
                        return;
                    }
                } else if ($(this).hasClass("minus-cart-quantity") && quantity > 1) {
                    quantity--;
                }

                $input.val(quantity);
            });
        });

        function addToCart(productId) {
            let selectedColor = $("input[name='color']:checked").val();
            let selectedSize = $("input[name='size']:checked").val();
            let qty = $('#quantity-input').val();

            if (!selectedColor) {
                toastr.error("Vui lòng chọn màu sắc.");
                return;
            }

            if (!selectedSize) {
                toastr.error("Vui lòng chọn kích thước.");
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('add.to.cart') }}",
                data: {
                    product_id: productId,
                    quantity: qty,
                    color: selectedColor,
                    size: selectedSize,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function(res) {
                if (res.status == 200) {
                    toastr.success(res.message);
                } else if (res.status == 302) {
                    toastr.warning(res.message);
                } else {
                    toastr.error(res.message);
                }
            });
        }
    </script>
@endpush
