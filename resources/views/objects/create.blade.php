<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Создать объект и отзыв') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded p-4 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('objects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Категория</label>
                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    @foreach ($categories as $category)
                        @include('objects.partials.option', ['category' => $category, 'level' => 0])
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Название объекта</label>
                <input type="text" name="title" class="mt-1 block w-full border-gray-300 rounded-md" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Описание</label>
                <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md" rows="4"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Изображение объекта</label>
                <input type="file" name="object_image" accept="image/*">
            </div>

            <hr class="my-6">

            <h3 class="text-lg font-semibold">Ваш отзыв</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700">Оценка</label>
                <select name="rating" class="mt-1 block w-24 border-gray-300 rounded-md" required>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Текст отзыва</label>
                <textarea name="review_content" rows="5" class="mt-1 block w-full border-gray-300 rounded-md" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Плюсы</label>
                <textarea name="pros" rows="2" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Минусы</label>
                <textarea name="cons" rows="2" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Изображение к отзыву</label>
                <input type="file" name="review_image" accept="image/*">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Создать</button>
            </div>
        </form>
    </div>
</x-app-layout>
