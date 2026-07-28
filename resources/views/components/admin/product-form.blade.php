@props([
    'product' => null,
    'action'  => '',
    'method'  => 'POST',
])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if (!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    {{-- Section: Informasi Dasar --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-5 text-sm font-semibold text-slate-800">Informasi Dasar</h3>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            {{-- Nama Produk --}}
            <div class="sm:col-span-2">
                <x-admin.form-input
                    label="Nama Produk"
                    name="name"
                    :value="$product?->name"
                    placeholder="Contoh: Laptop Gaming Pro X"
                    required
                />
            </div>

            {{-- Harga --}}
            <x-admin.form-input
                label="Harga (Rp)"
                name="price"
                type="number"
                :value="$product?->price"
                placeholder="0"
                required
                hint="Masukkan angka tanpa titik atau koma."
            />

            {{-- Stok --}}
            <x-admin.form-input
                label="Stok"
                name="stock"
                type="number"
                :value="$product?->stock ?? 0"
                placeholder="0"
                hint="Jumlah unit yang tersedia."
            />

            {{-- Upload Gambar --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                Gambar Produk
                </label>

            <div class="flex items-center gap-5">
        
            {{-- Preview --}}
            <div class="w-24 h-24 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 flex items-center justify-center">

    @if($product?->image_url)
        <img
            id="preview-image"
            src="{{ $product?->image_url ?? asset('assets/images/no-image.png') }}"
            class="object-cover w-full h-full"
            alt="Preview"
        >
    @else
        <img
            id="preview-image"
            src=""
            class="hidden object-cover w-full h-full"
            alt="Preview"
        >

        <svg id="placeholder-icon" class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4-4 4 4 6-6 2 2v6H4z"/>
        </svg>
    @endif

</div>

            {{-- Input --}}
            <div class="flex-1">
                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    onchange="previewImage(event)"
                    class="block w-full text-sm text-slate-600
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-600
                        hover:file:bg-indigo-100"
                >

                <p class="text-xs text-slate-500 mt-2">
                    JPG, PNG (maks 2MB)
                </p>
            </div>
        </div>
    </div>

            {{-- Deskripsi --}}
            <div class="sm:col-span-2">
                <x-admin.form-textarea
                    label="Deskripsi"
                    name="description"
                    :value="$product?->description"
                    placeholder="Deskripsikan produk ini..."
                    :rows="4"
                />
            </div>
        </div>
    </div>

    {{-- Section: Status --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-5 text-sm font-semibold text-slate-800">Status Produk</h3>

        <label class="group flex cursor-pointer items-center gap-3">
            <div class="relative">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="peer sr-only"
                    {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}
                />
                <div class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600
                    peer-focus:ring-2 peer-focus:ring-indigo-500 peer-focus:ring-offset-1"></div>
                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition
                    peer-checked:translate-x-5"></div>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-700">Produk Aktif</p>
                <p class="text-xs text-slate-500">Produk aktif akan tampil di halaman publik.</p>
            </div>
        </label>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.products.index') }}"
            class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700
                shadow-sm transition hover:bg-slate-50 hover:shadow">
            Batal
        </a>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold
                text-white shadow-sm transition hover:bg-indigo-700 active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $product ? 'Simpan Perubahan' : 'Tambah Produk' }}
        </button>
    </div>
</form>
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e) {
        const img = document.getElementById('preview-image');
        img.src = e.target.result;
        img.classList.remove('hidden');

        const placeholder = document.getElementById('placeholder-icon');
        if (placeholder) {
            placeholder.remove();
        }
    };

    reader.readAsDataURL(file);
}
</script>