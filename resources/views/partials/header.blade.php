{{-- resources/views/partials/header.blade.php --}}

@php
    $logo = ($settings?->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings->logo))
        ? asset('storage/' . $settings->logo)
        : asset('assets/images/Logo.png');
@endphp

<header
    class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40"
    x-data="{ mobileOpen: false }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- =========================================================
             HEADER CONTENT
             ========================================================= --}}
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- =========================================================
                 LOGO
                 ========================================================= --}}
            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3 flex-shrink-0"
            >
                <img
                    src="{{ $logo }}"
                    alt="{{ $settings?->app_name ?? 'Fasel Aquarium' }}"
                    class="w-15 h-15 object-contain"
                >

                <div class="hidden sm:block">
                    <h1 class="text-lg font-bold text-gray-900 leading-none">
                        {{ $settings?->app_name ?? 'Fasel Aquarium' }}
                    </h1>

                    <p class="text-xs text-gray-500 leading-none mt-0.5">
                        {{ $settings?->app_description ?? 'Ikan Hias Berkualitas' }}
                    </p>
                </div>
            </a>

            {{-- =========================================================
                 SEARCH BAR
                 ========================================================= --}}
            <form
                action="{{ route('home') }}"
                method="GET"
                class="hidden md:block flex-1 max-w-xl"
            >
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >

                    <svg
                        class="absolute left-4 top-3 w-4 h-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </form>

            {{-- =========================================================
                 RIGHT SECTION
                 ========================================================= --}}
            <div class="flex items-center gap-2 sm:gap-3">

                @auth

                    {{-- =========================================================
                         ORDERS
                         ========================================================= --}}
                    <a
                        href="{{ route('orders.index') }}"
                        class="hidden sm:flex relative p-2.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition"
                        title="Pesanan Saya"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                            />
                        </svg>
                    </a>

                    {{-- =========================================================
                         CART
                         ========================================================= --}}
                    <a
                        href="{{ route('cart.index') }}"
                        class="relative p-2.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition"
                        title="Keranjang"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />
                        </svg>

                        {{-- Cart Badge --}}
<span
    id="header-cart-count"
    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-blue-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow"
>
    {{ auth()->check() ? \App\Models\Cart::forUser(auth()->id())->sum('quantity') : 0 }}
