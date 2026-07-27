{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-6 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Pesanan
        </a>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 mb-6" x-transition>
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-6" x-transition>
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 mb-6" x-transition>
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Order Detail --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Order Info Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-base font-bold text-gray-900">Informasi Pesanan</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">No. Pesanan</dt>
                                <dd class="mt-1 text-sm font-bold text-gray-800 font-mono">{{ $order->order_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">Tanggal Pesan</dt>
                                <dd class="mt-1 text-sm text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">Metode Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-800">
                                    {{ $order->payment_type ? strtoupper(str_replace('_', ' ', $order->payment_type)) : '-' }}
                                </dd>
                            </div>
                            @if($order->paid_at)
                                <div>
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">Dibayar Pada</dt>
                                    <dd class="mt-1 text-sm text-gray-800">{{ $order->paid_at->format('d M Y, H:i') }}</dd>
                                </div>
                            @endif
                            @if($order->transaction_id)
                                <div class="col-span-2">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">ID Transaksi</dt>
                                    <dd class="mt-1 text-sm text-gray-800 font-mono">{{ $order->transaction_id }}</dd>
                                </div>
                            @endif
                            @if($order->notes)
                                <div class="col-span-2">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wide">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-800">{{ $order->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-900">Item Pesanan</h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 p-5">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
    <img src="{{ $item->product->image_url }}"
         alt="{{ $item->product->name }}"
         class="w-full h-full object-cover">
</div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2">
                                        {{ $item->product->name }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $item->formatted_price }} x {{ $item->quantity }}
                                    </p>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-bold text-gray-800">{{ $item->formatted_subtotal }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Summary & Actions --}}
            <div class="space-y-4">

                {{-- Total Summary --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Ringkasan Pembayaran</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="text-gray-700 font-medium">{{ $order->formatted_total_price }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Biaya Pengiriman</span>
                            <span class="text-gray-500">Gratis</span>
                        </div>
                        <div class="border-t border-dashed border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-blue-600 text-lg">{{ $order->formatted_total_price }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                @if($order->isPendingPayment())
                    <a href="{{ route('orders.payment', $order->id) }}"
                       class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md shadow-orange-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Selesaikan Pembayaran
                    </a>
                @endif

                @if($order->isPaid())
                    <a href="{{ route('orders.success', $order->id) }}"
                       class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 rounded-xl transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Konfirmasi
                    </a>
                @endif

                <a href="{{ route('orders.index') }}"
                   class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 font-medium py-3 rounded-xl transition text-sm">
                    Semua Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
