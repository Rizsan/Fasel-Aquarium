@extends('layouts.admin')

@section('content')
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.mortality.index') }}" class="transition hover:text-indigo-600">Mortality Ikan</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('admin.mortality.show', $mortality) }}" class="transition hover:text-indigo-600">Detail</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-slate-800">Edit</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Edit Catatan Mortality</h1>
        <p class="mt-0.5 text-sm text-slate-500">Perubahan jumlah akan otomatis menyesuaikan stok produk.</p>
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

    <form action="{{ route('admin.mortality.update', $mortality) }}" method="POST"
        x-data="mortalityForm(@js($products->mapWithKeys(fn($p) => [$p->id => ['stock' => $p->stock, 'name' => $p->name]])))">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="date" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Kematian <span class="text-red-500">*</span></label>
                        <input id="date" type="date" name="date" value="{{ old('date', $mortality->date->format('Y-m-d')) }}" required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="product_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis Ikan / Produk <span class="text-red-500">*</span></label>
                        <select id="product_id" name="product_id" x-model="productId" required
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} — stok {{ $product->stock }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="quantity" class="mb-1.5 block text-sm font-semibold text-slate-700">Jumlah Ikan Mati <span class="text-red-500">*</span></label>
                        <input id="quantity" type="number" name="quantity" min="1" :max="maxQuantity" value="{{ old('quantity', $mortality->quantity) }}" required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-500">Untuk produk yang sama, stok saat ini dapat ditambah kembali dari mortality lama.</p>
                    </div>

                    <div>
                        <label for="cause" class="mb-1.5 block text-sm font-semibold text-slate-700">Penyebab</label>
                        <input id="cause" type="text" name="cause" value="{{ old('cause', $mortality->cause) }}"
                            placeholder="Contoh: Penyakit, kualitas air, stres..."
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="mb-1.5 block text-sm font-semibold text-slate-700">Keterangan</label>
                        <textarea id="notes" name="notes" rows="4" maxlength="5000"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes', $mortality->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Stok saat ini</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800" x-text="currentStock + ' ekor'"></p>
                    <p class="mt-2 text-sm text-slate-500" x-show="productId == '{{ $mortality->product_id }}'">
                        Stok efektif setelah mengembalikan catatan lama:
                        <strong x-text="currentStock + {{ $mortality->quantity }}"></strong> ekor.
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-sm font-semibold text-amber-800">Penyesuaian stok</p>
                    <p class="mt-1 text-sm leading-relaxed text-amber-700">
                        Jika jumlah mortality berubah, sistem hanya menyesuaikan selisihnya. Jika produk diganti, stok produk lama dikembalikan dan stok produk baru dikurangi.
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.mortality.show', $mortality) }}"
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
            productId: '{{ old('product_id', $mortality->product_id) }}',
            oldProductId: '{{ $mortality->product_id }}',
            oldQuantity: {{ $mortality->quantity }},
            get currentStock() {
                return this.products[this.productId]?.stock ?? 0;
            },
            get maxQuantity() {
                if (this.productId == this.oldProductId) {
                    return this.currentStock + this.oldQuantity;
                }
                return this.currentStock;
            }
        };
    }
</script>
@endpush
