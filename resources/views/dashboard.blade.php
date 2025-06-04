{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Личный кабинет') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-700">Привет, {{ Auth::user()->name }}! Вы успешно вошли в систему.</p>
                <p class="mt-4">
                    <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">
                        Перейти в профиль пользователя
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
