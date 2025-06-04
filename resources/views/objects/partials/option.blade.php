<option value="{{ $category->id }}" @if(old('category_id')===$category->id) selected @endif>
    {{ str_repeat('— ', $level) }}{{ $category->title }}
</option>
@if ($category->children)
    @foreach ($category->children as $child)
        @include('objects.partials.option', ['category' => $child, 'level' => $level+1])
    @endforeach
@endif
