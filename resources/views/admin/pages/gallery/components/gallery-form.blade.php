@php

    $isEdit = $mode === 'edit';

@endphp

<div class="row">

    {{-- FILE --}}
    <div class="col-md-12">

        <div class="form-group mb-4">

            <label>
                Media
            </label>

            <input type="file" class="form-control" name="files[]" id="{{ $prefix }}_files"
                {{ $isEdit ? '' : 'multiple' }}>

            <small class="text-muted">
                Hỗ trợ ảnh và video
            </small>

        </div>

    </div>

    {{-- COLOR --}}
    <div class="col-md-6">

        <div class="form-group mb-4">

            <label>
                Màu sắc
            </label>

            <select name="color_id" id="{{ $prefix }}_color_id" class="form-control">

                <option value="">
                    -- Chọn màu --
                </option>

                @foreach ($colors as $color)
                    <option value="{{ $color->id }}">

                        {{ $color->name }}

                    </option>
                @endforeach

            </select>

        </div>

    </div>

    {{-- SORT --}}
    <div class="col-md-6">

        <div class="form-group mb-4">

            <label>
                Thứ tự hiển thị
            </label>

            <input type="number" class="form-control" name="sort_order" id="{{ $prefix }}_sort_order"
                value="0">

        </div>

    </div>

    {{-- PREVIEW --}}
    <div class="col-md-12">

        <div class="mt-3" id="{{ $prefix }}_preview_wrapper"></div>

    </div>

</div>
