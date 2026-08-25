@extends('layouts.admin')

@section('content')
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.mortality.index') }}" class="transition hover:text-indigo-600">Mortality Ikan</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-slate-800">Detail</span>
    </nav>

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Detail Mortality</h1>
            <p class="mt-0.5 text-sm text-slate-500">Informasi lengkap catatan kematian ikan.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.mortality.edit', $mortality) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                Edit
            </a>
            <form action="{{ route('admin.mortality.destroy', $mortality) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(this)"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <x-flash-message />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <img src="{{ $mortality->product->image_url }}"
                alt="{{ $mortality->product->name }}"
                class="aspect-square w-full object-cover"
                onerror="this.src='https://placehold.co/500x500/e2e8f0/94a3b8?text=No+Image'">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Produk</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800">{{ $mortality->product->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">Stok saat ini: <strong class="text-slate-700">{{ $mortality->product->stock }} ekor</strong></p>
            </div>
        </div>

        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Kematian</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $mortality->date->translatedFormat('d F Y') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah</p>
                        <p class="mt-1 text-xl font-extrabold text-red-600">{{ number_format($mortality->quantity) }} ekor</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Penyebab</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $mortality->cause ?: 'Tidak diketahui' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dicatat Oleh</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $mortality->user?->name ?? 'Sistem' }}</p>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keterangan</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700">{{ $mortality->notes ?: 'Tidak ada keterangan.' }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-800">Informasi Pencatatan</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Dibuat</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700">{{ $mortality->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Terakhir diperbarui</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700">{{ $mortality->updated_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.mortality.index') }}"
                class="inline-flex rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                Kembali ke Mortality
            </a>
        </div>
    </div>
@endsection
