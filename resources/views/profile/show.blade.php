{{-- resources/views/profile/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    @php
        $avatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?d=identicon&s=200';
    @endphp

    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6 flex flex-col sm:flex-row items-center">
            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
            <div class="sm:ml-6 mt-4 sm:mt-0 text-center sm:text-left">
                <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                <p class="text-gray-500 text-sm">
                    Страна: {{ $user->country ?? '—' }}<br>
                    Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}<br>
                    Активность: {{ $lastActivity ? $lastActivity->diffForHumans() : '—' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-xl font-bold">{{ $reputation }}</div>
                <div class="text-gray-500 text-sm">Репутация</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-xl font-bold">{{ $reviews->count() }}</div>
                <div class="text-gray-500 text-sm">Отзывы</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-xl font-bold">{{ $comments->count() }}</div>
                <div class="text-gray-500 text-sm">Комментарии</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-xl font-bold">{{ $followersCount }}</div>
                <div class="text-gray-500 text-sm">Подписчики</div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <nav class="border-b flex space-x-8 px-6 pt-4">
                <a href="#tab-about" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="about">Обо мне</a>
                <a href="#tab-reviews" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="reviews">Отзывы ({{ $reviews->count() }})</a>
                <a href="#tab-comments" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="comments">Комментарии ({{ $comments->count() }})</a>
                @auth
                    @if (auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('messages.start', $user) }}" class="ml-auto">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Написать сообщение</button>
                        </form>
                    @endif
                @endauth
            </nav>

            <div class="p-6">
                <div id="tab-about" class="tab-content">
                    <p class="text-gray-700">{{ $user->about ?? 'Пользователь ещё не рассказал о себе.' }}</p>
                </div>
                <div id="tab-reviews" class="tab-content hidden space-y-4">
                    @forelse($reviews as $review)
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('categories.show', $review->category->slug) }}" class="text-indigo-600 hover:underline font-medium">
                                    {{ $review->category->title }}
                                </a>
                                <span class="text-gray-500 text-sm">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="mt-2 text-yellow-500 font-semibold">★ {{ $review->rating }}</div>
                            <p class="mt-1 text-gray-700">{{ \Illuminate\Support\Str::limit($review->content, 120) }}</p>
                            @if($review->image_path)
                                <img src="{{ Storage::url($review->image_path) }}" alt="image" class="mt-2 max-w-xs rounded">
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600 italic">Отзывов пока нет.</p>
                    @endforelse
                </div>
                <div id="tab-comments" class="tab-content hidden space-y-4">
                    @forelse($comments as $comment)
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('categories.show', $comment->review->category->slug) }}#review-{{ $comment->review->id }}" class="text-indigo-600 hover:underline font-medium">
                                    К отзыву: {{ \Illuminate\Support\Str::limit($comment->review->content, 50) }}
                                </a>
                                <span class="text-gray-500 text-sm">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <p class="mt-2 text-gray-700">{{ \Illuminate\Support\Str::limit($comment->content, 120) }}</p>
                            @if($comment->image_path)
                                <img src="{{ Storage::url($comment->image_path) }}" alt="image" class="mt-2 max-w-xs rounded">
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600 italic">Комментариев пока нет.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function showTab(tabId) {
                const contentId = tabId.startsWith('tab-') ? tabId : `tab-${tabId}`;
                const contentEl = document.getElementById(contentId);
                if (!contentEl) return;
                tabContents.forEach(tc => tc.classList.add('hidden'));
                contentEl.classList.remove('hidden');
                tabLinks.forEach(link => {
                    link.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                    if (link.getAttribute('data-tab') === tabId) {
                        link.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
                    }
                });
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    showTab(this.getAttribute('data-tab'));
                });
            });

            showTab(tabLinks[0].getAttribute('data-tab'));
        });
    </script>
</x-app-layout>
