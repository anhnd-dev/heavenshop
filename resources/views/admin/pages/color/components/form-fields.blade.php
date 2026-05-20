<div class="form-group mb-4">

    <label for="{{ $prefix }}_name">
        {{ __('admin.color.color_name') }}
    </label>

    <input type="text" class="form-control" id="{{ $prefix }}_name" name="name"
        placeholder="{{ __('admin.color.enter_color_name') }}" required>

</div>

<div class="form-group mb-4">

    <label for="{{ $prefix }}_code">
        {{ __('admin.color.color_code') }}
    </label>

    <input type="color" class="form-control" id="{{ $prefix }}_code" name="code"
        placeholder="{{ __('admin.color.enter_color_code') }}">

</div>
