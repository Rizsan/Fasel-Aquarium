{{-- resources/views/orders/payment.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-lg">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Selesaikan Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-2">
                Pesanan <span class="font-mono font-semibold text-gray-700">{{ $order->order_number }}</span>
            </p>
        </div>

        {{-- Payment Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Order Summary --}}
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-700 mb-4">Ringkasan Pesanan</h2>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="{{ $item->product->image_url }}"
         alt="{{ $item->product->name }}"
         class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 font-medium line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->formatted_price }} x {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-800">{{ $item->formatted_subtotal }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Total --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-gray-900">Total Pembayaran</span>
                    <span class="font-bold text-blue-600 text-xl">{{ $order->formatted_total_price }}</span>
                </div>
            </div>

            {{-- Pay Button --}}
            <div class="p-6">
                <button
                    id="pay-btn"
                    onclick="openSnapPopup()"
                    class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.98]
                           text-white font-bold py-4 rounded-2xl transition text-base shadow-lg shadow-blue-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Sekarang
                </button>

                <p class="text-center text-xs text-gray-400 mt-3">
                    Pembayaran aman & diproses oleh Midtrans
                </p>

                {{-- Secure badges --}}
                <div class="flex items-center justify-center gap-4 mt-4">
                    <div class="flex items-center gap-1 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-xs">SSL Encrypted</span>
                    </div>
                    <div class="flex items-center gap-1 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-xs">3D Secure</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel link --}}
        <div class="text-center mt-4">
            <a href="{{ route('orders.show', $order->id) }}"
               class="text-sm text-gray-400 hover:text-gray-600 transition">
                Bayar nanti
            </a>
        </div>

    </div>
</div>

{{-- Loading overlay --}}
<div id="loading-overlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 flex flex-col items-center gap-4 shadow-2xl">
        <svg class="w-10 h-10 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <p class="text-sm text-gray-600 font-medium">Memuat payment gateway...</p>
    </div>
</div>

@push('scripts')
{{-- Midtrans Snap.js --}}
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
    const SNAP_TOKEN   = @json($order->snap_token);
    const FINISH_URL   = @json(route('orders.finish', $order->id));
    const ORDER_SHOW   = @json(route('orders.show', $order->id));

    function openSnapPopup() {
        document.getElementById('loading-overlay').classList.remove('hidden');

        window.snap.pay(SNAP_TOKEN, {
            onSuccess: function(result) {
                console.log('Midtrans success:', result);
                hideOverlay();
                window.location.href = FINISH_URL
                    + '?transaction_status=settlement'
                    + '&order_id=' + encodeURIComponent(result.order_id);
            },
            onPending: function(result) {
                console.log('Midtrans pending:', result);
                hideOverlay();
                window.location.href = FINISH_URL
                    + '?transaction_status=pending'
                    + '&order_id=' + encodeURIComponent(result.order_id);
            },
            onError: function(result) {
                console.error('Midtrans error:', result);
                hideOverlay();
                window.location.href = FINISH_URL
                    + '?transaction_status=error';
            },
            onClose: function() {
                console.log('Midtrans popup closed');
                hideOverlay();
            }
        });
    }

    function hideOverlay() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }

    // Auto-open snap jika baru diarahkan dari cart
    @if(session('success'))
        setTimeout(() => openSnapPopup(), 800);
    @endif
</script>
@endpush

@endsection
