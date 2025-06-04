{{-- resources/views/home.blade.php --}}
<x-app-layout>
    <!-- Слот “header” попадёт в @isset($header) внутри layouts/app.blade.php -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Категории и объекты') }}
        </h2>
    </x-slot>

    <!-- Основной контент страницы -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Если коллекция $categories пустая, покажем предупреждение --}}
        @if($categories->isEmpty())
            <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded px-4 py-3 mb-6">
                <p>Пока нет ни одной категории. Обратитесь к администратору.</p>
            </div>
        @endif

        {{-- Перебираем каждую категорию --}}
        @foreach ($categories as $category)
            <div class="mb-8">
                {{-- Заголовок категории --}}
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $category->title }}</h3>

                @if ($category->objects->isEmpty())
                    {{-- Если у категории нет объектов --}}
                    <p class="text-gray-500 ml-4">В данной категории пока нет объектов.</p>
                @else
                    {{-- Сетка карточек объектов --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($category->objects as $object)
                            <div class="bg-white shadow rounded-lg overflow-hidden">
                                <div class="p-4">
                                    {{-- Название объекта --}}
                                    <h4 class="text-lg font-medium text-gray-800">
                                        <a href="{{ route('objects.show', ['slug' => $object->slug]) }}"
                                           class="text-indigo-600 hover:text-indigo-800">
                                            {{ $object->title }}
                                        </a>
                                    </h4>

                                    {{-- Краткое описание --}}
                                    <p class="text-gray-600 text-sm mt-2">
                                        {{ \Illuminate\Support\Str::limit($object->description, 100) }}
                                    </p>

                                    {{-- Средний рейтинг (если хранится в базе) --}}
                                    @if(isset($object->avg_rating) && $object->reviews_count !== null)
                                        <div class="flex items-center text-sm text-gray-700 mt-2">
                                            <span class="font-semibold">Рейтинг:</span>
                                            <span class="ml-2 text-yellow-500">
                                                {{ number_format($object->avg_rating, 1) }} / 5
                                            </span>
                                            <span class="ml-2 text-gray-500">({{ $object->reviews_count }} отзывов)</span>
                                        </div>
                                    @endif

                                    {{-- Кнопка “Подробнее” --}}
                                    <div class="mt-4">
                                        <a href="{{ route('objects.show', ['slug' => $object->slug]) }}"
                                           class="inline-block px-3 py-1 bg-indigo-600 text-white 
                                                  text-xs font-semibold rounded hover:bg-indigo-700">
                                            Подробнее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
