@extends('layouts.admin')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.products.index') }}" class="transition hover:text-indigo-600">
            Produk
        </a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-slate-800">{{ $product->name }}</span>
    </nav>

    {{-- Flash Message --}}
    <x-flash-message />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri: Gambar --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @if ($product->image)
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="aspect-square w-full object-cover"
                        onerror="this.src='https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image'"
                    />
                @else
                    <div class="flex aspect-square w-full items-center justify-center bg-slate-100">
                        <div class="flex flex-col items-center gap-2 text-slate-400">
                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Tidak ada gambar</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Detail --}}
        <div class="space-y-5 lg:col-span-2">

            {{-- Info Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h1>
                        <p class="mt-1 text-2xl font-semibold text-indigo-600">{{ $product->formatted_price }}</p>
                    </div>
                    <x-admin.badge :color="$product->is_active ? 'green' : 'gray'" class="shrink-0 text-sm">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </x-admin.badge>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Stok</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $product->stock }} unit</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Status Stok</p>
                        <div class="mt-1">
                            <x-admin.badge :color="$product->stock_status_color">
                                {{ $product->stock_status }}
                            </x-admin.badge>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Dibuat</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $product->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>

                @if ($product->description)
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Deskripsi</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">{{ $product->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                        text-white shadow-sm transition hover:bg-indigo-700 active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Produk
                </a>

                <form
                    action="{{ route('admin.products.destroy', $product) }}"
                    method="POST"
                    x-data
                    @submit.prevent="
                        if (confirm('Hapus produk ini? Tindakan ini tidak bisa dibatalkan.')) {
                            $el.submit()
                        }
                    "
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-red-300 bg-red-50 px-4
                            py-2.5 text-sm font-semibold text-red-600 shadow-sm transition hover:bg-red-100 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Produk
                    </button>
                </form>

                <a href="{{ route('admin.products.index') }}"
                    class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium
                        text-slate-600 shadow-sm transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>

        </div>
    </div>

@endsection