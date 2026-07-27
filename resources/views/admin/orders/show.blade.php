{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 hover:border-blue-300 hover:text-blue-600 transition text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-sm font-mono text-gray-500">{{ $order->order_number }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>

            @if($order->isUpdatableByAdmin())
                <a href="{{ route('admin.orders.edit', $order->id) }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Update Status
                </a>
            @endif
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3" x-transition>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3" x-transition>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900">Informasi Pelanggan</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600
                                    flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                Member sejak {{ $order->user->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900">Item Pesanan</h2>
                    <span class="text-xs text-gray-400">{{ $order->items->count() }} item</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-5">
                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="{{ $item->product->image_url }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->formatted_price }} x {{ $item->quantity }} item
                                </p>
                            </div>
                            <p class="text-sm font-bold text-gray-800">{{ $item->formatted_subtotal }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-blue-600">{{ $order->formatted_total_price }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Transaction Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900">Informasi Transaksi</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">No. Pesanan</p>
                        <p class="text-sm font-mono font-bold text-gray-800 mt-1">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Status Pesanan</p>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Tanggal Pesan</p>
                        <p class="text-sm text-gray-800 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($order->payment_type)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Metode Bayar</p>
                            <p class="text-sm text-gray-800 mt-1">
                                {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
                            </p>
                        </div>
                    @endif
                    @if($order->transaction_status)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Status Midtrans</p>
                            <p class="text-sm text-gray-800 mt-1 capitalize">{{ $order->transaction_status }}</p>
                        </div>
                    @endif
                    @if($order->transaction_id)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">ID Transaksi</p>
                            <p class="text-sm font-mono text-gray-800 mt-1 break-all">{{ $order->transaction_id }}</p>
                        </div>
                    @endif
                    @if($order->paid_at)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Dibayar Pada</p>
                            <p class="text-sm text-gray-800 mt-1">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                    @if($order->notes)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Catatan</p>
                            <p class="text-sm text-gray-800 mt-1">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Action --}}
            @if($order->isUpdatableByAdmin())
                <a href="{{ route('admin.orders.edit', $order->id) }}"
                   class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 rounded-xl transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Update Status Pesanan
                </a>
            @endif
        </div>
    </div>

</div>
@endsection
