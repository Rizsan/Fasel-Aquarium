{{-- resources/views/cart/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $cartItems->count() }} produk dalam keranjang
                </p>
            </div>
            <a
                href="{{ route('products.index') }}"
                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Lanjut Belanja
            </a>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 mb-6"
            >
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-6"
            >
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 mb-6"
            >
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('info') }}</p>
                <button @click="show = false" class="ml-auto text-blue-400 hover:text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ===== CHECKOUT BLOCK WARNINGS ===== --}}
        @if($blockReasons->isNotEmpty())
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 mb-1">Beberapa item tidak bisa di-checkout:</p>
                        <ul class="text-xs text-amber-700 space-y-0.5">
                            @foreach($blockReasons as $reason)
                                <li class="flex items-center gap-1.5">
                                    <span class="w-1 h-1 bg-amber-500 rounded-full flex-shrink-0"></span>
                                    <span class="font-medium">{{ $reason['name'] }}</span>
                                    <span>—</span>
                                    @if($reason['reason'] === 'expired')
                                        <span>Item sudah expired (lebih dari 3 hari)</span>
                                    @elseif($reason['reason'] === 'out_of_stock')
                                        <span>Stok habis</span>
                                    @elseif($reason['reason'] === 'exceeds_stock')
                                        <span>Jumlah melebihi stok tersedia</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== EMPTY STATE ===== --}}
        @if($cartItems->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-20 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Keranjang Belanja Kosong</h2>
                <p class="text-gray-400 text-sm mb-6 max-w-xs">
                    Anda belum menambahkan produk apapun. Mulai belanja sekarang!
                </p>
                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Mulai Belanja
                </a>
            </div>

        @else

            {{-- ===== CART LAYOUT ===== --}}
            <div
                x-data="cartManager()"
                class="grid grid-cols-1 lg:grid-cols-3 gap-6"
            >

                {{-- ===== LEFT: CART ITEMS ===== --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Header actions --}}
<div class="flex items-center justify-between">
    <p class="text-sm text-gray-500">
        <span class="font-medium text-gray-700">{{ $cartItems->count() }}</span> produk
    </p>
    
    <form action="{{ route('cart.clear') }}" method="POST" id="clear-cart-form">
        @csrf
        @method('DELETE')
        <button type="button"
                @click="confirmClearCart"
                class="text-xs text-red-400 hover:text-red-600 font-medium transition">
            Hapus Semua
        </button>
    </form>
</div>

                {{-- ===== CART ITEM CARDS ===== --}}
                @foreach($cartItems as $item)
                    @php
                        $isExpired     = $item->isExpired();
                        $isOutOfStock  = $item->product->stock <= 0;
                        $exceedsStock  = !$isOutOfStock && $item->quantity > $item->product->stock;
                        $isBlocked     = $isExpired || $isOutOfStock || $exceedsStock;
                        $daysLeft      = 3 - $item->created_at->diffInDays(now());
                        $hoursLeft     = $item->created_at->diffInHours(now());
                    @endphp

                    <div
                        class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all duration-200
                               {{ $isBlocked ? 'border-red-100' : 'border-gray-100' }}"
                        x-data="{
                            qty: {{ $item->quantity }},
                            maxQty: {{ $item->product->stock }},
                            subtotal: {{ $item->subtotal }},
                            loading: false,
                            error: '',
                            formatRupiah(n) {
                                return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            },
                            async updateQty(newQty) {
                                if (newQty < 1) return;
                                if (newQty > this.maxQty) {
                                    this.error = 'Maksimal stok: ' + this.maxQty + ' item.';
                                    return;
                                }
                                this.error = '';
                                this.loading = true;
                                try {
                                    const res = await fetch('{{ route('cart.update', $item->id) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({ quantity: newQty }),
                                    });
                                    const data = await res.json();
                                    if (res.ok && data.success) {
                                        this.qty = newQty;
                                        this.subtotal = data.subtotal;
                                        $dispatch('update-total', { total: data.formatted_total });
                                    } else {
                                        this.error = data.message;
                                    }
                                } catch(e) {
                                    this.error = 'Terjadi kesalahan. Coba lagi.';
                                } finally {
                                    this.loading = false;
                                }
                            }
                        }"
                    >
                        <div class="p-4 sm:p-5">
                            <div class="flex gap-4">

                                {{-- Product Image --}}
                                <div class="relative flex-shrink-0">
                                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden bg-gray-100">
                                        <img src="{{ $item->product->image_url }}"
         alt="{{ $item->product->name }}"
         class="w-full h-full object-cover"
                                            loading="lazy"
                                        >
                                    </div>

                                    {{-- Status Badges --}}
                                    @if($isExpired)
                                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                            Expired
                                        </span>
                                    @elseif($isOutOfStock)
                                        <span class="absolute -top-1.5 -right-1.5 bg-gray-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                            Habis
                                        </span>
                                    @elseif($exceedsStock)
                                        <span class="absolute -top-1.5 -right-1.5 bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                            Melebihi
                                        </span>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-gray-800 text-sm sm:text-base leading-snug line-clamp-2">
                                                {{ $item->product->name }}
                                            </h3>

                                            {{-- Expiry Info --}}
                                            @if(!$isExpired)
                                                @if($daysLeft <= 1)
                                                    <p class="text-xs text-orange-500 font-medium mt-0.5">
                                                        Expired dalam &lt;{{ $daysLeft <= 0 ? '1 hari' : $daysLeft . ' hari' }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        Expired dalam {{ $daysLeft }} hari
                                                    </p>
                                                @endif
                                            @else
                                                <p class="text-xs text-red-500 font-medium mt-0.5">
                                                    Item ini sudah expired
                                                </p>
                                            @endif

                                            {{-- Stock info --}}
                                            @if(!$isExpired)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Stok tersedia:
                                                    <span class="{{ $item->product->stock <= 5 ? 'text-orange-500 font-medium' : 'text-gray-500' }}">
                                                        {{ $item->product->stock }} item
                                                    </span>
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Delete Button --}}
                                        <form
                                            action="{{ route('cart.destroy', $item->id) }}"
                                            method="POST"
                                            class="flex-shrink-0"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition"
                                                title="Hapus dari keranjang"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Price --}}
                                    <p class="text-blue-600 font-bold text-sm sm:text-base mt-2">
                                        {{ \App\Helpers\FormatHelper::rupiah($item->product->price) }}
                                    </p>

                                    {{-- Qty + Subtotal Row --}}
                                    <div class="flex items-center justify-between mt-3 flex-wrap gap-2">

                                        {{-- Quantity Control --}}
                                        @if(!$isExpired && !$isOutOfStock)
                                            <div class="flex items-center gap-1">
                                                <button
                                                    @click="updateQty(qty - 1)"
                                                    :disabled="qty <= 1 || loading"
                                                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600
                                                           hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                                    </svg>
                                                </button>

                                                <div class="relative">
                                                    <input
                                                        type="number"
                                                        x-model.number="qty"
                                                        @change="updateQty(qty)"
                                                        :max="maxQty"
                                                        min="1"
                                                        :disabled="loading"
                                                        class="w-14 h-8 text-center text-sm font-semibold border border-gray-200 rounded-lg
                                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                               disabled:opacity-40 [appearance:textfield]
                                                               [&::-webkit-outer-spin-button]:appearance-none
                                                               [&::-webkit-inner-spin-button]:appearance-none"
                                                    >
                                                    <div
                                                        x-show="loading"
                                                        class="absolute inset-0 flex items-center justify-center bg-white rounded-lg"
                                                    >
                                                        <svg class="w-3.5 h-3.5 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <button
                                                    @click="updateQty(qty + 1)"
                                                    :disabled="qty >= maxQty || loading"
                                                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600
                                                           hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                                <span>Qty: {{ $item->quantity }}</span>
                                            </div>
                                        @endif

                                        {{-- Subtotal --}}
                                        <p class="text-gray-800 font-bold text-sm sm:text-base">
                                            <span x-text="formatRupiah(subtotal)">
                                                {{ \App\Helpers\FormatHelper::rupiah($item->subtotal) }}
                                            </span>
                                        </p>
                                    </div>

                                    {{-- Error Message --}}
                                    <p
                                        x-show="error"
                                        x-text="error"
                                        x-transition
                                        class="text-xs text-red-500 mt-2 font-medium"
                                    ></p>

                                    {{-- Blocked item warning --}}
                                    @if($exceedsStock)
                                        <p class="text-xs text-orange-500 font-medium mt-2">
                                            Jumlah melebihi stok. Kurangi quantity ke maks. {{ $item->product->stock }}.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== RIGHT: ORDER SUMMARY ===== --}}
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6"
                    x-data="{ total: '{{ \App\Helpers\FormatHelper::rupiah($validTotal) }}' }"
                    @update-total.window="total = $event.detail.total"
                >
                    <h2 class="text-base font-bold text-gray-900 mb-5">Ringkasan Pesanan</h2>

                    {{-- Item Count --}}
                    <div class="space-y-3 mb-5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total Produk</span>
                            <span class="font-medium text-gray-700">{{ $cartItems->count() }} item</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Bisa di-checkout</span>
                            <span class="font-medium {{ $cartItems->filter(fn($i) => $i->isCheckable())->count() > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $cartItems->filter(fn($i) => $i->isCheckable())->count() }} item
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Pengiriman</span>
                            <span class="font-medium text-gray-500">Dihitung saat checkout</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-4 mb-5">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-semibold">Total Valid</span>
                            <span
                                class="text-blue-600 font-bold text-lg"
                                x-text="total"
                            >
                                {{ \App\Helpers\FormatHelper::rupiah($validTotal) }}
                            </span>
                        </div>
                        @if($blockReasons->isNotEmpty())
                            <p class="text-xs text-gray-400 mt-1">
                                *Total hanya menghitung item yang bisa di-checkout
                            </p>
                        @endif
                    </div>

                    {{-- Checkout Button --}}
                    @if($canCheckout)
                        <form action="{{ route('orders.checkout') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label class="block text-sm font-medium mb-2">
            Metode Pembayaran
        </label>

        <label class="flex items-center gap-2 mb-2">
            <input
                type="radio"
                name="payment_method"
                value="cash"
                checked
            >
            Bayar di Tempat (Cash)
        </label>

        <label class="flex items-center gap-2">
            <input
                type="radio"
                name="payment_method"
                value="transfer"
            >
            Transfer Bank
        </label>
    </div>

    <button
        type="submit"
        class="w-full bg-blue-600 text-white rounded-xl py-3"
    >
        Lanjut Checkout
    </button>
