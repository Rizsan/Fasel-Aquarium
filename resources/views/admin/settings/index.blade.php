@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Website</h1>
        <p class="text-gray-600 mt-2">Kelola semua pengaturan website dari satu tempat</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-6" x-data="{ activeTab: 'identity' }">
        <div class="border-b border-gray-200">
            <nav class="flex gap-8" role="tablist">
                <button
                    @click="activeTab = 'identity'"
                    :class="activeTab === 'identity' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-1 font-medium transition"
                    role="tab"
                >
                    <i class="fas fa-image mr-2"></i> Identitas Website
                </button>

                <button
                    @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-1 font-medium transition"
                    role="tab"
                >
                    <i class="fas fa-phone mr-2"></i> Informasi Kontak
                </button>

                <button
                    @click="activeTab = 'about'"
                    :class="activeTab === 'about' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-1 font-medium transition"
                    role="tab"
                >
                    <i class="fas fa-book mr-2"></i> Tentang Kami
                </button>

                <button
                    @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-1 font-medium transition"
                    role="tab"
                >
                    <i class="fas fa-cog mr-2"></i> Pengaturan Umum
                </button>

                <button
                    @click="activeTab = 'backup'"
                    :class="activeTab === 'backup' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-1 font-medium transition"
                    role="tab"
                >
                    <i class="fas fa-database mr-2"></i> Backup & Restore
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="mt-8">
            {{-- Identity Tab --}}
            <div x-show="activeTab === 'identity'">
    @include('admin.settings.identity')
</div>

            {{-- Contact Tab --}}
            <div x-show="activeTab === 'contact'">
                @include('admin.settings.contact')
            </div>

            {{-- About Tab --}}
            <div x-show="activeTab === 'about'">
                @include('admin.settings.about')
            </div>

            {{-- General Tab --}}
            <div x-show="activeTab === 'general'">
                @include('admin.settings.general-settings')
            </div>

            {{-- Backup Tab --}}
            <div x-show="activeTab === 'backup'">
                @include('admin.settings.backup')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set active tab dari query parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const event = new Event('click');
            document.querySelector(`button[data-tab="${tab}"]`)?.click();
        }
    });
</script>
@endpush
@endsection
