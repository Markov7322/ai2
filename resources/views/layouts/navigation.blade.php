{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Навигационные ссылки (Отзывы, Помощь, Промоакции) -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Отзывы') }}
                    </x-nav-link>
                    <x-nav-link :href="route('help')" :active="request()->routeIs('help')">
                        {{ __('Помощь') }}
                    </x-nav-link>
                    <x-nav-link :href="route('promo')" :active="request()->routeIs('promo')">
                        {{ __('Промоакции') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Блок “Профиль/Войти” -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <!-- Триггер дропдауна: имя пользователя или “Гость” -->
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent
                                   text-sm leading-4 font-medium rounded-md text-gray-500 bg-white
                                   hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            @auth
                                <div>{{ Auth::user()->name }}</div>
                            @else
                                <div>Гость</div>
                            @endauth

                            <div class="ml-1">
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1
                                           0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1
                                           0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <!-- Содержимое дропдауна -->
                    <x-slot name="content">
                        @auth
                            <!-- Ссылка “Профиль” -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Профиль') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('messages.index')">
                                {{ __('Сообщения') }}
                            </x-dropdown-link>
                            @if(Auth::user() && Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Админ') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Форма “Выйти” -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    {{ __('Выйти') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <!-- Ссылки “Войти” и “Регистрация” -->
                            <x-dropdown-link :href="route('login')">
                                {{ __('Войти') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('register')">
                                {{ __('Регистрация') }}
                            </x-dropdown-link>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- “Гамбургер” (для мобильного меню) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                           hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100
                           focus:text-gray-500 transition duration-150 ease-in-out"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{'hidden': open, 'inline-flex': !open}"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{'hidden': !open, 'inline-flex': open}"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Ссылки “Отзывы”, “Помощь”, “Промоакции” -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Отзывы') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('help')" :active="request()->routeIs('help')">
                {{ __('Помощь') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('promo')" :active="request()->routeIs('promo')">
                {{ __('Промоакции') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Профиль') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('messages.index')">
                        {{ __('Сообщения') }}
                    </x-responsive-nav-link>
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            {{ __('Админ') }}
                        </x-responsive-nav-link>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            {{ __('Выйти') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">Гость</div>
                    <div class="font-medium text-sm text-gray-500">Добро пожаловать!</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Войти') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Регистрация') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
