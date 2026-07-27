@extends('layouts.app')

@section('content')

{{-- Breadcrumb --}}
<br>
<nav class="mb-6 flex items-center gap-2 text-xl text-slate-500 ml-4">
    <a href="{{ route('products.index') }}" class="transition hover:text-indigo-600">
        Produk
    </a>

    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>

    <span class="font-medium text-slate-800">
        {{ $product->name }}
    </span>
</nav>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 ml-8 mr-8">

    {{-- IMAGE --}}
    <div class="lg:col-span-1">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            @if ($product->image)
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    class="aspect-square w-full object-cover"
                >
            @else
                <div class="flex aspect-square w-full items-center justify-center bg-slate-100">
                    <p class="text-slate-400 text-sm">Tidak ada gambar</p>
                </div>
            @endif

        </div>
    </div>

    {{-- DETAIL --}}
    <div class="space-y-5 lg:col-span-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h1 class="text-2xl font-bold text-slate-800">
                {{ $product->name }}
            </h1>

            <p class="mt-2 text-2xl font-semibold text-indigo-600">
                {{ $product->formatted_price }}
            </p>

            {{-- INFO --}}
            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 mt-4 sm:grid-cols-3">

                <div>
                    <p class="text-xs text-slate-500">Stok</p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $product->stock }} unit
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500">Status</p>
                    <p class="text-sm font-semibold">
                        {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                    </p>
                </div>

            </div>

            {{-- DESKRIPSI --}}
            @if ($product->description)
                <div class="mt-4 border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-500">Deskripsi</p>
                    <p class="text-sm text-slate-700 mt-2">
                        {{ $product->description }}
                    </p>
                </div>
            @endif

        </div>

        {{-- ACTION --}}
        <div class="flex flex-wrap items-center gap-3">

            @auth

                {{-- CART --}}
                <div x-data="{ sending: false }">
<form
    @submit.prevent="
        if (sending) return;
        sending = true;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                quantity: Number($refs.qty.value)
            })
        })
        .then(res => res.json())
        .then(data => {
            sending = false;

            if (data.success) {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                const badge = document.getElementById('header-cart-count');

                if (badge && data.cart_count !== undefined) {
                    badge.textContent = data.cart_count;
                }

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });

            }
        })
        .catch(() => {

            sending = false;

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan.'
            });

        });
    "
    class="flex items-center gap-2"
>
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center space-x-1">
    <button type="button" 
            onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
            class="flex h-9 w-9 items-center justify-center rounded-l-lg border border-slate-300 bg-slate-50 text-slate-600 hover:bg-slate-100 active:bg-slate-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
        </svg>
    </button>

    <input x-ref="qty"
            type="number"
           name="quantity"
           value="1"
           min="1"
           max="{{ $product->stock }}"
           class="h-9 w-14 border-y border-slate-300 text-center text-sm focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

    <button type="button" 
            onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
            class="flex h-9 w-9 items-center justify-center rounded-r-lg border border-slate-300 bg-slate-50 text-slate-600 hover:bg-slate-100 active:bg-slate-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </button>
</div>
                    <button
    type="submit"
    :disabled="sending || {{ $product->stock <= 0 ? 'true' : 'false' }}"
    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
>

    <span x-show="!sending">
        🛒 Tambah ke Keranjang
    </span>

    <span
        x-show="sending"
        x-cloak
        class="flex items-center gap-2"
    >
        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
        Menambahkan...
    </span>

</button>
                </form>
</div>
                {{-- WISHLIST --}}
                <div
    x-data="{ liked: false, loading: false }"
>
    @auth
        <button
            @click.prevent="
                loading = true;

                fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }}
                    })
                })
                .then(res => res.json())
                .then(data => {
                    liked = !liked;
                    loading = false;
                })
                .catch(() => loading = false);
            "
            class="inline-flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition"
            :class="liked ? 'border-red-400 bg-red-50 text-red-600' : 'border-pink-300 bg-pink-50 text-pink-600'"
        >

            <span x-text="liked ? '❤️ Saved' : '🤍 Wishlist'"></span>

            <div x-show="loading" class="w-3 h-3 border-2 border-gray-300 border-t-red-500 rounded-full animate-spin"></div>

        </button>
    @else
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">
            Login
        </a>
    @endauth
</div>

            @endauth

            {{-- BACK --}}
            <a href="{{ route('products.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">

                Kembali
            </a>

        </div>

    </div>

</div>
<br>
<br>
{{-- ========================================================= --}}
{{-- LIHAT JUGA --}}
{{-- ========================================================= --}}

@if($relatedProducts->count())

<section class="mt-10 ml-10 mr-10">

    <div class="flex items-center justify-between mb-10">

        <div>
            <h2 class="text-2xl font-bold text-slate-800 ">
                Lihat Juga
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Produk terlaris yang mungkin Anda sukai.
            </p>
        </div>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @foreach($relatedProducts as $relatedProduct)

            <x-product-card
                :product="$relatedProduct"
                :isWishlisted="auth()->check()
                    ? auth()->user()
                        ->wishlists()
                        ->where('product_id', $relatedProduct->id)
                        ->exists()
                    : false"
            />

        @endforeach

    </div>

</section>
<br><br>
@endif
@endsection