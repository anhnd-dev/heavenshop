<footer class="footer-dark bg-dark-gray p-0">
    <div class="container">
        <div class="row align-items-center pt-35px pb-35px">
            <div class="col-12 col-md-auto sm-mb-15px text-center text-md-start">
                <a href="{{ route('home') }}" class="footer-logo">
                    @if ($logoIcon)
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_white) }}"
                            data-at2x="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_white_2x) }}"
                            alt class="default-logo" />
                    @endif
                </a>
            </div>

            <div class="col">
                <ul class="footer-navbar text-center text-md-end">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">{{ __('frontend.footer.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="shop.html" class="nav-link">{{ __('frontend.footer.shop') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="collection.html" class="nav-link">{{ __('frontend.footer.collection') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('blog') }}" class="nav-link">{{ __('frontend.footer.blog') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link">{{ __('frontend.footer.contact') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row justify-content-center fs-15 lh-28 pb-50px xs-pb-35px">
            <div class="col-12 mb-50px sm-mb-35px">
                <div class="divider-style-03 divider-style-03-01 border-color-transparent-white-light"></div>
            </div>

            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">{{ __('frontend.footer.categories') }}</span>
                <ul>
                    @if ($categories)
                        @foreach ($categories as $category)
                            <li>
                                <li><a href="#">{{ $category->name }}</a></li>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">{{ __('frontend.footer.info') }}</span>
                <ul>
                    <li><a href="{{ route('about') }}">{{ __('frontend.footer.about') }}</a></li>
                    <li><a href="{{ route('contact') }}">{{ __('frontend.footer.contact') }}</a></li>
                    <li><a
                            href="{{ route('policy', ['slug' => 'terms-of-service']) }}">{{ __('frontend.footer.term_and_condition') }}</a>
                    </li>
                    <li><a href="{{ route('policy', ['slug' => 'privacy-policy']) }}">{{ __('frontend.footer.privacy_policy') }}</a></li>
                    <li><a href="#">{{ __('frontend.footer.shipping_and_delivery') }}</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">{{ __('frontend.footer.quick_links') }}</span>
                <ul>
                    <li><a href="account.html">{{ __('frontend.footer.my_account') }}</a></li>
                    <li><a href="#">{{ __('frontend.footer.order_tracking') }}</a></li>
                    <li><a href="{{ route('faqs') }}">{{ __('frontend.footer.faq') }}</a></li>
                </ul>
            </div>

            <div
                class="col-6 col-lg-3 col-md-4 col-sm-5 md-mb-50px xs-mb-30px order-sm-2 order-lg-2 offset-md-2 offset-lg-0">
                <span class="fw-500 d-block text-white mb-10px fs-17">{{ __('frontend.footer.quick_contact') }}</span>
                @if ($contact)
                    <div>
                        <i class="feather icon-feather-phone-call fs-16 text-white me-10px xs-me-5px"></i><a
                            href="tel:{{ $contact->phone_number }}">{{ $contact->phone_number }}</a>
                    </div>
                    <div class="mb-15px">
                        <i class="feather icon-feather-mail fs-16 text-white me-10px xs-me-5px"></i><a
                            href="cdn-cgi/l/email-protection.html#c1a8afa7ae81a5aeaca0a8afefa2aeac"
                            class="text-decoration-line-bottom"><span class="__cf_email__"
                                data-cfemail="355c5b535a75515a58545c5b1b565a58">{{ $contact->email }}</span></a>
                    </div>
                @endif
                <span class="fw-500 d-block text-white mb-5px fs-17">{{ __('frontend.footer.connect_with_us') }}</span>
                <div class="elements-social social-icon-style-02">
                    <ul class="light">
                        @if ($socialIcons)
                            @foreach ($socialIcons as $socialIcon)
                                @php
                                    $decoded_data = json_decode($socialIcon->data_value);
                                @endphp

                                @if ($decoded_data)
                                    <li>
                                        <a class="{{ strtolower($decoded_data->title) }}"
                                            href="{{ $decoded_data->url }}" target="_blank">{!! $decoded_data->social_icon !!}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-7 ps-20px sm-ps-15px md-mb-50px xs-mb-0 order-sm-1 order-lg-5">
                <span class="fw-500 d-block text-white mb-5px fs-17">{{ __('frontend.footer.become_a_number') }}</span>
                <div class="mb-15px">{{ __('frontend.footer.join_discount') }}</div>
                <div class="d-inline-block w-100 newsletter-style-04 position-relative mb-15px">
                    <form action="https://craftohtml.themezaa.com/email-templates/subscribe-newsletter.php"
                        method="post" class="position-relative w-100">
                        <input
                            class="input-small bg-nero-grey border-radius-4px fs-14 border-color-transparent w-100 form-control pe-50px ps-20px lg-ps-15px required"
                            type="email" name="email" placeholder="Enter your email" />
                        <input type="hidden" name="redirect" value />
                        <button class="btn pe-20px submit" aria-label="submit">
                            <i class="icon bi bi-envelope icon-small text-white"></i>
                        </button>
                        <div
                            class="form-results border-radius-4px pt-5px pb-5px ps-15px pe-15px fs-14 lh-22 mt-10px w-100 text-center position-absolute d-none">
                        </div>
                    </form>
                </div>
                <div class="footer-card">
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('frontend/images/demo-decor-store-payment-icon-01.png') }}" alt /></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('frontend/images/demo-decor-store-payment-icon-02.png') }}" alt /></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('frontend/images/demo-decor-store-payment-icon-03.png') }}" alt /></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('frontend/images/demo-decor-store-payment-icon-04.png') }}" alt /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-30px pb-30px bg-nero-grey">
        <div class="container">
            <div class="row align-items-center fs-15">
                <div class="col-12 col-lg-7 last-paragraph-no-margin md-mb-15px text-center text-lg-start lh-22">
                    <p>
                        {{ __('frontend.footer.reCapcha') }}
                        <a href="#" class="text-white text-decoration-line-bottom">{{ __('frontend.footer.privacy_policy') }}</a>
                        {{ __('frontend.footer.and') }}
                        <a href="#" class="text-white text-decoration-line-bottom">{{ __('frontend.footer.term_and_condition') }}</a>
                    </p>
                </div>
                <div class="col-12 col-lg-5 text-center text-lg-end lh-22">
                    <span>&copy; {{ __('frontend.footer.copyright') }}
                        <a href="https://www.themezaa.com/" target="_blank"
                            class="text-decoration-line-bottom text-white">{{ __('frontend.footer.copyright_name') }}</a></span>
                </div>
            </div>
        </div>
    </div>
</footer>
