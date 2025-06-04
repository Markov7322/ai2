{{-- resources/views/objects/show.blade.php --}}
<x-app-layout>
    <!-- Слот “header”: название объекта -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $object->title }}
            </h2>
            {{-- Если нужно, можно добавить кнопку “Вернуться” --}}
            <a href="{{ route('home') }}"
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Вернуться
            </a>
        </div>
    </x-slot>

    <!-- Основной контент: описание + отзывы + форма для нового отзыва -->
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        {{-- 1) Блок с описанием объекта --}}
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700">{{ $object->description }}</p>

            @if(isset($object->avg_rating) && $object->reviews_count !== null)
                <div class="flex items-center text-sm text-gray-700 mt-4">
                    <span class="font-semibold">Средний рейтинг:</span>
                    <span class="ml-2 text-yellow-500">{{ number_format($object->avg_rating, 1) }} / 5</span>
                    <span class="ml-2 text-gray-500">({{ $object->reviews_count }} отзывов)</span>
                </div>
            @else
                @php
                    $avg = $object->reviews->avg('rating');
                @endphp
                <div class="flex items-center text-sm text-gray-700 mt-4">
                    <span class="font-semibold">Средний рейтинг:</span>
                    <span class="ml-2 text-yellow-500">{{ $avg ? round($avg, 1) : '–' }}</span>
                    <span class="ml-2 text-gray-500">({{ $object->reviews->count() }} отзывов)</span>
                </div>
            @endif
        </div>

        {{-- 2) Список отзывов --}}
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Отзывы ({{ $object->reviews->count() }})</h3>

            @forelse ($object->reviews as $review)
                <div class="bg-white shadow rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-1">
                        {{-- Имя автора --}}
                        <span class="font-medium text-gray-800">
                            {{ $review->user->name ?? 'Аноним' }}
                        </span>
                        {{-- Оценка --}}
                        <span class="text-yellow-500 font-semibold">
                            {{ $review->rating }} ★
                        </span>
                    </div>
                    {{-- Дата публикации --}}
                    <div class="text-gray-500 text-sm mb-2">
                        {{ $review->created_at->format('d.m.Y H:i') }}
                    </div>
                    {{-- Текст отзыва --}}
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($review->content)) !!}
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Пока нет ни одного отзыва. Станьте первым!</p>
            @endforelse
        </div>

        {{-- 3) Форма “Добавить отзыв” (только для авторизованных) --}}
        <div>
            @auth
                <div class="bg-white shadow rounded-lg p-6">
                    <h4 class="text-lg font-semibold mb-4">Добавить новый отзыв</h4>

                    {{-- Флеш-сообщение об успешной отправке --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-300 text-green-700 rounded px-4 py-2 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Ошибки валидации --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded p-4 mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('objects.reviews.store', ['slug' => $object->slug]) }}" method="POST">
                        @csrf

                        {{-- Поле “Оценка” --}}
                        <div class="mb-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">
                                Ваша оценка <span class="text-red-500">*</span>
                            </label>
                            <select id="rating" name="rating" required
                                    class="block w-24 px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm
                                           focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">–</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} звезде{{ $i === 1 ? '' : 's' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Поле “Текст отзыва” --}}
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                Текст отзыва <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="5" required
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                                             focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Поделитесь своим опытом…">{{ old('content') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent 
                                           shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 
                                           hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                           focus:ring-indigo-500">
                                Отправить отзыв
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Если гость — подсказка залогиниться --}}
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded px-4 py-3 flex items-center">
                    <svg class="h-6 w-6 flex-shrink-0 text-yellow-500" xmlns="http://www.w3.org/2000/svg" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 
                                 100 20 10 10 0 000-20z" />
                    </svg>
                    <p class="ml-3">
                        <span class="font-medium">Войдите, чтобы оставить отзыв.</span>
                        <a href="{{ route('login') }}" class="underline text-indigo-600 hover:text-indigo-800 ml-1">
                            Войти
                        </a>
                        или
                        <a href="{{ route('register') }}" class="underline text-indigo-600 hover:text-indigo-800 ml-1">
                            Зарегистрироваться
                        </a>
                    </p>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>
