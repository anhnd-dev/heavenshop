<div class="form-group mb-4">

    <label for="{{ $prefix }}_title">
        {{ __('admin.blog.blog_title') }}
    </label>

    <input type="text" class="form-control" id="{{ $prefix }}_title" name="title"
        placeholder="{{ __('admin.blog.enter_blog_title') }}" required>

</div>

<div class="form-group mb-4">

    <label for="{{ $prefix }}_slug">
        {{ __('admin.common.slug') }}
    </label>

    <input type="text" class="form-control" id="{{ $prefix }}_slug" name="slug"
        placeholder="{{ __('admin.common.enter_slug') }}">

</div>

<div class="form-group mb-4">

    <label for="{{ $prefix }}_description">{{ __('admin.blog.blog_desc') }}</label>

    <textarea class="form-control" id="{{ $prefix }}_description" name="description"></textarea>

</div>

<div class="form-group mb-4">

    <label for="{{ $prefix }}_content">{{ __('admin.blog.blog_count') }}</label>

    <textarea class="form-control" id="{{ $prefix }}_content" name="content"></textarea>

</div>

<div class="form-group mb-4">

    <label for="">{{ __('admin.blog.blog_tags') }}</label>

    @php
        $defaultTags = config('blog.tags');
    @endphp

    <select name="tags[]" multiple class="form-control select2-auto-tokenize">
        @foreach ($defaultTags as $tag)
            <option value="{{ $tag }}">
                {{ $tag }}
            </option>
        @endforeach
    </select>

</div>

<div class="form-group mb-4">

    <label>{{ __('admin.blog.blog_image') }}</label>

    <input type="file" id="input-file-now" class="dropify" accept="image/*" name="image" data-default-file=""
        data-height="300" />

</div>

@if (isset($blog) && $blog->image)
    <div class="mb-3">
        <img src="{{ asset('uploads/blog/' . $blog->image) }}" style="max-width: 200px;">
    </div>
@endif

<div class="form-group mb-4">

    <label for="category_id">{{ __('admin.sidebar.category') }}</label>

    <select name="category_id" id="{{ $prefix }}_category_id" class="default-select form-control wide mb-3">
        <option value="">{{ __('admin.category.select_category') }}</option>

        @foreach ($categories as $category)
            <option value="{{ $category->id }}">
                {{ $category->name }}
            </option>
        @endforeach
    </select>

</div>
