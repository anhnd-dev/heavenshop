@extends('layouts.base')

@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <span class="fs-18 mb-30px d-inline-block sm-mb-20px">By
                        <a href="#" class="text-dark-gray text-decoration-line-bottom">{{ $blog->admin->user_name }}</a>
                        in
                        <a href="#" class="text-dark-gray text-decoration-line-bottom">{{ $blog->category->name }}</a>
                        on {{ (new DateTime($blog->created_at))->format('j F Y') }}</span>
                    <h1 class="alt-font fw-600 text-dark-gray ls-minus-2px mb-0">
                        {{ $blog->title }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="py-0 ps-13 pe-13 lg-ps-4 lg-pe-4 sm-px-0">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <img src="{{ asset('uploads/blog/'. $blog->image) }}" class="w-100" alt />
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 text-center sm-mb-30px">
                            <div class="pb-20px">
                                <img src="images/blog-single-creative-avtar.jpg" class="rounded-circle w-130px mb-20px"
                                    alt />
                                <span class="d-block lh-22">Words by</span>
                                <a href="blog-grid.html" class="text-dark-gray alt-font fw-500 text-uppercase">{{ $blog->admin->full_name }}</a>
                            </div>
                            <div class="h-3px w-100 bg-dark-gray mb-20px"></div>
                            <ul class="d-flex list-unstyled justify-content-center">
                                <li class="me-25px">
                                    <a href="#" class="text-uppercase alt-font fs-13"><i
                                            class="feather icon-feather-message-circle me-5px icon-small align-middle"></i>Comment</a>
                                </li>
                                <li>
                                    <a href="#" class="text-uppercase alt-font fs-13"><i
                                            class="feather icon-feather-heart me-5px icon-small align-middle"></i>Like</a>
                                </li>
                            </ul>
                        </div>
                        <div class="offset-lg-1 col-md-8 last-paragraph-no-margin text-center text-md-start">
                            {!! htmlspecialchars_decode($blog->description) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="py-0 ps-13 pe-13 lg-ps-4 lg-pe-4 sm-px-0">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <img src="images/blog-single-creative-02.jpg" class="w-100" alt />
                </div>
            </div>
        </div>
    </section> --}}

    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 text-center text-lg-end sm-mb-30px">
                            <img src="images/blog-single-creative-08.png" class="mt-10px" alt />
                        </div>
                        <div class="offset-lg-1 col-md-8 last-paragraph-no-margin text-center text-md-start">
                            <div class="pb-35px text-center text-md-start">
                                <h5 class="text-dark-gray fw-500 w-90 md-w-100 alt-font">
                                    The artist's world is limitless. It can be found any where
                                    far from where he lives or a few feet away.
                                </h5>
                                <span class="fw-600 text-dark-gray d-block lh-20 text-uppercase">Nicholas Robinson</span>
                                <span class="d-block text-uppercase fs-13">Founder of photos</span>
                            </div>
                            <div class="h-3px w-100 bg-dark-gray mb-35px"></div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip commodo consequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum dolore
                                eu fugiat nulla pariatur.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-0 ps-13 pe-13 lg-ps-4 lg-pe-4 sm-px-0">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <img src="images/blog-single-creative-07.jpg" class="w-100" alt />
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 text-center text-md-start">
                            <h5 class="alt-font fw-600 text-dark-gray">
                                Look for opportunities to diversify.
                            </h5>
                        </div>
                        <div class="offset-lg-1 col-md-8 last-paragraph-no-margin text-center text-md-start">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip commodo consequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum dolore
                                eu fugiat nulla pariatur.
                            </p>
                            <p>
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                accusantium doloremque laudantium
                                <span class="text-dark-gray text-decoration-line-bottom">totam rem aperiam</span>
                                eaque ipsa quae ab illo inventore veritatis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="half-section pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row mb-30px">
                        <div class="tag-cloud col-md-9 text-center text-md-start sm-mb-15px">
                            @php
                                $tags = explode(',', $blog->tags);
                            @endphp

                            @foreach ($tags as $tag)
                                <a href="magazine.html">{{ $tag }}</a>
                            @endforeach
                        </div>
                        <div class="tag-cloud col-md-3 text-uppercase text-center text-md-end">
                            <a class="likes-count fw-500 mx-0" href="#"><i
                                    class="fa-regular fa-heart text-red me-10px"></i><span
                                    class="text-dark-gray text-dark-gray-hover">05 Likes</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-very-light-gray">
        <div class="container">
            <div class="row mb-6 xs-mb-7">
                <div class="col-12 text-center">
                    <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">
                        Related
                        <span class="text-highlight fw-600">posts<span
                                class="bg-base-color h-5px bottom-2px"></span></span>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul
                        class="blog-classic blog-wrapper grid grid-3col xl-grid-3col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large">
                        <li class="grid-sizer"></li>

                        @foreach ($relatedBlogs as $relatedBlog)
                            <li class="grid-item">
                                <div class="card bg-transparent border-0 h-100">
                                    <div class="blog-image position-relative overflow-hidden">
                                        <a href="{{ route('blog.details', ['slug' => $relatedBlog->slug]) }}"><img src="{{ asset('uploads/blog/' . $blog->image) }}"
                                                alt /></a>
                                    </div>
                                    <div class="card-body px-0 pt-30px pb-30px sm-pb-15px">
                                        <span class="mb-5px d-block">By
                                            <a href="#" class="text-dark-gray fw-500 categories-text">{{ $relatedBlog->admin->full_name }}</a><a href="#" class="blog-date">26 December 2023</a></span>
                                        <a href="{{ route('blog.details', ['slug' => $relatedBlog->slug]) }}"
                                            class="alt-font card-title fs-20 lh-30 fw-500 text-dark-gray d-inline-block w-75 xl-w-85 lg-w-100">{{ $relatedBlog->title }}</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center mb-2">
                    <h6 class="alt-font text-dark-gray fw-500">4 Comments</h6>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <ul class="blog-comment">
                        <li>
                            <div class="d-block d-md-flex w-100 align-items-center align-items-md-start">
                                <div class="w-90px sm-w-65px sm-mb-10px">
                                    <img src="images/avtar-18.jpg" class="rounded-circle" alt />
                                </div>
                                <div class="w-100 ps-30px last-paragraph-no-margin sm-ps-0">
                                    <a href="#" class="text-dark-gray fw-500">Herman Miller</a>
                                    <a href="#comments" class="btn-reply text-uppercase section-link">Reply</a>
                                    <div class="fs-14 lh-24 mb-10px">17 July 2020, 6:05 PM</div>
                                    <p class="w-85 sm-w-100">
                                        Lorem ipsum is simply dummy text of the printing and
                                        typesetting industry. Lorem ipsum has been the industry's
                                        standard dummy text ever since the make book.
                                    </p>
                                </div>
                            </div>
                            <ul class="child-comment">
                                <li>
                                    <div class="d-block d-md-flex w-100 align-items-center align-items-md-start">
                                        <div class="w-90px sm-w-65px sm-mb-10px">
                                            <img src="images/avtar-19.jpg" class="rounded-circle" alt />
                                        </div>
                                        <div class="w-100 ps-30px last-paragraph-no-margin sm-ps-0">
                                            <a href="#" class="text-dark-gray fw-500">Wilbur Haddock</a>
                                            <a href="#comments" class="btn-reply text-uppercase section-link">Reply</a>
                                            <div class="fs-14 lh-24 mb-10px">
                                                18 July 2020, 10:19 PM
                                            </div>
                                            <p class="w-85 sm-w-100">
                                                Lorem ipsum is simply dummy text of the printing and
                                                typesetting industry. Lorem ipsum has been the
                                                industry's standard dummy text ever since.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div
                                        class="d-block d-md-flex w-100 align-items-center align-items-md-start border-radius-5px p-50px md-p-30px sm-p-20px bg-very-light-gray">
                                        <div class="w-90px sm-w-65px sm-mb-10px">
                                            <img src="images/avtar-17.jpg" class="rounded-circle" alt />
                                        </div>
                                        <div class="w-100 ps-30px last-paragraph-no-margin sm-ps-0">
                                            <a href="#" class="text-dark-gray fw-500">Colene Landin</a>
                                            <a href="#comments" class="btn-reply text-uppercase section-link">Reply</a>
                                            <div class="fs-14 lh-24 mb-10px">
                                                18 July 2020, 12:39 PM
                                            </div>
                                            <p class="w-85 sm-w-100">
                                                Lorem ipsum is simply dummy text of the printing and
                                                typesetting industry. Ipsum has been the industry's
                                                standard dummy text.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="d-block d-md-flex w-100 align-items-center align-items-md-start">
                                <div class="w-90px sm-w-65px sm-mb-10px">
                                    <img src="images/avtar-18.jpg" class="rounded-circle" alt />
                                </div>
                                <div class="w-100 ps-30px last-paragraph-no-margin sm-ps-0">
                                    <a href="#" class="text-dark-gray fw-500">Jennifer Freeman</a>
                                    <a href="#comments" class="btn-reply text-uppercase section-link">Reply</a>
                                    <div class="fs-14 lh-24 mb-10px">19 July 2020, 8:25 PM</div>
                                    <p class="w-85 sm-w-100">
                                        Lorem ipsum is simply dummy text of the printing and
                                        typesetting industry. Lorem ipsum has been the industry's
                                        standard dummy text ever since the make a type specimen
                                        book.
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="comments" class="pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 mb-3 sm-mb-6">
                    <h6 class="alt-font text-dark-gray fw-500 mb-5px">
                        Write a comment
                    </h6>
                    <div class="mb-5px">
                        Your email address will not be published. Required fields are
                        marked *
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <form action="https://craftohtml.themezaa.com/email-templates/contact-form.php" method="post"
                        class="row contact-form-style-02">
                        <div class="col-md-6 mb-30px">
                            <input class="input-name border-radius-4px form-control required" type="text"
                                name="name" placeholder="Enter your name*" />
                        </div>
                        <div class="col-md-6 mb-30px">
                            <input class="border-radius-4px form-control required" type="email" name="email"
                                placeholder="Enter your email address*" />
                        </div>
                        <div class="col-md-12 mb-30px">
                            <textarea class="border-radius-4px form-control" cols="40" rows="4" name="comment"
                                placeholder="Your message"></textarea>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="redirect" value />
                            <button class="btn btn-dark-gray btn-small btn-round-edge submit" type="submit">
                                Post Comment
                            </button>
                            <div class="form-results mt-20px d-none"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
