{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Riwayat semua transaksi Anda</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Lanjut Belanja
            </a>
        </div>

        {{-- Flash Messages --}}
        @foreach(['success' => 'emerald', 'error' => 'red', 'info' => 'blue'] as $type => $color)
            @if(session($type))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="flex items-center gap-3 bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-800 rounded-xl px-4 py-3 mb-6"
                     x-transition>
                    <p class="text-sm font-medium">{{ session($type) }}</p>
                </div>
            @endif
        @endforeach

        {{-- Empty State --}}
        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-20 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Pesanan</h2>
                <p class="text-gray-400 text-sm mb-6">Anda belum pernah melakukan pemesanan.</p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition">
                    Mulai Belanja
                </a>
            </div>
        @else

        {{-- Orders List --}}
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">

                    {{-- Order Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">No. Pesanan</p>
                                <p class="text-sm font-bold text-gray-800 font-mono">{{ $order->order_number }}</p>
                            </div>
                            <div class="h-8 w-px bg-gray-200"></div>
                            <div>
                                <p class="text-xs text-gray-400">Tanggal</p>
                                <p class="text-sm text-gray-700">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    {{-- Order Items Preview --}}
                    <div class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Gambar produk (max 3) --}}
                            <div class="flex -space-x-2">
                                @foreach($order->items->take(3) as $item)
                                    <div class="w-10 h-10 rounded-lg border-2 border-white overflow-hidden bg-gray-100 shadow-sm">
                                        <img src="{{ $item->product->image_url }}"
         alt="{{ $item->product->name }}"
         class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <div class="w-10 h-10 rounded-lg border-2 border-white bg-gray-200 shadow-sm flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600">+{{ $order->items->count() - 3 }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700 line-clamp-1">
                                    {{ $order->items->pluck('product.name')->join(', ') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $order->items->count() }} produk
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Order Footer --}}
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div>
                            <p class="text-xs text-gray-400">Total Pembayaran</p>
                            <p class="text-base font-bold text-blue-600">{{ $order->formatted_total_price }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->isPendingPayment())
                                <a href="{{ route('orders.payment', $order->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Bayar Sekarang
                                </a>
                            @endif

                            <a href="{{ route('orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1.5 border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold px-4 py-2 rounded-lg transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

        @endif

    </div>
</div>
@endsection
