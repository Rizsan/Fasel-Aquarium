{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div x-data="{}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola semua pengguna aplikasi</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm shadow-sm shadow-blue-200 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $statCards = [
                ['label' => 'Total Pengguna',      'value' => $stats['total'],          'color' => 'blue',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Admin',               'value' => $stats['admin'],          'color' => 'purple', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['label' => 'Pelanggan', 'value' => $stats['user'],       'color' => 'emerald','icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label' => 'Baru Bulan Ini',      'value' => $stats['new_this_month'], 'color' => 'amber',  'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
            ];
        @endphp

        @foreach($statCards as $card)
            @php
                $colorMap = [
                    'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-100 text-blue-600',   'text' => 'text-blue-600'],
                    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-100 text-purple-600','text' => 'text-purple-600'],
                    'emerald'=> ['bg' => 'bg-emerald-50','icon' => 'bg-emerald-100 text-emerald-600','text' => 'text-emerald-600'],
                    'amber'  => ['bg' => 'bg-amber-50',  'icon' => 'bg-amber-100 text-amber-600',  'text' => 'text-amber-600'],
                ];
                $c = $colorMap[$card['color']];
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $c['icon'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold {{ $c['text'] }}">{{ number_format($card['value']) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Filter Bar --}}
        <div class="p-5 border-b border-gray-100">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">

                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                {{-- Filter Role --}}
                <select name="role"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[140px]">
                    <option value="">Semua Role</option>
                    <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Pelanggan</option>
                </select>

                {{-- Filter Status --}}
                <select name="status"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[140px]">
                    <option value="">Semua Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                {{-- Buttons --}}
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition flex-shrink-0">
                    Filter
                </button>

                @if(request()->hasAny(['search','role','status']))
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 border border-gray-200 hover:border-gray-300 text-gray-600 text-sm font-semibold rounded-xl transition flex-shrink-0 text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Pengguna</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Role</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Bergabung</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Last Login</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Pesanan</th>
                        <th class="text-right px-6 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">

                            {{-- User Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->profile_photo_url)
    <img
        src="{{ $user->profile_photo_url }}"
        alt="{{ $user->name }}"
        class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
@else
    <div
        class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">
        {{ $user->initials }}
    </div>
@endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                                        <p class="text-gray-400 text-xs truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                    {{ $user->role_label }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            {{-- Joined --}}
                            <td class="px-4 py-4 text-gray-500 text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            {{-- Last Login --}}
                            <td class="px-4 py-4 text-gray-500 text-xs">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </td>

                            {{-- Orders Count --}}
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-700">{{ $user->orders_count }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">

                                    {{-- Detail --}}
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       title="Detail"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Toggle Status --}}
                                    @if($user->id !== auth()->id())
                                        <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="button"
                                                onclick="confirmToggleStatus({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 transition
                                                {{ $user->is_active ? 'hover:text-orange-600 hover:bg-orange-50' : 'hover:text-emerald-600 hover:bg-emerald-50' }}">
                                                @if($user->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete --}}
                                    @if($user->id !== auth()->id())
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                title="Hapus"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Tidak ada pengguna ditemukan</p>
                                    <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ===== SWEETALERT2 SCRIPTS ===== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Flash Notifications --}}
@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: @json(session('success')),
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: @json(session('error')),    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
});
</script>
@endif

@if(session('info'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'info',
    title: @json(session('info')),    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
});
</script>
@endif

<script>
function confirmDeleteUser(userId, userName) {
    Swal.fire({
    title: 'Hapus Pengguna?',
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
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}

function confirmToggleStatus(userId, userName, isActive) {
    const action = isActive ? 'nonaktifkan' : 'aktifkan';
    const icon   = isActive ? 'warning' : 'question';
    const btnColor = isActive ? '#d97706' : '#059669';

    Swal.fire({
    title: (isActive ? 'Nonaktifkan' : 'Aktifkan') + ' Pengguna?',
        html: `Akun <strong>${userName}</strong> akan di-${action}.`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, ' + action.charAt(0).toUpperCase() + action.slice(1),
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('toggle-form-' + userId).submit();
        }
    });
}

function confirmResetPassword(userId, userName) {
    Swal.fire({
    title: 'Reset Password?',
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
            document.getElementById('reset-form-' + userId).submit();
        }
    });
}
</script>
@endpush

@endsection
