<section class="pb-3 ps-7 pe-7 lg-ps-3 lg-pe-3 xs-px-0">
    <div class="container">
        <div class="row mb-4 xs-mb-7">
            <div class="col-12 text-center">
                <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">
                    Fashion
                    <span class="text-highlight fw-600">magazine<span
                            class="bg-base-color h-5px bottom-2px"></span></span>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="blog-classic blog-wrapper grid-loading grid grid-4col xl-grid-4col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large"
                    data-anime='{ "el": "childs", "translateY": [15, 0], "translateX": [-15, 0], "opacity": [0,1], "duration": 500, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <li class="grid-sizer"></li>

                    @foreach ($blogs as $blog)
                        <li class="grid-item">
                            <div class="card bg-transparent border-0 h-100">
                                <div class="blog-image position-relative overflow-hidden">
                                    <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}"><img
                                            src="{{ asset('uploads/blog/' . $blog->image) }}" alt /></a>
                                </div>
                                <div class="card-body px-0 pt-30px pb-30px sm-pb-15px">
                                    <span class="mb-5px d-block">By
                                        <a href="#"
                                            class="text-dark-gray fw-500 categories-text">{{ $blog->admin->full_name }}</a><a
                                            href="#" class="blog-date">{{ (new DateTime($blog->created_at))->format('j F Y') }}</a></span>
                                    <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}"
                                        class="alt-font card-title fs-20 lh-30 fw-500 text-dark-gray d-inline-block w-75 xl-w-85 lg-w-100">{{ $blog->title }}</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
