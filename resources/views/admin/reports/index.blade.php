{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    {{-- ============================================================
         PAGE HEADER
         ============================================================ --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
            <p class="text-gray-500 text-sm mt-1">Analisis performa penjualan toko Anda secara detail</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Diperbarui: {{ now()->format('d M Y, H:i') }}</span>
        </div>
    </div>

    {{-- ============================================================
         FILTER SECTION
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">
                Filter Laporan
            </h2>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-5">

                    {{-- Bulan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Bulan
                        </label>
                        <select
                            name="month"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                        >
                            @foreach(range(1, 12) as $m)
                            <option
                                value="{{ $m }}"
                                {{ $month == $m ? 'selected' : '' }}
                            >
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Tahun
                        </label>
                        <select
                            name="year"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                        >
                            @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Start --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Tanggal Awal
                        </label>
                        <input
                            type="date"
                            name="date_start"
                            value="{{ $dateStart ?? '' }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                        >
                    </div>

                    {{-- Date End --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Tanggal Akhir
                        </label>
                        <input
                            type="date"
                            name="date_end"
                            value="{{ $dateEnd ?? '' }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                        >
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="flex flex-col justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-100">
                    {{-- Reset --}}
                    <a
                        href="{{ route('admin.reports.index') }}"
                        class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600 text-sm font-medium px-4 py-2.5 rounded-xl transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filter
                    </a>

                    {{-- Export PDF --}}
                    <a
                        href="{{ route('admin.reports.pdf', request()->query()) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-medium px-4 py-2.5 rounded-xl transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </a>

                    {{-- Export Excel --}}
                    <a
                        href="{{ route('admin.reports.excel', request()->query()) }}"
                        class="inline-flex items-center gap-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-sm font-medium px-4 py-2.5 rounded-xl transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </a>

                    {{-- Print --}}
                    <button
                        type="button"
                        @click="window.print()"
                        class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-xl transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ============================================================
         STATISTIC CARDS (4 COLUMNS)
         ============================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        {{-- Card: Total Penjualan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($growthPercent != 0)
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold px-2 py-1 rounded-lg
                    {{ $growthPercent >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }}">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $growthPercent >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/>
                    </svg>
                    {{ number_format(abs($growthPercent), 1) }}%
                </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Penjualan</p>
            <p class="text-lg font-bold text-gray-900 mt-1">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>

        {{-- Card: Total Order --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Order</p>
            <p class="text-lg font-bold text-gray-900 mt-1">
                {{ number_format($totalOrders) }} <span class="text-xs font-normal text-gray-500">pesanan</span>
            </p>
        </div>

        {{-- Card: Produk Terjual --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Produk Terjual</p>
            <p class="text-lg font-bold text-gray-900 mt-1">
                {{ number_format($totalItemsSold) }} <span class="text-xs font-normal text-gray-500">item</span>
            </p>
        </div>

        {{-- Card: Rata-rata Transaksi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Rata-rata Transaksi</p>
            <p class="text-lg font-bold text-gray-900 mt-1">
                Rp {{ number_format($averageTransaction, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- ============================================================
         CHART + TOP PRODUCTS
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Chart: Penjualan (2/3 width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Grafik Penjualan</h3>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-72">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Products (1/3 width) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Produk Terlaris</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($topProducts as $idx => $product)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                        {{ $idx === 0 ? 'bg-amber-100 text-amber-700' :
                           ($idx === 1 ? 'bg-gray-100 text-gray-600' :
                           ($idx === 2 ? 'bg-orange-100 text-orange-700' :
                           'bg-gray-50 text-gray-500')) }}">
                        {{ $idx + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 truncate">
                            {{ $product['name'] }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $product['quantity'] }} terjual</p>
                    </div>
                    <p class="text-xs font-bold text-blue-600 shrink-0">
                        Rp {{ number_format($product['revenue'], 0, ',', '.') }}
                    </p>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-6">Tidak ada data produk</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================================
         PAYMENT STATS + TABLE
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Payment Method Stats (1/4 width) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Metode Pembayaran</h3>
            </div>
            <div class="p-6 space-y-3">
                @php
                    $paymentColors = ['bg-blue-500', 'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-red-500'];
                    $totalPaymentOrders = $paymentStats->sum('count');
                @endphp
                @forelse($paymentStats as $idx => $stat)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-600">{{ $stat['type'] }}</span>
                        <span class="text-xs text-gray-400">{{ $stat['count'] }}x</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full {{ $paymentColors[$idx % count($paymentColors)] }}"
                            style="width: {{ $totalPaymentOrders > 0 ? ($stat['count'] / $totalPaymentOrders) * 100 : 0 }}%"
                        ></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        {{-- Table Order (3/4 width) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tabel Transaksi</h3>
                        <p class="text-xs text-gray-400">{{ $orders->total() }} transaksi ditemukan</p>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            @if($orders->isEmpty())
                <div class="py-12 flex flex-col items-center text-center px-6">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada data transaksi</p>
                    <p class="text-gray-400 text-xs mt-1">Coba ubah filter periode</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">No</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Order</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Customer</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Tanggal</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Total</th>
                                <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Status</th>
                                <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-4">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="font-mono text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">
                                        {{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-900">{{ $order->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs text-gray-600">{{ $order->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-xs font-bold text-blue-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                                        {{ $order->status === 'completed'
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                            : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-xs text-gray-500 font-medium uppercase">
                                        {{ $order->payment_type ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $orders->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // Chart Data
    // =========================================================
    const chartLabels = @json($chartData['labels']);
    const chartValues = @json($chartData['data']);

    // =========================================================
    // Chart Setup
    // =========================================================
    const ctx = document.getElementById('salesChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: chartValues,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#3B82F6',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 5,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 10,
                    cornerRadius: 8,
                    titleColor: '#fff',
                    bodyColor: '#f3f4f6',
                    borderColor: '#374151',
                    borderWidth: 1,
                    callbacks: {
                        label: function(ctx) {
                            return '  Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        callback: function(val) {
                            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        }
                    },
                    beginAtZero: true,
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
@media print {
    nav, aside, button:not(.print-visible), form { display: none !important; }
}
</style>
@endpush
