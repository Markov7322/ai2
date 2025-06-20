{{-- resources/views/messages/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $conversation->user_one_id === auth()->id() ? $conversation->userTwo->name : $conversation->userOne->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div id="messages-container"
                 data-conversation="{{ $conversation->id }}"
                 data-user-id="{{ auth()->id() }}"
                 data-last-timestamp="{{ optional($messages->last())->created_at }}"
                 class="bg-white p-4 rounded shadow space-y-4 overflow-y-auto max-h-96">
                @foreach ($messages as $msg)
                    <div class="{{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        <div class="inline-block px-3 py-2 rounded {{ $msg->sender_id === auth()->id() ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <p>{{ $msg->message }}</p>
                            @if($msg->image_path)
                                <img src="{{ Storage::url($msg->image_path) }}" alt="image" class="mt-2 max-w-xs rounded">
                            @endif
                            <span class="text-xs text-gray-500">{{ $msg->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <form id="message-form" method="POST" action="{{ route('messages.store', $conversation) }}" class="mt-4 flex space-x-2" enctype="multipart/form-data">
                @csrf
                <input type="text" name="message" class="flex-1 border-gray-300 rounded" required>
                <input type="file" name="image" accept="image/*">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Отправить</button>
            </form>
        </div>
    </div>
</x-app-layout>
