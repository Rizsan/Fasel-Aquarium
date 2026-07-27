@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Produk</h1>
            <p class="mt-0.5 text-sm text-slate-500">
                Total {{ $products->total() }} produk terdaftar.
            </p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                text-white shadow-sm transition hover:bg-indigo-700 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- Flash Message --}}
    <x-flash-message />

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-5">
        <div class="flex gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau deskripsi produk..."
                    class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm
                        text-slate-800 shadow-sm placeholder-slate-400 transition
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>
            <button type="submit"
                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium
                    text-slate-600 shadow-sm transition hover:bg-slate-50">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('admin.products.index') }}"
                    class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium
                        text-slate-600 shadow-sm transition hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <x-admin.product-table :products="$products" />

    {{-- Pagination --}}
    @if ($products->hasPages())
        <div class="mt-5">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

@endsection