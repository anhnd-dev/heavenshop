<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-4">

            <label for="{{ $prefix }}_name">
                {{ __('admin.category.category_name') }}
            </label>

            <input type="text" class="form-control" id="{{ $prefix }}_name" name="name"
                placeholder="{{ __('admin.category.enter_category_name') }}" required>

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_slug">
                {{ __('admin.common.slug') }}
            </label>

            <input type="text" class="form-control" id="{{ $prefix }}_slug" name="slug"
                placeholder="{{ __('admin.common.enter_slug') }}" readonly>

        </div>

        <div class="form-group mb-4">

            <label>
                {{ __('admin.category.category_image') }}
            </label>

            <input type="file" name="image" class="form-control">

        </div>

        @if ($prefix === 'edit')
            <div class="mb-4">
                <img id="{{ $prefix }}_image_preview" class="img-fluid" width="120">
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <div class="form-group mb-4">

            <label>
                {{ __('admin.category.category_type') }}
            </label>

            <select name="type" id="{{ $prefix }}_type" class="form-control">

                <option value="">
                    {{ __('admin.category.select_category_type') }}
                </option>

                <option value="product">
                    {{ __('admin.sidebar.product') }}
                </option>

                <option value="blog">
                    {{ __('admin.sidebar.blog') }}
                </option>

            </select>

        </div>


        <div class="form-group mb-4">

            <label>
                {{ __('admin.category.parent_category') }}
            </label>

            <select name="parent_id" id="{{ $prefix }}_parent_id" class="form-control">

                <option value="">
                    Root
                </option>

            </select>

        </div>
    </div>
</div>
