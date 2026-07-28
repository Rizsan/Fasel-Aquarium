{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola semua pesanan pelanggan</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3" x-transition>
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3" x-transition>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        @php
            $statCards = [
    ['label' => 'Total',                 'key' => 'total',             'color' => 'gray'],
    ['label' => 'Menunggu Pembayaran',   'key' => 'pending',   'color' => 'yellow'],
    ['label' => 'Diproses',              'key' => 'processing',        'color' => 'indigo'],
    ['label' => 'Selesai',               'key' => 'completed',         'color' => 'green'],
    ['label' => 'Dibatalkan',            'key' => 'cancelled',         'color' => 'red'],
];
        @endphp

        @foreach($statCards as $card)
            <a href="{{ route('admin.orders.index', ['status' => $card['key'] === 'total' ? null : $card['key']]) }}"
               class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition
                      {{ request('status') === $card['key'] ? 'ring-2 ring-blue-500' : '' }}">
                <p class="text-2xl font-bold text-{{ $card['color'] }}-600">{{ $stats[$card['key']] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor order, nama, atau email..."
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Status Filter --}}
            <div class="sm:w-48">
                <select name="status"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending"
    {{ request('status') === 'pending' ? 'selected' : '' }}>
    Menunggu Pembayaran
</option>

<option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>
    Diproses
</option>

<option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
    Selesai
</option>

<option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
    Dibatalkan
</option>
                </select>
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600 text-sm font-medium px-4 py-2.5 rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($orders->isEmpty())
            <div class="py-16 flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tidak ada pesanan ditemukan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">No. Pesanan</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Pelanggan</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Item</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">
    Total
</th>

<th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">
    Metode
</th>

<th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">
    Status
</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Tanggal</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-gray-800">
                                        {{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600">{{ $order->items->count() }} item</span>
                                </td>
                                <td class="px-4 py-4">
    <span class="text-sm font-bold text-blue-600">
        {{ $order->formatted_total_price }}
    </span>
</td>

<td class="px-4 py-4">
    <span class="text-sm">
        @if($order->payment_type)
            {{ strtoupper($order->payment_type) }}
        @elseif($order->payment_method)
            {{ ucfirst($order->payment_method) }}
        @else
            -
        @endif
    </span>
</td>

<td class="px-4 py-4">
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $order->status_badge_class }}">
        {{ $order->status_label }}
    </span>
</td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-500">
                                        {{ $order->created_at->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 border border-blue-200 hover:border-blue-400 px-3 py-1.5 rounded-lg transition">
                                            Detail
                                        </a>
                                        @if($order->isUpdatableByAdmin())
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition">
                                                Update
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
