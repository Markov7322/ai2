{{-- resources/views/messages/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои беседы') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($conversations as $conv)
                @php
                    $other = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne;
                    $last = $conv->messages->last();
                    $unread = $conv->messages->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('messages.show', $conv) }}" class="block p-4 bg-white rounded shadow hover:bg-gray-50">
                    <div class="font-medium">{{ $other->name }}</div>
                    @if ($last)
                        <div class="text-sm text-gray-600">
                            {{ \Illuminate\Support\Str::limit($last->message, 50) }}
                            <span class="ml-2 text-xs text-gray-400">{{ $last->created_at->diffForHumans() }}</span>
                            @if ($unread)
                                <span class="ml-2 text-red-500">({{ $unread }})</span>
                            @endif
                        </div>
                    @endif
                </a>
            @empty
                <p class="text-gray-600">У вас нет бесед.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
