<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Informasi Kontak</h2>
        <p class="text-sm text-gray-600 mt-1">Atur informasi kontak untuk customer</p>
    </div>

    <form action="{{ route('admin.settings.contact') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $settings->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Telepon --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $settings->phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0812345678"
                    required
                >
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div>
                <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="whatsapp"
                    name="whatsapp"
                    value="{{ old('whatsapp', $settings->whatsapp) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="083131871300"
                    required
                >
                @error('whatsapp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Instagram --}}
            <div>
                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">
                    Instagram
                </label>
                <input
                    type="text"
                    id="instagram"
                    name="instagram"
                    value="{{ old('instagram', $settings->instagram) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="@username"
                >
                @error('instagram')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Facebook --}}
            <div>
                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">
                    Facebook
                </label>
                <input
                    type="text"
                    id="facebook"
                    name="facebook"
                    value="{{ old('facebook', $settings->facebook) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Nama Facebook"
                >
                @error('facebook')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Latitude --}}
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                    Latitude
                </label>
                <input
                    type="number"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude', $settings->latitude) }}"
                    step="0.00000001"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="-6.3044"
                >
                @error('latitude')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Longitude --}}
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                    Longitude
                </label>
                <input
                    type="number"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude', $settings->longitude) }}"
                    step="0.00000001"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="108.3257"
                >
                @error('longitude')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Alamat (Full Width) --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                Alamat <span class="text-red-500">*</span>
            </label>
            <textarea
                id="address"
                name="address"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
            >{{ old('address', $settings->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
            <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
