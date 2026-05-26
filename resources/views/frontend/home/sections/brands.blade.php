<section class="half-section border-bottom border-color-extra-medium-gray">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-5 row-cols-sm-3 position-relative justify-content-center"
            data-anime='{ "el": "childs", "translateY": [-15, 0], "scale": [0.8, 1], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
            @foreach ($brands as $brand)
                <div class="col text-center sm-mb-30px">
                    <a href="#">
                        <img src="{{ asset('uploads/brand/'.$brand->image) }}" class="h-30px" alt="{{ $brand->name }}" />
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
