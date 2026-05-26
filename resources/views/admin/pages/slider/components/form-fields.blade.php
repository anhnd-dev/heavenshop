<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-4">

            <label for="{{ $prefix }}_title">
                {{ __('admin.slider.title') }}
            </label>

            <input type="text" class="form-control" id="{{ $prefix }}_title" name="title"
                placeholder="{{ __('admin.slider.enter_title') }}">

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_subtitle">
                {{ __('admin.slider.subtitle') }}
            </label>

            <textarea class="form-control" id="{{ $prefix }}_subtitle" name="subtitle" rows="3"
                placeholder="{{ __('admin.slider.enter_subtitle') }}"></textarea>

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_url">
                {{ __('admin.slider.url') }}
            </label>

            <input type="text" class="form-control" id="{{ $prefix }}_url" name="url"
                placeholder="{{ __('admin.slider.enter_url') }}">

        </div>

        <div class="form-group mb-4">

            <label>
                {{ __('admin.slider.image') }}
            </label>

            <input type="file" name="image" class="form-control" accept="image/*">

        </div>

        @if ($prefix === 'edit')
            <div class="mb-4">

                <img id="{{ $prefix }}_image_preview" class="img-fluid rounded border" width="150">

            </div>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">

            <label for="{{ $prefix }}_position">
                {{ __('admin.slider.position') }}
            </label>

            <select class="form-control" id="{{ $prefix }}_position" name="position" required>

                <option value="">
                    {{ __('admin.slider.select_position') }}
                </option>

                @foreach (\App\Models\Slider::POSITIONS as $key => $value)
                    <option value="{{ $key }}">
                        {{ $value }}
                    </option>
                @endforeach

            </select>

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_sort_order">
                {{ __('admin.slider.sort_order') }}
            </label>

            <input type="number" class="form-control" id="{{ $prefix }}_sort_order" name="sort_order"
                min="0" value="0">

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_start_at">
                {{ __('admin.slider.start_at') }}
            </label>

            <input type="datetime-local" class="form-control" id="{{ $prefix }}_start_at" name="start_at">

        </div>

        <div class="form-group mb-4">

            <label for="{{ $prefix }}_end_at">
                {{ __('admin.slider.end_at') }}
            </label>

            <input type="datetime-local" class="form-control" id="{{ $prefix }}_end_at" name="end_at">

        </div>



    </div>
</div>
