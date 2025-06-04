{{-- resources/views/messages/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $conversation->user_one_id === auth()->id() ? $conversation->userTwo->name : $conversation->userOne->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 rounded shadow space-y-4">
                @foreach ($messages as $msg)
                    <div class="{{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        <div class="inline-block px-3 py-2 rounded {{ $msg->sender_id === auth()->id() ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <p>{{ $msg->message }}</p>
                            <span class="text-xs text-gray-500">{{ $msg->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('messages.store', $conversation) }}" class="mt-4 flex space-x-2">
                @csrf
                <input type="text" name="message" class="flex-1 border-gray-300 rounded" required>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Отправить</button>
            </form>
        </div>
    </div>
</x-app-layout>
