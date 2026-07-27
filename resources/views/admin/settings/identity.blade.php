<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Identitas Website</h2>
        <p class="text-sm text-gray-600 mt-1">Atur identitas dan branding website Anda</p>
    </div>

    <form action="{{ route('admin.settings.identity') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf

        {{-- Nama Aplikasi --}}
        <div class="mb-6">
            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Aplikasi <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="app_name"
                name="app_name"
                value="{{ old('app_name', $settings->app_name) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Fasel Aquarium"
                required
            >
            @error('app_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Slogan --}}
        <div class="mb-6">
            <label for="slogan" class="block text-sm font-medium text-gray-700 mb-2">
                Slogan Website
            </label>
            <textarea
                id="slogan"
                name="slogan"
                rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Platform E-commerce Terpercaya Untuk Ikan Hias"
            >{{ old('slogan', $settings->slogan) }}</textarea>
            @error('slogan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Logo Upload --}}
        <div class="mb-6">
            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                Logo Aplikasi (JPEG, PNG, GIF - Maks 2MB)
            </label>

            <div class="grid grid-cols-2 gap-6">
                {{-- Upload Area --}}
                <div>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('logo').click()">
                        <input
                            type="file"
                            id="logo"
                            name="logo"
                            accept="image/jpeg,image/png,image/gif"
                            class="hidden"
                            onchange="previewImage(this, 'logoPreview')"
                        >
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Klik untuk upload atau drag file</p>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB)</p>
                    </div>
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview --}}
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview</p>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 h-40 flex items-center justify-center">
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 h-40 flex items-center justify-center">

    <img
        id="logoPreview"
        src="{{ $settings->logo ? $settings->logo_url : '' }}"
        class="max-h-36 {{ $settings->logo ? '' : 'hidden' }}"
        alt="Logo">

    <p
        id="logoPreviewPlaceholder"
        class="{{ $settings->logo ? 'hidden' : '' }}">
        Tidak ada logo
    </p>

</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Favicon Upload --}}
        <div class="mb-6">
            <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                Favicon (JPEG, PNG, GIF, ICO - Maks 512KB)
            </label>

            <div class="grid grid-cols-2 gap-6">
                {{-- Upload Area --}}
                <div>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('favicon').click()">
                        <input
                            type="file"
                            id="favicon"
                            name="favicon"
                            accept="image/jpeg,image/png,image/gif,image/x-icon"
                            class="hidden"
                            onchange="previewImage(this, 'faviconPreview')"
                        >
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Klik untuk upload favicon</p>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, ICO (Max 512KB)</p>
                    </div>
                    @error('favicon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview --}}
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview</p>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 h-40 flex items-center justify-center">
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 h-40 flex items-center justify-center">

    <img
        id="faviconPreview"
        src="{{ $settings->favicon ? $settings->favicon_url : '' }}"
        class="max-h-12 {{ $settings->favicon ? '' : 'hidden' }}"
        alt="Favicon">

    <p
        id="faviconPreviewPlaceholder"
        class="{{ $settings->favicon ? 'hidden' : '' }}">
        Tidak ada favicon
    </p>

</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(previewId + 'Placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
