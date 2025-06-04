<div class="ml-{{ $level * 4 }} mb-4">
    <h4 class="text-xl font-semibold text-gray-800">
        <a href="{{ route('categories.show', $category->slug) }}" class="hover:underline">
            {{ $category->title }}
        </a>
    </h4>
    @if($category->children->isNotEmpty())
        @foreach($category->children as $child)
            @include('home.category-tree', ['category' => $child, 'level' => $level + 1])
        @endforeach
    @endif
</div>
