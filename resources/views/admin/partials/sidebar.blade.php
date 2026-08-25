@php
    $logo = $settings?->logo_url ?? asset('assets/images/Logo.png');
@endphp

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="bg-gray-900 text-white flex flex-col transition-all duration-300 flex-shrink-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 py-5 border-b border-gray-800">

        <img
            src="{{ $logo }}"
            alt="{{ $settings?->app_name ?? 'Fasel Aquarium' }}"
            class="w-15 h-15 object-contain flex-shrink-0"
        >

        <div x-show="sidebarOpen" x-transition>
            <span class="font-bold text-lg text-white">
                {{ $settings?->app_name ?? 'Fasel Aquarium' }}
            </span>

            <p class="text-xs text-gray-400">
                {{ $settings?->app_description ?? 'Ikan Hias Berkualitas' }}
            </p>
        </div>

    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 overflow-y-auto">
        <div x-show="sidebarOpen" class="px-4 mb-2">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-widest">
                Menu
            </p>
        </div>

        @php
            $menus = [
                [
                    'icon' =>
                        'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'active' => request()->routeIs('admin.dashboard'),
                ],

                [
                    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                    'label' => 'Produk',
                    'route' => 'admin.products.index',
                    'active' => request()->routeIs('admin.products.*'),
                ],

                [
                    'icon' =>
                        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                    'label' => 'Daftar Pesanan',
                    'route' => 'admin.orders.index',
                    'active' => request()->routeIs('admin.orders.*'),
                ],

                [
                    'icon' =>
                        'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'label' => 'Laporan',
                    'route' => 'admin.reports.index',
                    'active' => request()->routeIs('admin.reports.*'),
                ],

                [
                    'icon'   => 'M9 17v-6h13M9 17l3 3m-3-3l-3 3M3 7h13M3 7l3-3M3 7l3 3',
                    'label'  => 'Prediksi',
                    'route'  => 'admin.prediction.index',
                    'active' => request()->routeIs('admin.prediction.*'),
                ],

                [
                    'icon' =>
                        'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'label' => 'Mortality Ikan',
                    'route' => 'admin.mortality.index',
                    'active' => request()->routeIs('admin.mortality.*'),
                ],
                
                [
                    'icon' =>
                        'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
                    'label' => 'Pengguna',
                    'route' => 'admin.users.index',
                    'active' => request()->routeIs('admin.users.*'),
                ],

                [
    'icon' =>
        'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    'label'  => 'Pengaturan',
    'route'  => 'admin.settings.index',
    'active' => request()->routeIs('admin.settings.*'),
],
            ];
        @endphp

        @foreach ($menus as $menu)
            <a href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
                class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded-xl text-sm font-medium transition mb-1
                {{ $menu['active']
                    ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="{{ $menu['icon'] }}" />
                </svg>

                <span x-show="sidebarOpen">{{ $menu['label'] }}</span>

                {{-- Active Indicator --}}
                @if ($menu['active'])
                    <span x-show="sidebarOpen"
                        class="ml-auto w-1.5 h-1.5 bg-white rounded-full opacity-80"></span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- User Info --}}
    <div class="border-t border-gray-800 p-4">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div x-show="sidebarOpen" class="overflow-hidden">
                <p class="text-white text-sm font-semibold truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-gray-400 text-xs truncate">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-3"
            x-show="sidebarOpen">
            @csrf

            <button type="submit"
                class="w-full flex items-center gap-2 text-red-400 hover:text-red-300 text-sm py-2 px-3 rounded-lg hover:bg-red-900 hover:bg-opacity-30 transition">

                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>

                Keluar
            </button>
        </form>
    </div>
</aside>