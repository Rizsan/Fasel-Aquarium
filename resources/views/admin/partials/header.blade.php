{{-- resources/views/admin/partials/topbar.blade.php --}}
<header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
    <div class="flex items-center justify-between h-16 px-6">

        {{-- Left: Page Title / Breadcrumb --}}
        <div class="flex items-center gap-3">
            {{-- Mobile menu toggle (jika ada sidebar) --}}
            <button
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition"
                @click="sidebarOpen = !sidebarOpen"
                x-data
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Panel</p>
                <h2 class="text-sm font-bold text-gray-900 leading-none mt-0.5">@yield('title', 'Dashboard')</h2>
            </div>
        </div>

        {{-- Right: Actions + Profile --}}
        <div class="flex items-center gap-2">

            {{-- Link ke Toko --}}
            <a
                href="{{ route('home') }}"
                target="_blank"
                class="hidden sm:flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-xl transition"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Toko
            </a>

            {{-- =========================================================
                 ADMIN PROFILE DROPDOWN
                 ========================================================= --}}
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2.5 pl-1 pr-3 py-1 rounded-xl hover:bg-gray-100 transition"
                >
                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-lg overflow-hidden border border-gray-200 shrink-0">
                        @if(auth()->user()->profile_photo_url)
                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ auth()->user()->initials }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-gray-900 leading-none">{{ Str::limit(auth()->user()->name, 15) }}</p>
                        <p class="text-xs text-gray-400 leading-none mt-0.5">Administrator</p>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                         :class="{ 'rotate-180': open }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-2xl border border-gray-100 shadow-xl z-50 overflow-hidden"
                >
                    {{-- Header --}}
                    <div class="px-4 py-4 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-gray-200 shrink-0">
                                @if(auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ auth()->user()->initials }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold bg-gray-900 text-white mt-0.5">
                                    Administrator
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="p-2">
                        <a
                            href="{{ route('admin.profile.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-xl transition"
                            @click="open = false"
                        >
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            Profil Admin
                        </a>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-xl transition"
                            @click="open = false"
                        >
                            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"/>
                                </svg>
                            </div>
                            Dashboard
                        </a>

                        <a
                            href="{{ route('home') }}"
                            target="_blank"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-xl transition"
                        >
                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            Lihat Toko
                        </a>
                    </div>

                    {{-- Logout --}}
                    <div class="p-2 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition"
                            >
                                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </div>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
