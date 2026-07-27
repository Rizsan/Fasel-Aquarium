{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @php
            $statCards = [
                [
                    'label' => 'Total Pengguna',
                    'value' => $stats['total_users'] ?? 0,
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
                    'color' => 'blue',
                    'sub' => ($stats['active_users'] ?? 0) . ' aktif',
                ],
                [
                    'label' => 'Total Produk',
                    'value' => $stats['total_products'] ?? 0,
                    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                    'color' => 'emerald',
                    'sub' => 'Semua kategori',
                ],
                [
                    'label' => 'Total Admin',
                    'value' => $stats['total_admin'] ?? 0,
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'color' => 'purple',
                    'sub' => 'Super admin',
                ],
                [
                    'label' => 'Pengguna Aktif',
                    'value' => $stats['active_users'] ?? 0,
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'color' => 'amber',
                    'sub' => 'Online hari ini',
                ],
            ];
        @endphp

        @foreach($statCards as $card)
            @php
                $colors = [
                    'blue' => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-600', 'text' => 'text-blue-600'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-600', 'text' => 'text-emerald-600'],
                    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-600', 'text' => 'text-purple-600'],
                    'amber' => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-500', 'text' => 'text-amber-600'],
                ];
                $c = $colors[$card['color']];
            @endphp

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-gray-500 text-sm font-medium">{{ $card['label'] }}</p>
                    <div class="{{ $c['icon'] }} w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                </div>

                <p class="text-3xl font-extrabold text-gray-800 mb-1">{{ $card['value'] }}</p>
                <p class="text-xs {{ $c['text'] }} font-medium">{{ $card['sub'] }}</p>
            </div>
        @endforeach
    </div>
{{-- Recent Tables --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                    {{-- Recent Products --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h2 class="font-bold text-gray-800">Produk Terbaru</h2>
                            <a href="{{ url('/admin/products') }}" class="text-blue-600 text-sm hover:underline">Lihat semua</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="text-left px-6 py-3">Produk</th>
                                        <th class="text-right px-6 py-3">Harga</th>
                                        <th class="text-center px-6 py-3">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @forelse($recentProducts ?? [] as $product)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                                        @if($product->image)
                                                            <img
                                                                src="{{ $product->image_url }}"
                                                                alt="{{ $product->name }}"
                                                                class="w-full h-full object-cover"
                                                                onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=IMG'"
                                                            >
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <span class="font-medium text-gray-700 truncate max-w-32">
                                                        {{ $product->name }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-3 text-right text-blue-600 font-semibold">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </td>

                                            <td class="px-6 py-3 text-center">
                                                <span class="{{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">
                                                Belum ada produk
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Recent Users --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h2 class="font-bold text-gray-800">Pengguna Terbaru</h2>
                            <a href="{{ url('/admin/users') }}" class="text-blue-600 text-sm hover:underline">Lihat semua</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="text-left px-6 py-3">Pengguna</th>
                                        <th class="text-center px-6 py-3">Role</th>
                                        <th class="text-center px-6 py-3">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @forelse($recentUsers ?? [] as $user)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>

                                                    <div>
                                                        <p class="font-medium text-gray-700 text-sm">{{ $user->name }}</p>
                                                        <p class="text-gray-400 text-xs truncate max-w-32">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-3 text-center">
                                                <span class="{{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                                    {{ $user->role }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-3 text-center">
                                                <span class="{{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">
                                                Belum ada pengguna
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
@endsection