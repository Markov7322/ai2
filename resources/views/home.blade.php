{{-- resources/views/home.blade.php --}}
<x-app-layout>
    {{-- Слот "header" попадёт в @isset($header) внутри layouts/app.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Категории') }}
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

        {{-- Древовидный вывод категорий --}}
        @foreach ($categories as $category)
            @include('home.category-tree', ['category' => $category, 'level' => 0])
        @endforeach
    </div>
</x-app-layout>
