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
        <a href="{{ route('admin.products.show', $product) }}" class="transition hover:text-indigo-600">
            {{ $product->name }}
        </a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-slate-800">Edit</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Edit Produk</h1>
        <p class="mt-0.5 text-sm text-slate-500">Perbarui informasi produk <strong>{{ $product->name }}</strong>.</p>
    </div>

    <x-admin.product-form
        :product="$product"
        :action="route('admin.products.update', $product)"
        method="PUT"
    />

@endsection