<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Halaman Tentang Kami</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola konten halaman tentang kami dan galeri foto</p>
    </div>

    <form action="{{ route('admin.settings.about') }}" method="POST" class="p-6">
        @csrf

        {{-- Judul --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Halaman <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $about->title) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Tentang Kami"
                required
            >
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Isi Tentang Kami --}}
        <div class="mb-6">
            <label for="about_content" class="block text-sm font-medium text-gray-700 mb-2">
                Isi Tentang Kami <span class="text-red-500">*</span>
            </label>
            <textarea
                id="about_content"
                name="about_content"
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
            >{{ old('about_content', $about->about_content) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Gunakan <strong>\n</strong> untuk membuat baris baru</p>
            @error('about_content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mengapa Memilih Kami --}}
        <div class="mb-6">
            <label for="why_choose_us" class="block text-sm font-medium text-gray-700 mb-2">
                Mengapa Memilih Kami
            </label>
            <textarea
                id="why_choose_us"
                name="why_choose_us"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >{{ old('why_choose_us', $about->why_choose_us) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Gunakan <strong>✓</strong> atau <strong>-</strong> untuk list items</p>
            @error('why_choose_us')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Cara Berbelanja --}}
        <div class="mb-6">
            <label for="how_to_shop" class="block text-sm font-medium text-gray-700 mb-2">
                Cara Berbelanja
            </label>
            <textarea
                id="how_to_shop"
                name="how_to_shop"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >{{ old('how_to_shop', $about->how_to_shop) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Gunakan <strong>1.</strong> <strong>2.</strong> dst untuk numbered list</p>
            @error('how_to_shop')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fasilitas --}}
        <div class="mb-6">
            <label for="facilities" class="block text-sm font-medium text-gray-700 mb-2">
                Fasilitas
            </label>
            <textarea
                id="facilities"
                name="facilities"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >{{ old('facilities', $about->facilities) }}</textarea>
            @error('facilities')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Informasi Kontak (Section) --}}
        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Kontak Halaman</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Alamat --}}
                <div>
                    <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea
                        id="contact_address"
                        name="contact_address"
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('contact_address', $about->contact_address) }}</textarea>
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label for="contact_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor WhatsApp
                    </label>
                    <input
                        type="text"
                        id="contact_whatsapp"
                        name="contact_whatsapp"
                        value="{{ old('contact_whatsapp', $about->contact_whatsapp) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="083131871300"
                    >
                </div>

                {{-- Instagram --}}
                <div>
                    <label for="contact_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                        Instagram
                    </label>
                    <input
                        type="text"
                        id="contact_instagram"
                        name="contact_instagram"
                        value="{{ old('contact_instagram', $about->contact_instagram) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="@username"
                    >
                </div>

                {{-- Telepon --}}
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input
                        type="text"
                        id="contact_phone"
                        name="contact_phone"
                        value="{{ old('contact_phone', $about->contact_phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0812345678"
                    >
                </div>

                {{-- Jam Operasional --}}
                <div class="md:col-span-2">
                    <label for="operation_hours" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Operasional
                    </label>
                    <input
                        type="text"
                        id="operation_hours"
                        name="operation_hours"
                        value="{{ old('operation_hours', $about->operation_hours) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="09.00 - 21.00 WIB (Setiap Hari)"
                    >
                </div>
            </div>
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

    {{-- Gallery Section --}}
    <div class="border-t border-gray-200 px-6 py-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Galeri Foto</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @for ($i = 1; $i <= 5; $i++)

                @php
                    $gallery = $about->getGalleries()->firstWhere('key', "gallery_{$i}");
                @endphp

                <div class="border border-gray-300 rounded-lg overflow-hidden hover:shadow-lg transition">

                    {{-- Image Preview --}}
                    <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($gallery)
                            <img
                                src="{{ $gallery['url'] }}"
                                alt="Gallery {{ $i }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="text-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                                <p class="text-gray-400 text-sm mt-2">
                                    Tidak ada gambar
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="p-4 bg-white border-t border-gray-200">

                        <p class="text-sm font-medium text-gray-700 mb-3">
                            Galeri {{ $i }}
                        </p>

                        <div class="flex gap-2">

                            <form
                                action="{{ route('admin.settings.gallery.upload', $i) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="flex-1"
                            >
                                @csrf

                                <input
                                    type="file"
                                    name="gallery"
                                    accept="image/*"
                                    class="hidden"
                                    id="gallery_{{ $i }}"
                                    onchange="this.form.submit()"
                                >

                                <button
                                    type="button"
                                    onclick="document.getElementById('gallery_{{ $i }}').click()"
                                    class="w-full px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition flex items-center justify-center gap-1"
                                >
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </form>

                            @if($gallery)
                                <form
                                    action="{{ route('admin.settings.gallery.delete', $i) }}"
                                    method="POST"
                                    class="flex-1"
                                    onsubmit="return confirm('Hapus galeri ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full px-3 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition flex items-center justify-center gap-1"
                                    >
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>

                </div>

            @endfor
        </div>
    </div>
</div>