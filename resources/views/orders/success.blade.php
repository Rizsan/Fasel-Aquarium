{{-- resources/views/orders/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Berhasil!')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-blue-50 flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-lg text-center">

        {{-- Success Animation --}}
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center animate-[scale-in_0.5s_ease-out_forwards]">
                    <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="absolute inset-0 rounded-full border-4 border-emerald-300 animate-ping opacity-30"></div>
            </div>
        </div>

        @if($order->payment_method === 'cash')
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Pesanan Berhasil Dibuat!
            </h1>

            <p class="text-gray-500 text-base mb-8">
                Terima kasih! Silakan lakukan pembayaran saat mengambil pesanan.
            </p>
        @else
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Pembayaran Berhasil!
            </h1>

            <p class="text-gray-500 text-base mb-8">
                Terima kasih! Pesanan Anda sedang diproses.
            </p>
        @endif

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm text-left mb-6">

            {{-- Order Number --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">No. Pesanan</p>
                        <p class="font-mono font-bold text-gray-800 text-lg">{{ $order->order_number }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        @if($order->payment_method === 'cash')
                            <p class="text-xs text-gray-400">Pembayaran</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                Dibayar saat pengambilan
                            </p>
                        @else
                            <p class="text-xs text-gray-400">Dibayar Pada</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                {{ $order->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}
                            </p>
                        @endif
                    </div>
                    @if($order->payment_type)
                        <div>
                            <p class="text-xs text-gray-400">Metode Pembayaran</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
                            </p>
                        </div>
                    @endif
                    @if($order->payment_method !== 'cash' && $order->transaction_id)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400">ID Transaksi</p>
                            <p class="text-sm font-mono text-gray-800 mt-1">{{ $order->transaction_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Items --}}
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3 px-6 py-4">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ $item->product->image_url }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->quantity }}x {{ $item->formatted_price }}</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $item->formatted_subtotal }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="px-6 py-4 bg-emerald-50 rounded-b-3xl">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-900">Total Dibayar</span>
                    <span class="font-bold text-emerald-600 text-xl">{{ $order->formatted_total_price }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('orders.show', $order->id) }}"
               class="flex items-center justify-center gap-2 border border-gray-200 text-gray-700 hover:border-blue-300 hover:text-blue-600 font-semibold py-3.5 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Detail Pesanan
            </a>

            <a href="{{ route('orders.download-pdf', $order->id) }}"
               class="flex items-center justify-center gap-2 border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 font-semibold py-3.5 rounded-xl transition text-sm">
                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3M4 17v1a2 2 0 002 2h12a2 2 0 002-2v-1M7 7V5a5 5 0 0110 0v2"/>
                </svg>
                Download PDF
            </a>

            <a href="{{ route('products.index') }}"
               class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md shadow-blue-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Lanjut Belanja
            </a>
        </div>

    </div>
</div>
@endsection