<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Pengaturan Umum</h2>
        <p class="text-sm text-gray-600 mt-1">Atur preferensi umum website</p>
    </div>

    <form action="{{ route('admin.settings.general') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Timezone --}}
            <div>
                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                    Timezone <span class="text-red-500">*</span>
                </label>
                <select
                    id="timezone"
                    name="timezone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                    <option value="">-- Pilih Timezone --</option>
                    <optgroup label="Asia">
                        <option value="Asia/Jakarta" @selected($settings->timezone === 'Asia/Jakarta')>Jakarta (WIB)</option>
                        <option value="Asia/Jayapura" @selected($settings->timezone === 'Asia/Jayapura')>Jayapura (WIT)</option>
                        <option value="Asia/Makassar" @selected($settings->timezone === 'Asia/Makassar')>Makassar (WITA)</option>
                    </optgroup>
                    <optgroup label="Lainnya">
                        <option value="UTC" @selected($settings->timezone === 'UTC')>UTC</option>
                        <option value="Asia/Bangkok" @selected($settings->timezone === 'Asia/Bangkok')>Bangkok</option>
                        <option value="Asia/Singapore" @selected($settings->timezone === 'Asia/Singapore')>Singapore</option>
                    </optgroup>
                </select>
                @error('timezone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Format Tanggal --}}
            <div>
                <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">
                    Format Tanggal <span class="text-red-500">*</span>
                </label>
                <select
                    id="date_format"
                    name="date_format"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                    <option value="d/m/Y" @selected($settings->date_format === 'd/m/Y')>DD/MM/YYYY (25/12/2024)</option>
                    <option value="m/d/Y" @selected($settings->date_format === 'm/d/Y')>MM/DD/YYYY (12/25/2024)</option>
                    <option value="Y-m-d" @selected($settings->date_format === 'Y-m-d')>YYYY-MM-DD (2024-12-25)</option>
                </select>
                @error('date_format')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Produk Per Halaman --}}
            <div>
                <label for="products_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Produk Per Halaman <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="products_per_page"
                    name="products_per_page"
                    value="{{ old('products_per_page', $settings->products_per_page) }}"
                    min="1"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                @error('products_per_page')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Maintenance Mode --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Maintenance Mode
                </label>
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        id="maintenance_mode"
                        name="maintenance_mode"
                        value="1"
                        @checked($settings->maintenance_mode)
                        class="w-5 h-5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                    >
                    <label for="maintenance_mode" class="text-sm text-gray-600">
                        Aktifkan maintenance mode (website tidak bisa diakses customer)
                    </label>
                </div>
                @error('maintenance_mode')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Info Box --}}
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Catatan:</strong> Semua perubahan akan diterapkan segera. Pastikan Anda menyimpan backup sebelum mengubah pengaturan penting.
            </p>
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
