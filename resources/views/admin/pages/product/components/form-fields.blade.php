@php

    $isEdit = $mode === 'edit';

@endphp

<div class="row">

    {{-- =========================================================
    | LEFT
    ========================================================= --}}
    <div class="col-md-8">

        {{-- NAME --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.product_name') }}
            </label>

            <input type="text" class="form-control" name="name" id="{{ $prefix }}_name" required>

        </div>

        {{-- SLUG --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.common.slug') }}
            </label>

            <input type="text" class="form-control" name="slug" id="{{ $prefix }}_slug">

        </div>

        {{-- DESCRIPTION --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.product_description') }}
            </label>

            <textarea class="form-control" name="description" id="{{ $prefix }}_description" rows="4"></textarea>

        </div>

        {{-- CONTENT --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.product_content') }}
            </label>

            <textarea class="form-control" name="content" id="{{ $prefix }}_content" rows="8"></textarea>

        </div>

    </div>

    {{-- =========================================================
    | RIGHT
    ========================================================= --}}
    <div class="col-md-4">

        {{-- CATEGORY --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.category_name') }}
            </label>

            <select name="category_id" id="{{ $prefix }}_category_id" class="form-control">

                <option value="">
                    -- Chọn danh mục --
                </option>

                @foreach ($categories as $category)
                    @include('admin.pages.product.components.category-option', [
                        'category' => $category,
                        'level' => 0,
                    ])
                @endforeach
                {{-- @foreach ($categories as $parent)
                    @if ($parent->children->count())
                        <optgroup label="{{ $parent->name }}">

                            @foreach ($parent->children as $child)
                                <option value="{{ $child->id }}">
                                    {{ $child->name }}
                                </option>
                            @endforeach

                        </optgroup>
                    @else
                        <option value="{{ $parent->id }}">
                            {{ $parent->name }}
                        </option>
                    @endif
                @endforeach --}}

            </select>

        </div>

        {{-- BRAND --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.brand_name') }}
            </label>

            <select name="brand_id" id="{{ $prefix }}_brand_id" class="form-control">

                <option value="">
                    -- Chọn thương hiệu --
                </option>

                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">
                        {{ $brand->name }}
                    </option>
                @endforeach

            </select>

        </div>

        {{-- IMAGE --}}
        <div class="form-group mb-4">

            <label>
                {{ __('admin.product.product_image') }}
            </label>

            <input type="file" class="form-control" name="image" id="{{ $prefix }}_image">

            <div class="mt-3 text-center">

                <img id="{{ $prefix }}_image_preview" class="img-fluid rounded border">

            </div>

        </div>

        {{-- TAGS --}}
        <div class="form-group mb-4">

            <label>Tags</label>

            <input type="text" class="form-control" name="tags" id="{{ $prefix }}_tags"
                data-role="tagsinput">

        </div>

        {{-- FEATURED --}}
        <div class="form-group mb-4">

            <div class="custom-control custom-switch">

                <input type="checkbox" class="custom-control-input" id="{{ $prefix }}_is_featured"
                    name="is_featured" value="1">

                <label class="custom-control-label" for="{{ $prefix }}_is_featured">

                    Sản phẩm nổi bật

                </label>

            </div>

        </div>

        {{-- STATUS --}}
        <div class="form-group mb-4">

            <label>Trạng thái</label>

            <select name="is_active" id="{{ $prefix }}_is_active" class="form-control">

                <option value="1">
                    Hiển thị
                </option>

                <option value="0">
                    Ẩn
                </option>

            </select>

        </div>

    </div>

</div>

<hr>

{{-- =========================================================
| VARIANTS
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <h5 class="mb-0">
        Danh sách biến thể
    </h5>

    <button type="button" class="btn btn-primary btn-sm" id="{{ $prefix }}VariantBtn">

        <i class="fas fa-plus"></i>

        Thêm biến thể

    </button>

</div>

<div id="{{ $prefix }}_variant_wrapper"></div>
