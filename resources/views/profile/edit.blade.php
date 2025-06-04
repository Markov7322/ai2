{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    {{-- Слот “header” --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мой профиль') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white shadow rounded-lg">
            <div class="border-b">
                {{-- === Вкладки === --}}
                <nav class="flex space-x-8 px-6 py-4">
                    <a href="#tab-main" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="main">
                        Основное
                    </a>
                    <a href="#tab-reviews" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="reviews">
                        Мои отзывы ({{ $myReviews->count() }})
                    </a>
                    <a href="#tab-comments" class="tab-link text-gray-600 hover:text-indigo-600 font-medium" data-tab="comments">
                        Мои комментарии ({{ $myComments->count() }})
                    </a>
                    <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium">
                        Сообщения
                    </a>
                </nav>
            </div>

            <div class="px-6 py-4">
                {{-- === Содержимое вкладок === --}}
                {{-- 1. Основное --}}
                <div id="tab-main" class="tab-content">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        @if (session('status') === 'profile-updated')
                            <div class="text-green-600">
                                Профиль успешно обновлён!
                            </div>
                        @endif

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                            <input id="name" name="name" type="text" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   value="{{ old('name', $user->name) }}">
                            @error('name')
                                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input id="email" name="email" type="email" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   value="{{ old('email', $user->email) }}">
                            @error('email')
                                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
                                Сохранить
                            </button>

                            {{-- Кнопка удаления аккаунта --}}
                            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Вы действительно хотите удалить свой аккаунт?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                                    Удалить аккаунт
                                </button>
                            </form>
                        </div>
                    </form>
                </div>

                {{-- 2. Мои отзывы --}}
                <div id="tab-reviews" class="tab-content hidden space-y-4">
                    @forelse ($myReviews as $review)
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <div class="flex items-center justify-between">
                                {{-- Ссылка на страницу объекта --}}
                                <a href="{{ route('objects.show', $review->object->slug) }}"
                                   class="text-indigo-600 hover:underline font-medium">
                                    {{ $review->object->title }}
                                </a>
                                <span class="text-gray-500 text-sm">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="mt-2 text-yellow-500 font-semibold">
                                ★ {{ $review->rating }}
                            </div>
                            <p class="mt-1 text-gray-700">{{ \Illuminate\Support\Str::limit($review->content, 100) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600 italic">Вы ещё не оставили ни одного отзыва.</p>
                    @endforelse
                </div>

                {{-- 3. Мои комментарии --}}
                <div id="tab-comments" class="tab-content hidden space-y-4">
                    @forelse ($myComments as $comment)
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <div class="flex items-center justify-between">
                                {{-- Ссылка на отзыв (объект#review-id) --}}
                                <a href="{{ route('objects.show', $comment->review->object->slug) }}#review-{{ $comment->review->id }}"
                                   class="text-indigo-600 hover:underline font-medium">
                                    К отзыву: {{ \Illuminate\Support\Str::limit($comment->review->content, 50) }}
                                </a>
                                <span class="text-gray-500 text-sm">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <p class="mt-2 text-gray-700">{{ \Illuminate\Support\Str::limit($comment->content, 120) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600 italic">Вы ещё не оставили ни одного комментария.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- === JS для переключения вкладок === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function showTab(tabId) {
                const contentId = tabId.startsWith('tab-') ? tabId : `tab-${tabId}`;
                const contentEl = document.getElementById(contentId);
                if (!contentEl) {
                    return; // защита от попытки открыть несуществующую вкладку
                }

                tabContents.forEach(tc => tc.classList.add('hidden'));
                contentEl.classList.remove('hidden');

                tabLinks.forEach(link => {
                    link.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                    if (link.getAttribute('data-tab') === tabId) {
                        link.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
                    }
                });
            }

            // При клике переключаем
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('data-tab');
                    showTab(target);
                });
            });

            // Определяем вкладку из URL ?tab=
            const urlParams = new URLSearchParams(window.location.search);
            const initialTab = urlParams.get('tab');
            if (initialTab) {
                showTab(initialTab);
            } else if (tabLinks.length > 0) {
                showTab(tabLinks[0].getAttribute('data-tab'));
            }
        });
    </script>
</x-app-layout>
