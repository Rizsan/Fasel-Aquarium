@extends('layouts.admin')

@section('content')
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.mortality.index') }}" class="transition hover:text-indigo-600">Mortality Ikan</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-slate-800">Catat Kematian</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Catat Kematian Ikan</h1>
        <p class="mt-0.5 text-sm text-slate-500">Pencatatan akan otomatis mengurangi stok ikan yang dipilih.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Periksa kembali data yang dimasukkan.</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.mortality.store') }}" method="POST" x-data="mortalityForm(@js($products->mapWithKeys(fn($p) => [$p->id => ['stock' => $p->stock, 'name' => $p->name]])))">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="date" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Kematian <span class="text-red-500">*</span></label>
                        <input id="date" type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="product_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis Ikan / Produk <span class="text-red-500">*</span></label>
                        <select id="product_id" name="product_id" x-model="productId" required
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih ikan</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} — stok {{ $product->stock }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="quantity" class="mb-1.5 block text-sm font-semibold text-slate-700">Jumlah Ikan Mati <span class="text-red-500">*</span></label>
                        <input id="quantity" type="number" name="quantity" min="1" :max="currentStock" value="{{ old('quantity', 1) }}" required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cause" class="mb-1.5 block text-sm font-semibold text-slate-700">Penyebab</label>
                        <input id="cause" type="text" name="cause" value="{{ old('cause') }}"
                            placeholder="Contoh: Penyakit, kualitas air, stres..."
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cause') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="mb-1.5 block text-sm font-semibold text-slate-700">Keterangan</label>
                        <textarea id="notes" name="notes" rows="4" maxlength="5000"
                            placeholder="Tambahkan informasi kondisi atau kejadian yang ditemukan..."
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Stok tersedia</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800" x-text="productId ? currentStock + ' ekor' : '-'"></p>
                    <p class="mt-2 text-sm text-slate-500" x-show="!productId">Pilih ikan untuk melihat stok saat ini.</p>
                    <p class="mt-2 text-sm text-amber-600" x-show="productId && currentStock <= 10">
                        Stok ikan sedang menipis.
                    </p>
                </div>

                <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
                    <p class="text-sm font-semibold text-red-800">Perhatian</p>
                    <p class="mt-1 text-sm leading-relaxed text-red-700">
                        Jumlah mortality akan langsung dikurangi dari stok produk setelah data berhasil disimpan.
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Simpan Mortality
                    </button>
                    <a href="{{ route('admin.mortality.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function mortalityForm(products) {
        return {
            products,
            productId: '{{ old('product_id') }}',
            get currentStock() {
                return this.products[this.productId]?.stock ?? 0;
            }
        };
    }
</script>
@endpush
