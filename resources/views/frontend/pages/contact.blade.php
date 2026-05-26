@extends('layouts.base')

@php
    $data = $contact ? json_decode($contact->data_value) : (object) [];
@endphp

@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">{{ __('frontend.breadcrumbs.contact') }}</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">{{ __('frontend.breadcrumbs.home') }}</a></li>
                        <li>{{ __('frontend.breadcrumbs.contact') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-0 position-relative overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-6 md-mb-8 text-center text-sm-start"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="alt-font text-dark-gray mb-15px fs-20">
                        <span class="text-highlight">{{ __('frontend.contact.pls_contact') }}<span
                                class="bg-base-color h-8px bottom-0px"></span></span>
                    </div>
                    <h2 class="alt-font text-dark-gray fw-400 ls-minus-1px fs-45">
                        {{ $data->title }}
                    </h2>
                    <div class="row row-cols-1 row-cols-sm-2 mb-10">
                        <div class="col last-paragraph-no-margin xs-mb-20px">
                            <span class="fs-18 fw-600 d-block text-dark-gray">{{ __('frontend.contact.address') }}</span>
                            <p class="w-95 xl-w-100">
                                {{ $data->address }}
                            </p>
                        </div>
                        <div class="col">
                            <span class="fs-18 fw-600 d-block text-dark-gray">{{ __('frontend.contact.phone_text') }}</span>
                            <a href="tel:12345678910">{{ $data->phone_number }}</a><br />
                            <a href="cdn-cgi/l/email-protection.html#ec85828a83ac8883818d8582c28f8381"
                                class="text-decoration-line-bottom text-dark-gray"><span class="__cf_email__"
                                    data-cfemail="f891969e97b89c9795999196d69b9795">{{ $data->email }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 offset-xxl-1 col-lg-6">
                    <div class="outside-box-right-30 position-relative"
                        data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <img src="{{ getImage(imagePath()['contact']['path'] . '/' . $data->map_url) }}" alt />
                        <div
                            class="bg-base-color video-icon-box video-icon-medium feature-box-icon-rounded position-absolute top-100px left-100px mt-10 ms-15 w-40px h-40px rounded-circle d-flex align-items-center justify-content-center">
                            <span>
                                <span class="video-icon">
                                    <span
                                        class="bg-dark-gray w-100 h-100 border-radius-100 text-center d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-location-dot m-0 text-white icon-small"></i>
                                    </span>
                                    <span class="video-icon-sonar">
                                        <span class="video-icon-sonar-bfr bg-red"></span>
                                        <span class="video-icon-sonar-bfr bg-yellow"></span>
                                    </span>
                                </span>
                            </span>
                        </div>
                        <div
                            class="bg-base-color video-icon-box video-icon-medium feature-box-icon-rounded position-absolute bottom-100px start-50 mb-10 ms-7 w-40px h-40px rounded-circle d-flex align-items-center justify-content-center">
                            <span>
                                <span class="video-icon">
                                    <span
                                        class="w-100 h-100 bg-dark-gray border-radius-100 d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-location-dot m-0 text-white icon-small"></i>
                                    </span>
                                    <span class="video-icon-sonar">
                                        <span class="video-icon-sonar-bfr bg-red"></span>
                                        <span class="video-icon-sonar-bfr bg-yellow"></span>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="h-600px md-h-500px sm-h-400px section-dark" data-parallax-background-ratio="0.5"
        style="background-image: url('{{ getImage(imagePath()['contact']['path'] . '/' . $data->image_url) }}"></section>

    <section class="position-relative sm-pt-20px">
        <div class="container overlap-section overlap-section-three-fourth">
            <div class="row row-cols-md-1 justify-content-center">
                <div class="col-xl-10"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 500, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="bg-white pt-8 pb-8 box-shadow-double-large ps-10 pe-10 border-radius-6px sm-pe-5 sm-ps-5">
                        <div class="row mb-2">
                            <div class="col-10">
                                <h2 class="alt-font text-dark-gray ls-minus-2px fs-32">
                                    {{ $data->question }}
                                </h2>
                            </div>
                            <div class="col-2 text-end">
                                <i class="bi bi-send icon-large text-dark-gray animation-float"></i>
                            </div>
                        </div>

                        <form action="" method="post"
                            class="contact-form-style-03">
                            <div class="row justify-content-center">
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail1" class="form-label fw-600 text-dark-gray mb-0">{{ __('frontend.contact.name') }}</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-emoji-smile"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail1" type="text" name="name"
                                            placeholder="{{ __('frontend.contact.enter_name') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail1" class="form-label fw-600 text-dark-gray mb-0">{{ __('frontend.contact.email') }}</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-envelope"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail2" type="email" name="email"
                                            placeholder="{{ __('frontend.contact.enter_email') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail1" class="form-label fw-600 text-dark-gray mb-0">{{ __('frontend.contact.phone') }}</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-telephone"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail3" type="tel" name="phone"
                                            placeholder="{{ __('frontend.contact.enter_phone') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail1"
                                        class="form-label fw-600 text-dark-gray mb-0">{{ __('frontend.contact.subject') }}</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-journals"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control"
                                            id="exampleInputEmail4" type="text" name="subject"
                                            placeholder="{{ __('frontend.contact.enter_subject') }}" />
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="exampleInputEmail1" class="form-label fw-600 text-dark-gray mb-0">{{ __('frontend.contact.message') }}</label>
                                    <div class="position-relative form-group form-textarea mb-0">
                                        <textarea class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control" name="comment"
                                            placeholder="{{ __('frontend.contact.enter_message') }}" rows="4"></textarea>
                                        <span class="form-icon"><i class="bi bi-chat-square-dots"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-0 fs-14 lh-24 text-center text-md-start">
                                        {{ __('frontend.contact.privacy_protected') }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-center text-md-end sm-mt-25px">
                                    <input id="exampleInputEmail5" type="hidden" name="redirect" value />
                                    <button
                                        class="btn btn-very-small btn-dark-gray btn-box-shadow btn-round-edge text-transform-none primary-font submit"
                                        type="submit">
                                        {{ __('frontend.contact.send_message') }}
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div class="form-results mt-20px d-none"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