</form>
                    @else
                        <button
                            disabled
                            title="Selesaikan masalah di atas untuk melanjutkan checkout"
                            class="w-full bg-gray-300 text-gray-500 font-semibold py-3.5 rounded-xl cursor-not-allowed text-sm flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Checkout Tidak Tersedia
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Selesaikan masalah item di atas untuk melanjutkan
                        </p>
                    @endif

                    {{-- Continue Shopping --}}
                    <a
                        href="{{ route('products.index') }}"
                        class="mt-3 w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 font-medium py-3 rounded-xl transition text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Lanjut Belanja
                    </a>

                    {{-- Security Badge --}}
                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-center gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-xs">Transaksi Aman & Terenkripsi</span>
                    </div>
                </div>
            </div>

        </div>

        @endif {{-- End @if cartItems not empty --}}

    </div>
</div>

@push('scripts')
<script>
    function cartManager() {
        return {
            init() {
                // Alpine.js Cart Manager ready
            },

            // Fungsi untuk memicu SweetAlert2 sebelum menghapus keranjang
            confirmClearCart() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Semua produk di keranjang akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Merah Tailwind (bg-red-500)
                    cancelButtonColor: '#6b7280',  // Abu-abu Tailwind (bg-gray-500)
                    confirmButtonText: 'Ya, kosongkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true // Opsional: Menaruh tombol 'Batal' di kiri dan 'Ya' di kanan
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user klik 'Ya', submit form secara manual
                        document.getElementById('clear-cart-form').submit();
                    }
                });
            }
        };
    }
</script>
@endpush

@endsection
