@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Mortality Ikan</h1>
            <p class="mt-0.5 text-sm text-slate-500">
                Catat dan pantau kematian ikan serta perubahan stok.
            </p>
        </div>

        <a href="{{ route('admin.mortality.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Catat Kematian
        </a>
    </div>

    <x-flash-message />

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Data belum dapat diproses.</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Ikan Mati</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ number_format($stats['total']) }}</p>
                    <p class="mt-1 text-xs text-red-600">Akumulasi seluruh catatan</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Mortality Bulan Ini</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ number_format($stats['this_month']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Jenis Ikan Terdampak</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ number_format($stats['affected_products']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Produk yang memiliki catatan mortality</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 7h14M5 12h14M5 17h14" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500">Penyebab Terbanyak</p>
                    <p class="mt-2 truncate text-xl font-extrabold text-slate-800">
                        {{ $stats['top_cause']?->cause ?? 'Belum ada data' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $stats['top_cause'] ? number_format((int) $stats['top_cause']->total) . ' ekor' : 'Belum ada catatan penyebab' }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M5.5 20h13a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.77 17a2 2 0 001.73 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <div class="mb-5">
                <h2 class="font-bold text-slate-800">Mortality per Bulan</h2>
                <p class="mt-0.5 text-xs text-slate-500">Jumlah ikan mati dalam enam bulan terakhir.</p>
            </div>
            <div class="h-72">
                <canvas id="mortalityChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-bold text-slate-800">Penyebab Kematian</h2>
                <p class="mt-0.5 text-xs text-slate-500">Lima penyebab dengan jumlah mortality terbesar.</p>
            </div>

            <div class="space-y-4">
                @forelse ($byCause as $item)
                    @php
                        $maxCause = max(1, $byCause->max('total'));
                        $percentage = ($item['total'] / $maxCause) * 100;
                    @endphp
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                            <span class="truncate font-medium text-slate-700">{{ $item['name'] }}</span>
                            <span class="shrink-0 font-semibold text-red-600">{{ $item['total'] }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-red-400" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">
                        Belum ada data penyebab kematian.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Produk terdampak --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="font-bold text-slate-800">Mortality Berdasarkan Jenis Ikan</h2>
            <p class="mt-0.5 text-xs text-slate-500">Produk dengan jumlah ikan mati terbesar.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            @forelse ($byProduct as $item)
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="truncate text-sm font-semibold text-slate-700">{{ $item['name'] }}</p>
                    <p class="mt-2 text-2xl font-extrabold text-red-600">{{ $item['total'] }}</p>
                    <p class="text-xs text-slate-500">ekor mati</p>
                </div>
            @empty
                <div class="col-span-full rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Belum ada data mortality.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Filter --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="font-bold text-slate-800">Filter Riwayat</h2>
            <p class="mt-0.5 text-xs text-slate-500">Gunakan filter untuk menemukan catatan tertentu.</p>
        </div>

        <form method="GET" action="{{ route('admin.mortality.index') }}"
            class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-6">
            <div class="xl:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama ikan, penyebab, keterangan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Produk</label>
                <select name="product_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Penyebab</label>
                <select name="cause"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua penyebab</option>
                    @foreach ($causes as $cause)
                        <option value="{{ $cause }}" @selected(request('cause') === $cause)>{{ $cause }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Dari tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Sampai tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex items-end gap-2 md:col-span-2 xl:col-span-6">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'product_id', 'cause', 'date_from', 'date_to']))
                    <a href="{{ route('admin.mortality.index') }}"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Riwayat Mortality</h2>
                <p class="text-xs text-slate-500">{{ $mortalityRecords->total() }} catatan ditemukan.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Ikan</th>
                        <th class="px-6 py-3 text-center">Jumlah</th>
                        <th class="px-6 py-3 text-left">Penyebab</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                        <th class="px-6 py-3 text-left">Dicatat Oleh</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($mortalityRecords as $record)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $record->date->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $record->product->image_url }}" alt="{{ $record->product->name }}"
                                        class="h-9 w-9 rounded-lg object-cover"
                                        onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=IMG'">
                                    <span class="font-semibold text-slate-700">{{ $record->product->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700">
                                    {{ number_format($record->quantity) }} ekor
                                </span>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $record->cause ?: 'Tidak diketahui' }}
                            </td>

                            <td class="max-w-xs px-6 py-4 text-slate-500">
                                <span class="line-clamp-2">{{ $record->notes ?: '-' }}</span>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $record->user?->name ?? 'Sistem' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.mortality.show', $record) }}"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.mortality.edit', $record) }}"
                                        class="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.mortality.destroy', $record) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)"
                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 8v4m0 4h.01M5.5 20h13a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.77 17a2 2 0 001.73 3z" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 font-semibold text-slate-700">Belum ada catatan mortality</p>
                                    <p class="mt-1 text-sm text-slate-500">Mulai catat kematian ikan untuk memantau kondisi stok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mortalityRecords->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $mortalityRecords->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    const mortalityChart = document.getElementById('mortalityChart');

    if (mortalityChart) {
        new Chart(mortalityChart, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Ikan Mati',
                    data: @json($chartValues),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.10)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
