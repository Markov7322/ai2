<div class="ml-{{ $level * 4 }} mb-4">
    <h4 class="text-xl font-semibold text-gray-800">{{ $category->title }}</h4>
    @if($category->children->isNotEmpty())
        @foreach($category->children as $child)
            @include('home.category-tree', ['category' => $child, 'level' => $level + 1])
        @endforeach
    @endif
    @if($category->objects->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-2">
            @foreach ($category->objects as $object)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h5 class="text-lg font-medium">
                            <a href="{{ route('objects.show', $object->slug) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $object->title }}
                            </a>
                        </h5>
                        <p class="text-gray-600 text-sm mt-2">{{ \Illuminate\Support\Str::limit($object->description, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
