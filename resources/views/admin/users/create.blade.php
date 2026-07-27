{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Pengguna</h1>
            <p class="text-gray-500 text-sm">Buat akun pengguna baru</p>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
          x-data="userForm()">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Foto Profil --}}
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative">
                        <img
                            id="profile-preview"
                            src="https://ui-avatars.com/api/?name=New+User&background=dbeafe&color=2563eb&size=128"
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                        >
                        <label for="profile_photo"
                               class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center cursor-pointer transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9zm12 4a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                    </div>
                    <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*"
                           onchange="previewProfilePhoto(event)">
                    <p class="text-xs text-gray-400">JPG, PNG, WEBP • Maksimal 2MB</p>
                    @error('profile_photo')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Fields --}}
            <div class="p-6 space-y-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="John Doe"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="john@example.com"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                placeholder="Min. 8 karakter"
                                class="w-full px-4 py-2.5 pr-10 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                       {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"
                            >
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                {{-- Role & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select
                            name="role"
                            class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition
                            {{ $errors->has('role') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"
                        >
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <label class="flex items-center gap-3 cursor-pointer mt-3">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1"
                                       class="sr-only peer"
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-600 rounded-full peer transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm text-gray-600">Akun Aktif</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2.5 border border-gray-200 text-gray-600 hover:border-gray-300 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function userForm() {
    return { showPassword: false };
}

function previewProfilePhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profile-preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>

@if($errors->any())
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: 'Terdapat kesalahan pada form',
    text: 'Silakan periksa kembali data yang Anda masukkan.',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true
});
</script>
@endif
@endpush

@endsection