</span>
                    </a>

                    {{-- =========================================================
                         WISHLIST
                         ========================================================= --}}
                    <a
                        href="{{ route('wishlist.index') }}"
                        class="hidden sm:flex p-2.5 text-gray-500 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition"
                        title="Wishlist"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                            />
                        </svg>
                    </a>

                    {{-- =========================================================
                         USER DROPDOWN
                         ========================================================= --}}
                    <div
                        class="relative"
                        x-data="{ open: false }"
                        @click.away="open = false"
                    >

                        {{-- Dropdown Button --}}
                        <button
                            @click="open = !open"
                            class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-xl hover:bg-gray-100 transition"
                        >

                            {{-- Avatar --}}
                            <div class="w-9 h-9 rounded-xl overflow-hidden border border-gray-200 shrink-0">

                                @if(auth()->user()->profile_photo_url)
                                    <img
                                        src="{{ auth()->user()->profile_photo_url }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">
                                            {{ auth()->user()->initials }}
                                        </span>
                                    </div>
                                @endif

                            </div>

                            {{-- Name --}}
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-gray-800 leading-none max-w-28 truncate">
                                    {{ auth()->user()->name }}
                                </p>
                            </div>

                            {{-- Arrow --}}
                            <svg
                                class="w-4 h-4 text-gray-400 transition duration-200"
                                :class="{ 'rotate-180': open }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        {{-- =========================================================
                             DROPDOWN MENU
                             ========================================================= --}}
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-64 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden z-50"
                            style="display: none;"
                        >

                            {{-- User Info --}}
                            <div class="px-4 py-4 bg-gray-50 border-b border-gray-100">

                                <div class="flex items-center gap-3">

                                    {{-- Avatar --}}
                                    <div class="w-11 h-11 rounded-xl overflow-hidden border border-gray-200 shrink-0">

                                        @if(auth()->user()->profile_photo_url)
                                            <img
                                                src="{{ auth()->user()->profile_photo_url }}"
                                                class="w-full h-full object-cover"
                                                alt="{{ auth()->user()->name }}"
                                            >
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">
                                                    {{ auth()->user()->initials }}
                                                </span>
                                            </div>
                                        @endif

                                    </div>

                                    {{-- Info --}}
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            {{ auth()->user()->name }}
                                        </p>

                                        <p class="text-xs text-gray-500 truncate">
                                            {{ auth()->user()->email }}
                                        </p>

                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                                                {{ auth()->user()->role_label }}
                                            </span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            {{-- Menu --}}
                            <div class="p-2">

                                {{-- Profile --}}
                                <a
                                    href="{{ route('profile.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition"
                                >
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>

                                    Profil Saya
                                </a>

                                {{-- Orders --}}
                                <a
                                    href="{{ route('orders.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition"
                                >
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                            />
                                        </svg>
                                    </div>

                                    Pesanan Saya
                                </a>

                                {{-- Wishlist --}}
                                <a
                                    href="{{ route('wishlist.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-rose-500 transition"
                                >
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                            />
                                        </svg>
                                    </div>

                                    Wishlist
                                </a>

                                {{-- Admin Dashboard --}}
                                @if(auth()->user()->isAdmin())
                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition"
                                    >
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 7h18M5 7l1 12h12l1-12"
                                                />
                                            </svg>
                                        </div>

                                        Admin Dashboard
                                    </a>
                                @endif

                            </div>

                            {{-- Logout --}}
                            <div class="p-2 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition"
                                    >
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                                />
                                            </svg>
                                        </div>

                                        Keluar
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>

                @else

                    {{-- =========================================================
                         GUEST BUTTONS
                         ========================================================= --}}
                    <a
                        href="{{ route('login') }}"
                        class="hidden sm:block px-4 py-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm"
                    >
                        Daftar
                    </a>

                @endauth

                {{-- =========================================================
                     MOBILE MENU BUTTON
                     ========================================================= --}}
                <button
                    @click="mobileOpen = !mobileOpen"
                    class="sm:hidden p-2 rounded-xl hover:bg-gray-100 transition"
                >
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>

            </div>
        </div>

        {{-- =========================================================
             MOBILE MENU
             ========================================================= --}}
        <div
            x-show="mobileOpen"
            x-transition
            class="sm:hidden border-t border-gray-100 py-4 space-y-2"
            style="display: none;"
        >

            {{-- Mobile Search --}}
            <form action="{{ route('home') }}" method="GET" class="mb-3">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <svg
                        class="absolute left-4 top-3 w-4 h-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </form>

            @auth

                <a
                    href="{{ route('profile.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl"
                >
                    Profil Saya
                </a>

                <a
                    href="{{ route('orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl"
                >
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                        />
                    </svg>
                    Pesanan Saya
                </a>

                <a
    href="{{ route('cart.index') }}"
    class="flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl"
>
    <span class="flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
            />
        </svg>
        Keranjang
    </span>

    {{-- Tambahkan id="header-cart-count" untuk target manipulasi DOM langsung --}}
    <span 
        id="header-cart-count"
        class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-600 rounded-full"
    >
        {{ auth()->check() ? \App\Models\Cart::forUser(auth()->id())->sum('quantity') : 0 }}
    </span>
</a>

                <a
                    href="{{ route('wishlist.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl"
                >
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                    </svg>
                    Wishlist
                </a>

                @if(auth()->user()->isAdmin())
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-xl"
                    >
                        Admin Dashboard
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl"
                    >
                        Keluar
                    </button>
                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl"
                >
                    Masuk
                </a>

                <a
                    href="{{ route('register') }}"
                    class="block px-4 py-3 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-xl"
                >
                    Daftar
                </a>

            @endauth

        </div>

    </div>
</header>
