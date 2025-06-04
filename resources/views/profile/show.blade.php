{{-- resources/views/profile/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @auth
                @if (auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('messages.start', $user) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Написать сообщение</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
