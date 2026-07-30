{{-- resources/views/admin/profile/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="space-y-6" x-data="adminProfile()">

    {{-- ============================================================
         FLASH MESSAGES (SWEETALERT2)
         ============================================================ --}}
    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session("success") }}',
            confirmButtonColor: '#2563eb'
        });
    });
    </script>
    @endif

    @if(session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session("error") }}',
            confirmButtonColor: '#dc2626'
        });
    });
    </script>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

            <p class="text-sm font-semibold">
                Terdapat kesalahan:
            </p>
        </div>

        <ul class="list-disc list-inside text-sm space-y-1 ml-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ============================================================
         PAGE HEADER
         ============================================================ --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Profil Admin
            </h1>

            <p class="text-gray-500 text-sm mt-1">
                Kelola informasi dan keamanan akun administrator
            </p>
        </div>
    </div>

    {{-- ============================================================
         QUICK STATS
         ============================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-2xl font-bold text-blue-600">
                {{ number_format($stats['total_users']) }}
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Total User
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-2xl font-bold text-indigo-600">
                {{ number_format($stats['total_orders']) }}
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Total Order
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-2xl font-bold text-amber-600">
                {{ number_format($stats['pending_orders']) }}
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Order Pending
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-2xl font-bold text-emerald-600">
                Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}jt
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Total Revenue
            </p>
        </div>
    </div>

    {{-- ============================================================
         PROFILE HEADER CARD
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="h-24 bg-gradient-to-r from-gray-800 via-gray-900 to-black"></div>

        <div class="px-6 pb-6">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-10 mb-4 gap-3">

                <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-md overflow-hidden shrink-0">

                    @if(!empty($user->profile_photo_url))
                        <img
                            src="{{ $user->profile_photo_url }}"
                            alt="{{ $user->name }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">
                                {{ $user->initials }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 sm:mb-1">

                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-gray-900 text-white border border-gray-700">
                        Administrator
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>

                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-900">
                {{ $user->name }}
            </h2>

            <p class="text-gray-500 text-sm">
                {{ $user->email }}
            </p>

        </div>
    </div>

    <form
        id="delete-photo-form"
        method="POST"
        action="{{ route('admin.profile.photo.delete') }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>

    {{-- ============================================================
         UPDATE PROFILE FORM
         ============================================================ --}}
    <form
        method="POST"
        action="{{ route('admin.profile.update') }}"
        enctype="multipart/form-data"
        @submit.prevent="handleSubmit($event)"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT SIDE --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- FOTO PROFIL --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                    <h3 class="text-sm font-bold text-gray-900 mb-5">
                        Foto Profil
                    </h3>

                    <div class="flex flex-col items-center gap-4">

                        <div class="w-28 h-28 rounded-2xl overflow-hidden border-2 border-gray-200">

                            {{-- Preview --}}
                            <img
                                x-show="previewUrl"
                                :src="previewUrl"
                                class="w-full h-full object-cover"
                                alt="Preview"
                                x-cloak
                            >

                            {{-- Foto dari database --}}
                            <div
                                x-show="!previewUrl"
                                class="w-full h-full"
                                x-cloak
                            >

                                @if(!empty($user->profile_photo_url))
                                    <img
                                        src="{{ $user->profile_photo_url }}"
                                        alt="{{ $user->name }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold">
                                            {{ $user->initials }}
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </div>

                        <label
                            for="profile_photo"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl cursor-pointer transition"
                        >
                            Pilih Foto
                        </label>

                        @if(!empty($user->profile_photo))
                        <button
                            type="button"
                            onclick="confirmDeletePhoto()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>

                            Hapus Foto
                        </button>
                        @endif

                        <input
                            type="file"
                            id="profile_photo"
                            name="profile_photo"
                            accept="image/*"
                            class="hidden"
                            @change="handlePhotoPreview($event)"
                        >

                        <p class="text-xs text-gray-400 text-center">
                            JPG, PNG, WEBP. Maks. 2MB
                        </p>

                    </div>
                </div>

                {{-- ACCOUNT INFO --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">
                        Info Akun
                    </h3>

                    <div class="space-y-3">

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Role</span>
                            <span class="text-xs font-semibold text-gray-900">
                                {{ $user->role_label }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Status</span>
                            <span class="text-xs font-semibold text-emerald-600">
                                Aktif
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Bergabung</span>
                            <span class="text-xs font-semibold text-gray-900">
                                {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- INFORMASI --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                    <h3 class="text-sm font-bold text-gray-900 mb-5">
                        Informasi Pribadi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Role
                            </label>

                            <input
                                type="text"
                                value="{{ $user->role_label }}"
                                disabled
                                class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-2.5 text-sm text-gray-400"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                placeholder="Masukan Alamat Toko"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            >{{ old('address', $user->address) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- PASSWORD --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                    <h3 class="text-sm font-bold text-gray-900 mb-5">
                        Keamanan Akun
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Password Saat Ini
                            </label>

                            <div class="relative">
                                <input
                                    :type="showCurrentPassword ? 'text' : 'password'"
                                    name="current_password"
                                    id="current_password"
                                    autocomplete="new-password"
                                    readonly
                                    onfocus="this.removeAttribute('readonly');"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Password Baru
                            </label>

                            <input
                                :type="showNewPassword ? 'text' : 'password'"
                                name="password"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Konfirmasi Password Baru
                            </label>

                            <input
                                :type="showNewPassword ? 'text' : 'password'"
                                name="password_confirmation"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"
                            >
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="flex items-center justify-between bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:px-6">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center justify-center gap-2 h-12 min-w-[190px] px-6 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm"
                    >
                        {{-- Loader Spinner --}}
                        <svg
                            x-show="loading"
                            class="w-4 h-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                            style="display: none;"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>

                        {{-- Icon Centang --}}
                        <svg
                            x-show="!loading"
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>

                        {{-- Teks Tombol Statis dengan Kondisi Alpine --}}
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
function adminProfile() {
    return {
        previewUrl: null,
        showCurrentPassword: false,
        showNewPassword: false,
        loading: false,

        handleSubmit(event) {
            Swal.fire({
                title: 'Simpan perubahan?',
                text: 'Data profil administrator akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                this.loading = true;

                this.$nextTick(() => {
                    event.target.submit();
                });
            });
        },

        handlePhotoPreview(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran Berlebihan',
                    text: 'Ukuran file maksimal 2MB',
                    confirmButtonColor: '#2563eb'
                });
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    };
}

function confirmDeletePhoto() {
    Swal.fire({
        title: 'Hapus Foto?',
        text: 'Foto profil administrator akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-photo-form').submit();
        }
    });
}
</script>
@endpush