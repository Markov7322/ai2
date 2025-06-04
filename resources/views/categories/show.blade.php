<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->title }}
            </h2>
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Вернуться
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        @if($category->children->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Подкатегории</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($category->children as $child)
                        <li>
                            <a href="{{ route('categories.show', $child->slug) }}" class="text-indigo-600 hover:underline">
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Отзывы ({{ $category->reviews->count() }})</h3>

                @forelse ($category->reviews as $review)
                    <div id="review-{{ $review->id }}" class="bg-white shadow rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center mb-1">
                            @if($review->user)
                                <a href="{{ route('users.show', $review->user) }}" class="font-medium text-gray-800 hover:underline">
                                    {{ $review->user->name }}
                                </a>
                            @else
                                <span class="font-medium text-gray-800">Аноним</span>
                            @endif
                            <span class="text-yellow-500 font-semibold">
                                {{ $review->rating }} ★
                            </span>
                        </div>
                        <div class="text-gray-500 text-sm mb-2">
                            {{ $review->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($review->content)) !!}
                        </div>
                        @if($review->image_path)
                            <div class="mt-2">
                                <img src="{{ Storage::url($review->image_path) }}" alt="image" class="max-w-xs rounded">
                            </div>
                        @endif
                        <div class="mt-2 flex items-center text-sm">
                            <form action="{{ route('reviews.react', $review) }}" method="POST" class="mr-2">
                                @csrf
                                <input type="hidden" name="type" value="like">
                                <button type="submit" class="text-green-600 hover:underline">
                                    👍 {{ $review->reactions->where('type', 'like')->count() }}
                                </button>
                            </form>
                            <form action="{{ route('reviews.react', $review) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="dislike">
                                <button type="submit" class="text-red-600 hover:underline">
                                    👎 {{ $review->reactions->where('type', 'dislike')->count() }}
                                </button>
                            </form>
                        </div>

                        {{-- Comments --}}
                        <div class="mt-4 space-y-4">
                            @foreach ($review->comments->where('parent_id', null) as $comment)
                                <div id="comment-{{ $comment->id }}" class="border-t pt-2">
                                    <div class="text-sm text-gray-700 flex justify-between">
                                        <span>{{ $comment->user->name }}</span>
                                        <span class="text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1">{{ $comment->content }}</p>
                                    @foreach ($comment->replies as $reply)
                                        <div id="comment-{{ $reply->id }}" class="ml-4 mt-2 border-l pl-2">
                                            <div class="text-sm text-gray-700 flex justify-between">
                                                <span>{{ $reply->user->name }}</span>
                                                <span class="text-gray-500">{{ $reply->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <p class="mt-1">{{ $reply->content }}</p>
                                        </div>
                                    @endforeach

                                    @auth
                                        <form action="{{ route('reviews.comments.store', $review) }}" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" class="w-full border rounded mb-1" rows="2" placeholder="Ваш ответ..."></textarea>
                                            <button type="submit" class="text-sm text-indigo-600">Ответить</button>
                                        </form>
                                    @endauth
                                </div>
                            @endforeach

                            @auth
                                <form action="{{ route('reviews.comments.store', $review) }}" method="POST" class="border-t pt-2">
                                    @csrf
                                    <textarea name="content" class="w-full border rounded mb-1" rows="2" placeholder="Ваш комментарий..."></textarea>
                                    <button type="submit" class="text-sm text-indigo-600">Отправить</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Пока нет ни одного отзыва. Станьте первым!</p>
                @endforelse
            </div>

            <div>
                @auth
                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4">Добавить новый отзыв</h4>
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-300 text-green-700 rounded px-4 py-2 mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded p-4 mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('categories.reviews.store', ['slug' => $category->slug]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">
                                    Ваша оценка <span class="text-red-500">*</span>
                                </label>
                                <select id="rating" name="rating" required class="block w-24 px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">–</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} звезде{{ $i === 1 ? '' : 's' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                    Текст отзыва <span class="text-red-500">*</span>
                                </label>
                                <textarea id="content" name="content" rows="5" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Поделитесь своим опытом…">{{ old('content') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Изображение</label>
                                <input type="file" name="image" id="image" accept="image/*">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Отправить отзыв
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded px-4 py-3 flex items-center">
                        <svg class="h-6 w-6 flex-shrink-0 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        <p class="ml-3">
                            <span class="font-medium">Войдите, чтобы оставить отзыв.</span>
                            <a href="{{ route('login') }}" class="underline text-indigo-600 hover:text-indigo-800 ml-1">Войти</a>
                            или
                            <a href="{{ route('register') }}" class="underline text-indigo-600 hover:text-indigo-800 ml-1">Зарегистрироваться</a>
                        </p>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>
