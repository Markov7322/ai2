{{-- resources/views/components/app-layout.blade.php --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name', 'Отзывы') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Подключаем скомпилированные стили Breeze/Vite (TailwindCSS + Alpine) --}}
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Если у тебя есть собственный CSS (style.css с фиксацией хедера, футером и т. д.), подключаем его тоже --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- === ХЕДЕР (фиксация наведена в public/css/style.css) === --}}
    <header class="header bg-white shadow-sm">
        <div class="header-inner max-w-7xl mx-auto flex items-center justify-between px-4 py-2 flex-wrap">
            {{-- Логотип --}}
            <div class="logo flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-red-600 hover:text-red-700">
                    🕘 Отзовик
                </a>
            </div>

            {{-- Блок поиска --}}
            <form action="{{ route('search') ?? '#' }}" method="GET" class="search-box flex-1 mx-4 max-w-xl">
                <input
                    type="text"
                    name="q"
                    placeholder="Пример: Mazda6 седан"
                    class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <button
                    type="submit"
                    class="px-4 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    🔍
                </button>
            </form>

            {{-- Навигационные ссылки --}}
            <nav class="nav-links flex-shrink-0 space-x-4">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">Отзывы</a>
                <a href="{{ route('help') ?? '#' }}" class="text-gray-700 hover:text-indigo-600">Помощь</a>
                <a href="{{ route('promo') ?? '#' }}" class="text-gray-700 hover:text-indigo-600">Промоакции</a>
            </nav>

            {{-- Блок пользователя / профиль --}}
            <div class="user-block flex-shrink-0 ml-4">
                @auth
                    {{-- Если пользователь авторизован --}}
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center space-x-1 text-gray-700 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                             fill="currentColor"><path d="M10 4a4 4 0 100 8 4 4 0 000-8z"/><path fill-rule="evenodd"
                             d="M.458 16.042A8 8 0 0116.04.458 8 8 0 01.458 16.04zM8 8a6 6 0 1112 0 6 6 0 01-12 0z"
                             clip-rule="evenodd"/></svg>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    {{-- Кнопка “Выйти” --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-3">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 focus:outline-none">
                            Выйти
                        </button>
                    </form>
                @else
                    {{-- Если гость --}}
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Войти</a>
                    <a href="{{ route('register') }}" class="ml-4 text-gray-700 hover:text-indigo-600">Регистрация</a>
                @endauth
            </div>
        </div>
    </header>
    {{-- === /ХЕДЕР === --}}

    {{-- === Опциональный слот “header” (название страницы, хлебные крошки) === --}}
    @isset($header)
        <div class="bg-white border-b shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </div>
    @endisset

    {{-- === Основной контент страницы === --}}
    <main class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    {{-- === ФУТЕР === --}}
    <footer class="bg-white border-t py-6 text-center text-gray-500 text-sm">
        © {{ date('Y') }} Отзовик24. Все права защищены.
    </footer>
</body>
</html>
