<section class="pt-0">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">Related <span
                        class="text-highlight fw-600">products<span class="bg-base-color h-5px bottom-2px"></span></span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul
                    class="shop-modern shop-wrapper grid grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
                    <li class="grid-sizer"></li>

                    @foreach ($rproducts as $item)
                    <li class="grid-item">
                        <div class="shop-box mb-10px">
                            <div class="shop-image mb-20px">
                                <a href="single-product.html">
                                    <img src="{{ asset('uploads/product/' . $item->image) }}"
                                        alt="{{ $item->name }}" />

                                    @if ($item->sold_quantity >= 501)
                                        <span class="lable new best-seller">Best Seller</span>
                                    @elseif ($item->sold_quantity >= 100)
                                        <span class="lable new hot">Hot</span>
                                    @else
                                    @endif

                                    <div class="shop-overlay bg-gradient-gray-light-dark-transparent"></div>
                                </a>
                                <div class="shop-buttons-wrap">
                                    <a href="single-product.html"
                                        class="alt-font btn btn-small btn-box-shadow btn-white btn-round-edge left-icon add-to-cart">
                                        <i class="feather icon-feather-shopping-bag"></i><span
                                            class="quick-view-text button-text">Add to cart</span>
                                    </a>
                                </div>
                                <div class="shop-hover d-flex justify-content-center">
                                    <ul>
                                        <li>
                                            <a href="#"
                                                class="w-40px h-40px bg-white text-dark-gray d-flex align-items-center justify-content-center rounded-circle ms-5px me-5px"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                title="Add to wishlist"><i
                                                    class="feather icon-feather-heart fs-16"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="w-40px h-40px bg-white text-dark-gray d-flex align-items-center justify-content-center rounded-circle ms-5px me-5px"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                title="Quick shop"><i
                                                    class="feather icon-feather-eye fs-16"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="shop-footer" style="{{ $item->discount > 0 ? '' : 'display: flex; justify-content: space-around;' }}">
                                <a href="single-product.html"
                                    class="alt-font text-dark-gray fs-19 fw-500">{{ $item->name }}</a>

                                @if ($item->discount > 0)
                                    <div class="price lh-22 fs-16">
                                        <del>{{ number_format($item->price, 0, ',', '.') }} ₫</del>
                                        @php
                                            $discountAmount = ($item->price * $item->discount) / 100;
                                            $discountedPrice = $item->price - $discountAmount;
                                        @endphp
                                        {{ number_format($discountedPrice, 0, ',', '.') }} ₫
                                    </div>
                                @else
                                    <span>{{ number_format($item->price, 0, ',', '.') }} ₫</span>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
