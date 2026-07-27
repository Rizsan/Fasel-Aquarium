{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Detail Pengguna - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200
                      hover:border-gray-300 text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengguna</h1>
                <p class="text-gray-500 text-sm">ID #{{ $user->id }}</p>
            </div>
        </div>
            @if($user->id !== auth()->id())
                <form id="delete-form-show" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button
                        type="button"
                        onclick="confirmDeleteUserShow('{{ addslashes($user->name) }}')"
                        class="inline-flex items-center gap-2 border border-red-200 text-red-600
                               hover:bg-red-50 text-sm font-semibold px-4 py-2.5 rounded-xl transition active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT COLUMN: Profile Card ===== --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Profile Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Cover --}}
                <div class="h-24 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600"></div>

                {{-- Avatar & Info --}}
                <div class="px-6 pb-6">
                    <div class="-mt-12 mb-4">
                        <img
                            src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                            alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg"
                        >
                    </div>

                    <div class="space-y-1 mb-4">
                        <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>

                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                            {{ $user->role_label }}
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                            {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Account Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Informasi Akun</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Bergabung</span>
                        <span class="font-medium text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Login</span>
                        <span class="font-medium text-gray-700">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Diperbarui</span>
                        <span class="font-medium text-gray-700">{{ $user->updated_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">User ID</span>
                        <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">

                    {{-- Reset Password --}}
                    <form id="reset-form-show" action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                        @csrf
                        <button
                            type="button"
                            onclick="confirmResetPasswordShow('{{ addslashes($user->name) }}')"
                            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200
                                   hover:border-purple-300 hover:bg-purple-50 text-gray-600 hover:text-purple-700
                                   text-sm font-medium transition"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Reset Password
                        </button>
                    </form>

                    {{-- Toggle Status --}}
                    @if($user->id !== auth()->id())
                        <form id="toggle-form-show" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                type="button"
                                onclick="confirmToggleStatusShow('{{ addslashes($user->name) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium transition
                                       {{ $user->is_active
                                            ? 'hover:border-orange-300 hover:bg-orange-50 text-gray-600 hover:text-orange-700'
                                            : 'hover:border-emerald-300 hover:bg-emerald-50 text-gray-600 hover:text-emerald-700' }}"
                            >
                                @if($user->is_active)
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Nonaktifkan Akun
                                @else
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Aktifkan Akun
                                @endif
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>

        {{-- ===== RIGHT COLUMN: Stats + Orders ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs">Total Pesanan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $user->orders()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs">Total Transaksi</p>
                            <p class="text-lg font-bold text-emerald-600">
                                {{ \App\Helpers\FormatHelper::rupiah($totalTransaksi) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pesanan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Riwayat Pesanan Terbaru</h3>
                    <span class="text-xs text-gray-400">5 pesanan terakhir</span>
                </div>

                @if($user->orders->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-gray-400 text-sm">Belum ada pesanan</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($user->orders as $order)
                            @php
                                $statusConfig = [
                                    'pending'    => 'bg-amber-100 text-amber-700',
                                    'paid'       => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-purple-100 text-purple-700',
                                    'shipped'    => 'bg-indigo-100 text-indigo-700',
                                    'completed'  => 'bg-emerald-100 text-emerald-700',
                                    'cancelled'  => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-gray-700">
                                        {{ \App\Helpers\FormatHelper::rupiah($order->total_price) }}
                                    </span>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusConfig[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="text-gray-400 hover:text-blue-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($user->orders()->count() > 5)
                        <div class="px-6 py-3 border-t border-gray-100 text-center">
                            <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}"
                               class="text-sm text-blue-600 hover:text-blue-700 font-medium transition">
                                Lihat semua {{ $user->orders()->count() }} pesanan
                            </a>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Flash notifications
@if(session('success'))
    Swal.fire({
        toast: true, position: 'top-end', icon: 'success',
        showConfirmButton: false, timer: 3500, timerProgressBar: true,
    });
@endif

@if(session('error'))
    Swal.fire({
        toast: true, position: 'top-end', icon: 'error',
        showConfirmButton: false, timer: 4000, timerProgressBar: true,
    });
@endif

@if(session('info'))
    Swal.fire({
        toast: true, position: 'top-end', icon: 'info', showConfirmButton: false, timer: 3500, timerProgressBar: true,
    });
@endif

function confirmDeleteUserShow(userName) {
    Swal.fire({
        html: `Akun <strong>${userName}</strong> akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-show').submit();
        }
    });
}

function confirmToggleStatusShow(userName, isActive) {
    const action   = isActive ? 'nonaktifkan' : 'aktifkan';
    const btnColor = isActive ? '#d97706' : '#059669';
    Swal.fire({        html: `Akun <strong>${userName}</strong> akan di-${action}.`,
        icon: isActive ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, ' + action.charAt(0).toUpperCase() + action.slice(1),
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('toggle-form-show').submit();
        }
    });
}

function confirmResetPasswordShow(userName) {
    Swal.fire({
        html: `Password <strong>${userName}</strong> akan direset ke password default.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reset-form-show').submit();
        }
    });
}
</script>
@endpush

@endsection