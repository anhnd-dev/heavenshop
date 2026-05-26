<div class="collection-result-wrapper">

    {{-- LEFT --}}
    <div class="active-filters">

        {{-- BRANDS --}}
        @if ($q_brands)
            @foreach ($brands->whereIn('id', explode(',', $q_brands)) as $brand)
                <span class="active-filter-item">

                    {{ $brand->name }}

                    <button type="button" class="remove-filter" data-type="brands" data-id="{{ $brand->id }}">
                        ×
                    </button>

                </span>
            @endforeach
        @endif

        {{-- COLORS --}}
        @if ($q_colors)
            @foreach ($colors->whereIn('id', explode(',', $q_colors)) as $color)
                <span class="active-filter-item">

                    {{ $color->name }}

                    <button type="button" class="remove-filter" data-type="colors" data-id="{{ $color->id }}">
                        ×
                    </button>

                </span>
            @endforeach
        @endif

        {{-- SIZES --}}
        @if ($q_sizes)
            @foreach ($sizes->whereIn('id', explode(',', $q_sizes)) as $size)
                <span class="active-filter-item">

                    {{ $size->name }}

                    <button type="button" class="remove-filter" data-type="sizes" data-id="{{ $size->id }}">
                        ×
                    </button>

                </span>
            @endforeach
        @endif

        {{-- CLEAR --}}
        @if ($q_brands || $q_colors || $q_sizes)
            <button type="button" class="clear-all-filters">
                Xóa lọc
            </button>
        @endif

    </div>

    {{-- RIGHT --}}
    <div class="select-options">

        <div class="page-view-filter">

            <select class="form-select" id="orderby">

                <option value="1" {{ $order == 1 ? 'selected' : '' }}>
                    Newest
                </option>

                <option value="2" {{ $order == 2 ? 'selected' : '' }}>
                    Oldest
                </option>

                <option value="3" {{ $order == 3 ? 'selected' : '' }}>
                    Price: Low To High
                </option>

                <option value="4" {{ $order == 4 ? 'selected' : '' }}>
                    Price: High To Low
                </option>

            </select>

        </div>

        <div>

            <select class="form-select" id="pagesize">

                <option value="12" {{ $psize == 12 ? 'selected' : '' }}>
                    12 Products
                </option>

                <option value="24" {{ $psize == 24 ? 'selected' : '' }}>
                    24 Products
                </option>

                <option value="48" {{ $psize == 48 ? 'selected' : '' }}>
                    48 Products
                </option>

            </select>

        </div>

    </div>

</div>
