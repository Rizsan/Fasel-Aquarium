{{-- resources/views/admin/orders/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Update Status Pesanan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.show', $order->id) }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 hover:border-blue-300 hover:text-blue-600 transition text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Update Status Pesanan</h1>
            <p class="text-sm font-mono text-gray-500">{{ $order->order_number }}</p>
        </div>
    </div>

    {{-- Current Status Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-900">Status Saat Ini</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600
                                flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->order_number }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                    <p class="text-sm font-bold text-blue-600 mt-1">{{ $order->formatted_total_price }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-900">Update Status</h2>
        </div>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            {{-- Status Selection --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">
                    Pilih Status Baru
                    <span class="text-red-500">*</span>
                </label>

                @php
                    $cards = [
                        'processing' => [
                            'title' => 'Sedang Diproses',
                            'desc' => 'Pesanan sedang disiapkan.',
                            'icon' => '⚙️',
                            'border' => 'border-indigo-500',
                            'bg' => 'bg-indigo-50',
                            'ring' => 'peer-checked:ring-indigo-500'
                        ],
                        'completed' => [
                            'title' => 'Selesai',
                            'desc' => 'Pesanan diterima pelanggan.',
                            'icon' => '✅',
                            'border' => 'border-green-500',
                            'bg' => 'bg-green-50',
                            'ring' => 'peer-checked:ring-green-500'
                        ],
                        'cancelled' => [
                            'title' => 'Dibatalkan',
                            'desc' => 'Batalkan pesanan.',
                            'icon' => '❌',
                            'border' => 'border-red-500',
                            'bg' => 'bg-red-50',
                            'ring' => 'peer-checked:ring-red-500'
                        ],
                    ];
                @endphp

                <div class="grid gap-4">
                    @foreach($availableStatuses as $status)
                        @php
                            $card = $cards[$status];
                        @endphp

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="status"
                                value="{{ $status }}"
                                class="peer sr-only"
                                {{ old('status', $order->status) == $status ? 'checked' : '' }}
                            >

                            <div class="
                                    border-2 border-gray-200
                                    rounded-2xl
                                    p-5
                                    transition
                                    duration-200
                                    hover:border-blue-300
                                    hover:shadow-md
                                    peer-checked:{{ $card['border'] }}
                                    peer-checked:{{ $card['bg'] }}
                                    peer-checked:ring-2
                                    {{ $card['ring'] }}
                                "
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-white shadow flex items-center justify-center text-3xl">
                                            {{ $card['icon'] }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800">
                                                {{ $card['title'] }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $card['desc'] }}
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="
                                            w-6 h-6
                                            rounded-full
                                            border-2 border-gray-300
                                            flex items-center justify-center
                                            peer-checked:border-blue-600
                                        ">
                                            <div class="
                                                hidden
                                                peer-checked:block
                                                w-3 h-3
                                                rounded-full
                                                bg-blue-600
                                            "></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('status')
                    <p class="text-sm text-red-500 mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Catatan Admin --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan Admin
                </label>
                <textarea
                    name="notes"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-4 resize-none"
                    placeholder="Misalnya: Misalnya: Pesanan sedang disiapkan atau siap diambil pelanggan..."
                >{{ old('notes', $order->notes) }}</textarea>
            </div>

            {{-- Kotak Informasi --}}
            <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
    <div class="flex gap-3">
        <div class="text-2xl">ℹ️</div>

        <div>
            <h4 class="font-semibold text-blue-800">
                Informasi
            </h4>

            <p class="text-sm text-blue-700 mt-1">
                Perubahan status akan langsung terlihat oleh pelanggan.
                Pastikan pesanan benar-benar telah diproses sebelum menandainya sebagai selesai.
            </p>
            </div>
        </div>
    </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 transition text-gray-700 font-medium flex items-center">
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition shadow">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection