@props(['review'])
@php
    $likes = $review->reactions->where('type', 'like')->count();
    $dislikes = $review->reactions->where('type', 'dislike')->count();
    $avatar = $review->user ? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($review->user->email))).'?d=identicon&s=96' : null;
@endphp
<div id="review-{{ $review->id }}" class="review-card bg-white shadow rounded-lg p-4 mb-4" data-rating="{{ $review->rating }}">
    <div class="flex justify-between items-start">
        <div class="flex items-center space-x-2 w-2/3">
            <span class="bg-red-600 text-white px-2 py-1 rounded text-sm font-semibold">★ {{ $review->rating }}</span>
            <h3 class="font-bold text-gray-900">{{ $review->title ?? 'Без заголовка' }}</h3>
        </div>
        <div class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</div>
    </div>
    <div class="flex items-center mt-3">
        @if($review->user)
            <img src="{{ $avatar }}" alt="avatar" class="w-12 h-12 rounded-full">
            <div class="ml-3">
                <a href="{{ route('users.show', $review->user) }}" class="font-medium text-gray-800 hover:underline">{{ $review->user->name }}</a>
            </div>
        @else
            <span class="text-gray-500">Аноним</span>
        @endif
    </div>
    @if($review->pros)
        <div class="mt-2">
            <span class="font-semibold">Достоинства:</span>
            <p class="ml-2">{!! nl2br(e($review->pros)) !!}</p>
        </div>
    @endif
    @if($review->cons)
        <div class="mt-2">
            <span class="font-semibold">Недостатки:</span>
            <p class="ml-2">{!! nl2br(e($review->cons)) !!}</p>
        </div>
    @endif
    <div class="mt-2 text-gray-700">
        <p class="review-content" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{!! nl2br(e($review->content)) !!}</p>
        @if(strlen($review->content) > 200)
            <a href="#" class="read-more text-indigo-600 hover:underline text-sm">Читать весь отзыв</a>
        @endif
    </div>
    @if($review->image_path)
        <div class="mt-2 flex flex-wrap gap-2">
            <img src="{{ Storage::url($review->image_path) }}" alt="image" class="w-24 h-24 object-cover rounded">
        </div>
    @endif
    <div class="mt-3 flex items-center space-x-4 text-sm">
        <button class="like-btn flex items-center space-x-1" data-type="like" data-review="{{ $review->id }}" data-url="{{ route('reviews.react', $review) }}">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a2 2 0 012-2h3l1-5a1 1 0 011-1h2a2 2 0 012 2v1h3a1 1 0 011 1v1.28a2 2 0 01-.21.9l-3.23 7A2 2 0 0112.95 16H6a4 4 0 01-4-4V10z" /></svg>
            <span>{{ $likes }}</span>
        </button>
        <button class="dislike-btn flex items-center space-x-1" data-type="dislike" data-review="{{ $review->id }}" data-url="{{ route('reviews.react', $review) }}">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a2 2 0 01-2 2h-3l-1 5a1 1 0 01-1 1H9a2 2 0 01-2-2v-1H4a1 1 0 01-1-1V9.72a2 2 0 01.21-.9l3.23-7A2 2 0 017.05 0H14a4 4 0 014 4v6z" /></svg>
            <span>{{ $dislikes }}</span>
        </button>
        <a href="#comment-form-{{ $review->id }}" class="flex items-center space-x-1 comment-link">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 13a1 1 0 01-1 1H7l-4 4V5a1 1 0 011-1h14a1 1 0 011 1v8z" /></svg>
            <span>{{ $review->comments->count() }}</span>
        </a>
    </div>
</div>
