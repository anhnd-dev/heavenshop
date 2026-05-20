<option value="{{ $category->id }}" {{ $category->childrenRecursive->count() ? 'disabled' : '' }}>

    {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}

    {{ $level > 0 ? '└── ' : '' }}

    {{ $category->name }}

</option>

@foreach ($category->childrenRecursive as $child)
    @include('admin.pages.product.components.category-option', [
        'category' => $child,
        'level' => $level + 1,
    ])
@endforeach
