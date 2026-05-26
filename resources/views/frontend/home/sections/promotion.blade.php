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
