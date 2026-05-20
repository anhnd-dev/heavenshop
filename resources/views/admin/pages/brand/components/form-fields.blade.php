<div class="form-group mb-4">

    <label for="{{ $prefix }}_name">
        {{ __('admin.brand.brand_name') }}
    </label>

    <input type="text" class="form-control" id="{{ $prefix }}_name" name="name"
        placeholder="{{ __('admin.brand.enter_brand_name') }}" required>

</div>

<div class="form-group mb-4">

    <label for="{{ $prefix }}_slug">
        {{ __('admin.common.slug') }}
    </label>

    <input type="text" class="form-control" id="{{ $prefix }}_slug" name="slug"
        placeholder="{{ __('admin.common.enter_slug') }}">

</div>

<div class="form-group mb-4">

    <label>
        {{ __('admin.brand.brand_image') }}
    </label>

    <input type="file" name="image" class="form-control">

</div>

@if ($prefix === 'edit')
    <div class="mb-4" id="image"></div>
@endif
