<section class="pt-0 pb-0 ps-7 pe-7 lg-ps-3 lg-pe-3 xs-p-0">
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-2 row-cols-md-2"
            data-anime='{ "el": "childs", "translateY": [-15, 0], "perspective": [1200,1200], "scale": [1.1, 1], "rotateX": [50, 0], "opacity": [0,1], "duration": 400, "delay": 100, "staggervalue": 200, "easing": "easeOutQuad" }'>

            @foreach ($categories as $category)
                <div class="col categories-style-02 lg-mb-30px">
                    <div class="categories-box">
                        <a href="shop.html">
                            <img class="sm-w-100" src="{{ asset('uploads/category/' . $category->image) }}" alt />
                        </a>
                        <div
                            class="border-color-transparent-dark-very-light border alt-font fw-500 text-dark-gray text-uppercase ps-15px pe-15px fs-11 lh-26 border-radius-100px d-inline-block position-absolute right-20px top-20px">
                            {{-- {{ $category->Subcategories->count() }} items --}}
                        </div>
                        <div class="absolute-bottom-center bottom-40px md-bottom-25px">
                            <a href="shop.html"
                                class="btn btn-white btn-switch-text btn-round-edge btn-box-shadow fs-18 text-uppercase-inherit p-5 min-w-150px">
                                <span>
                                    <span class="btn-double-text ls-0px"
                                        data-text="{{ $category->name }}">{{ $category->name }}</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
