@props(['products'])

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        No
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Produk
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Harga
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Stok
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Status
                    </th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($products as $index => $product)
                    <tr class="group transition hover:bg-slate-50/70">
                        {{-- ✅ FIX NOMOR --}}
                        <td class="px-6 py-4 text-sm text-slate-500">
                            @if(method_exists($products, 'firstItem'))
                                {{ $products->firstItem() + $index }}
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </td>

                        {{-- Produk --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($product->image)
                                    <img
                                        src="{{ $product->image_url }}"
                                        class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200"
                                        onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=IMG'"
                                    />
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 ring-1 ring-slate-200">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $product->name }}</p>
                                    @if ($product->description)
                                        <p class="mt-0.5 max-w-xs truncate text-xs text-slate-500">
                                            {{ $product->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Harga --}}
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">
                            {{ $product->formatted_price }}
                        </td>

                        {{-- Stok --}}
                        <td class="px-6 py-4">
                            <x-admin.badge :color="$product->stock_status_color">
                                {{ $product->stock }} — {{ $product->stock_status }}
                            </x-admin.badge>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <x-admin.badge :color="$product->is_active ? 'green' : 'gray'">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-admin.badge>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.show', $product) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 hover:shadow">
                                    Detail
                                </a>

                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-100 hover:text-indigo-800 hover:shadow">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST"
                                    class="delete-form"
                                >
                        @csrf
                        @method('DELETE')

    <button
        type="button"
        onclick="confirmDelete(this)"
        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 shadow-sm transition hover:bg-red-100 hover:text-red-800 hover:shadow">
        Hapus
    </button>
</form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <p class="text-sm text-slate-500">Belum ada produk</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>