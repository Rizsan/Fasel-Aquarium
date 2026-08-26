@extends('layouts.admin')

@section('title', 'Prediksi Penjualan')

@section('content')

<div

    x-data="salesPredictionApp()"

    x-init="init()"

    class="min-h-screen bg-white"

>

    {{-- ================================================================

         HEADER

    ================================================================ --}}

    <div class="mb-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>

                <div class="flex items-center gap-3">

                    <a

                        href="{{ route('admin.prediction.index') }}"

                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition"

                    >

                        <svg

                            class="w-4 h-4 text-gray-600"

                            fill="none"

                            viewBox="0 0 24 24"

                            stroke="currentColor"

                        >

                            <path

                                stroke-linecap="round"

                                stroke-linejoin="round"

                                stroke-width="2"

                                d="M15 19l-7-7 7-7"

                            />

                        </svg>

                    </a>

                    <div>

                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">

                            Prediksi Penjualan

                        </h1>

                        <p class="mt-1 text-sm text-gray-500">

                            Analisis jumlah penjualan produk menggunakan Weighted Moving Average

                        </p>

                    </div>

                </div>

            </div>

            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full text-xs font-medium">

                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>

                Live Data

            </span>

        </div>

    </div>

    {{-- ================================================================

         FLASH

    ================================================================ --}}

    <div

        x-show="flash.show"

        x-transition

        :class="flash.type === 'success'

            ? 'bg-emerald-50 border-emerald-200 text-emerald-800'

            : 'bg-red-50 border-red-200 text-red-800'"

        class="mb-6 border rounded-xl px-4 py-3 text-sm"

    >

        <span x-text="flash.message"></span>

    </div>

    {{-- ================================================================

         FILTER

    ================================================================ --}}

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6 overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">

            <h2 class="text-sm font-semibold text-gray-700">

                Filter & Konfigurasi WMA

            </h2>

        </div>

        <div class="p-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Periode --}}

                <div>

                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">

                        Periode

                    </label>

                    <select

                        x-model="filter.period"

                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5"

                    >

                        <option value="daily">Harian</option>

                        <option value="weekly">Mingguan</option>

                        <option value="monthly">Bulanan</option>

                    </select>

                </div>

                {{-- Start --}}

                <div>

                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">

                        Tanggal Mulai

                    </label>

                    <input

                        type="date"

                        x-model="filter.start_date"

                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5"

                    >

                </div>

                {{-- End --}}

                <div>

                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">

                        Tanggal Selesai

                    </label>

                    <input

                        type="date"

                        x-model="filter.end_date"

                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5"

                    >

                </div>

                {{-- Window --}}

                <div>

                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">

                        Window

                    </label>

                    <select

                        x-model="filter.window"

                        @change="updateWeights()"

                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5"

                    >

                        @for($i = 2; $i <= 12; $i++)

                            <option value="{{ $i }}">

                                {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>

                {{-- Analisa --}}

                <div class="flex items-end">

                    <button

                        @click="fetchData()"

                        :disabled="loading"

                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"

                    >

                        <span x-text="loading ? 'Menganalisa...' : 'Analisa'"></span>

                    </button>

                </div>

            </div>

            {{-- Bobot --}}

            <div class="mt-4">

                <label class="block text-xs font-semibold text-gray-600 mb-1.5">

                    Bobot WMA

                </label>

                <input

                    type="text"

                    x-model="filter.weights"

                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5"

                    placeholder="1,2,3"

                >

                <p class="mt-1 text-xs text-gray-400">

                    Jumlah bobot harus sesuai dengan window.

                </p>

            </div>

        </div>

    </div>

    {{-- ================================================================

         CONTENT

    ================================================================ --}}

    <template x-if="!loading">

        <div class="space-y-6">

            {{-- =========================================================

                 SUMMARY

            ========================================================= --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Jumlah Produk --}}

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">

                    <p class="text-xs font-semibold text-gray-500 uppercase">

                        Produk Dianalisis

                    </p>

                    <p

                        class="mt-2 text-2xl font-bold text-gray-900"

                        x-text="summary.product_count || 0"

                    ></p>

                    <p class="mt-1 text-xs text-gray-400">

                        Produk dengan histori penjualan

                    </p>

                </div>

                {{-- Total Prediksi --}}

                <div class="bg-blue-600 rounded-2xl p-5 shadow-sm">

                    <p class="text-xs font-semibold text-blue-200 uppercase">

                        Total Prediksi

                    </p>

                    <p

                        class="mt-2 text-2xl font-bold text-white"

                        x-text="formatNumber(summary.total_prediction || 0) + ' unit'"

                    ></p>

                    <p class="mt-1 text-xs text-blue-200">

                        Akumulasi seluruh produk

                    </p>

                </div>

                {{-- Window --}}

                <div class="bg-purple-600 rounded-2xl p-5 shadow-sm">

                    <p class="text-xs font-semibold text-purple-200 uppercase">

                        Window WMA

                    </p>

                    <p

                        class="mt-2 text-2xl font-bold text-white"

                        x-text="summary.window || 0"

                    ></p>

                    <p

                        class="mt-1 text-xs text-purple-200"

                        x-text="'Bobot: [' + (summary.weights || []).join(', ') + ']'"

                    ></p>

                </div>

                {{-- Produk Tertinggi --}}

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">

                    <p class="text-xs font-semibold text-gray-500 uppercase">

                        Prediksi Tertinggi

                    </p>

                    <p

                        class="mt-2 text-base font-bold text-gray-900 truncate"

                        x-text="products[0] ? products[0].product_name : '-'"

                    ></p>

                    <p

                        class="mt-1 text-xs text-gray-400"

                        x-text="products[0] ? products[0].predicted_qty + ' unit' : '-'"

                    ></p>

                </div>

            </div>

            {{-- =========================================================

                 TABLE PRODUK

            ========================================================= --}}

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-800">

                            Hasil Prediksi Penjualan Produk

                        </h2>

                        <p class="text-xs text-gray-400 mt-1">

                            Data menggunakan perhitungan WMA yang sama dengan Produk Diprediksi Laku.

                        </p>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="bg-gray-50 border-b border-gray-100">

                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    #

                                </th>

                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Produk

                                </th>

                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Total Terjual

                                </th>

                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    WMA

                                </th>

                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Prediksi

                                </th>

                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Stok Saat Ini

                                </th>

                                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Status

                                </th>

                                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                    Aksi

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <template

                                x-for="(product, index) in products"

                                :key="product.product_id"

                            >

                                <tr class="border-b border-gray-50 hover:bg-gray-50">

                                    <td class="px-4 py-4 font-semibold text-gray-500">

                                        <span x-text="index + 1"></span>

                                    </td>

                                    <td class="px-4 py-4">

                                        <p

                                            class="font-semibold text-gray-800"

                                            x-text="product.product_name"

                                        ></p>

                                    </td>

                                    <td

                                        class="px-4 py-4 text-right font-semibold text-blue-700"

                                        x-text="formatNumber(product.total_qty)"

                                    ></td>

                                    <td

                                        class="px-4 py-4 text-right text-purple-600 font-semibold"

                                        x-text="formatDecimal(product.wma)"

                                    ></td>

                                    <td class="px-4 py-4 text-right">

                                        <span

                                            class="inline-flex bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg font-bold"

                                            x-text="product.predicted_qty + ' unit'"

                                        ></span>

                                    </td>

                                    {{-- STOCK --}}

                                    <td class="px-4 py-4">

                                        <div class="flex items-center justify-end gap-2">

                                            <button

                                                @click="changeStock(product, 'decrease')"

                                                class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-red-50 hover:text-red-600 transition"

                                            >

                                                −

                                            </button>

                                            <span

                                                class="min-w-[45px] text-center font-bold text-gray-800"

                                                x-text="product.stock"

                                            ></span>

                                            <button

                                                @click="changeStock(product, 'increase')"

                                                class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-emerald-50 hover:text-emerald-600 transition"

                                            >

                                                +

                                            </button>

                                        </div>

                                    </td>

                                    {{-- STATUS --}}

                                    <td class="px-4 py-4 text-center">

                                        <template x-if="product.stock >= product.predicted_qty">

                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">

                                                Mencukupi

                                            </span>

                                        </template>

                                        <template x-if="product.stock < product.predicted_qty">

                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                                Berpotensi Kurang

                                            </span>

                                        </template>

                                    </td>

                                    {{-- DETAIL --}}

                                    <td class="px-4 py-4 text-center">

                                        <button

                                            @click="openDetail(product)"

                                            class="text-blue-600 hover:text-blue-800 text-xs font-semibold"

                                        >

                                            Detail

                                        </button>

                                    </td>

                                </tr>

                            </template>

                            <template x-if="products.length === 0">

                                <tr>

                                    <td

                                        colspan="8"

                                        class="px-5 py-12 text-center text-gray-400"

                                    >

                                        Tidak ada data produk pada periode tersebut.

                                    </td>

                                </tr>

                            </template>

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- =========================================================

                 DETAIL PRODUK

            ========================================================= --}}

            <template x-if="selectedProduct">

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">

                        <div>

                            <h2

                                class="text-sm font-semibold text-gray-800"

                                x-text="'Detail Prediksi — ' + selectedProduct.product_name"

                            ></h2>

                            <p class="text-xs text-gray-400 mt-1">

                                Perbandingan aktual, WMA, dan prediksi.

                            </p>

                        </div>

                        <button

                            @click="selectedProduct = null"

                            class="text-gray-400 hover:text-gray-700"

                        >

                            ✕

                        </button>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead>

                                <tr class="bg-gray-50 border-b border-gray-100">

                                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                        Periode

                                    </th>

                                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                        Aktual Terjual

                                    </th>

                                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                        WMA

                                    </th>

                                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">

                                        Prediksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <template

                                    x-for="(row, index) in selectedProduct.periods"

                                    :key="index"

                                >

                                    <tr class="border-b border-gray-50">

                                        <td

                                            class="px-4 py-3 font-medium text-gray-800"

                                            x-text="row.label"

                                        ></td>

                                        <td

                                            class="px-4 py-3 text-right font-semibold text-blue-700"

                                            x-text="row.actual + ' unit'"

                                        ></td>

                                        <td

                                            class="px-4 py-3 text-right text-purple-600"

                                            x-text="row.wma !== null ? formatDecimal(row.wma) : '-'"

                                        ></td>

                                        <td class="px-4 py-3 text-right">

                                            <span

                                                x-show="row.prediction !== null"

                                                class="inline-flex bg-blue-100 text-blue-700 px-2 py-1 rounded-md font-semibold"

                                                x-text="formatDecimal(row.prediction)"

                                            ></span>

                                            <span

                                                x-show="row.prediction === null"

                                                class="text-gray-400"

                                            >

                                                -

                                            </span>

                                        </td>

                                    </tr>

                                </template>

                            </tbody>

                        </table>

                    </div>

                </div>

            </template>

        </div>

    </template>

</div>

@endsection

@push('scripts')

<script>

function salesPredictionApp() {

    return {

        loading: false,

        products: [],

        summary: {},

        selectedProduct: null,

        flash: {

            show: false,

            type: 'success',

            message: ''

        },

        filter: {

            period: 'monthly',

            start_date: '{{ $defaults['start_date'] }}',

            end_date: '{{ $defaults['end_date'] }}',

            window: '3',

            weights: '1,2,3',

        },

        init() {

            this.fetchData**()**;

        },

        updateWeights() {

            const window = parseInt**(**

                this.filter.window

            );

            this.filter.weights =

                Array.from**(**

                    { length: window },

                    (_, i) => i + 1

                ).join**(',')**;

        },

        async fetchData() {

            this.loading = true;

            try {

                const params = new URLSearchParams**(**{

                    period: this.filter.period,

                    start_date: this.filter.start_date,

                    end_date: this.filter.end_date,

                    window: this.filter.window,

                    weights: this.filter.weights,

                });

                const response = await fetch**(**

                    `{{ route('admin.prediction.sales.data') }}?${params}`,

                    {

                        headers: {

                            'X-Requested-With': 'XMLHttpRequest'

                        }

                    }

                );

                const json = await response.json**()**;

                if (!response.ok || !json.success) {

                    throw new Error**(**

                        json.message ||

                        'Gagal memuat data prediksi.'

                    );

                }

                this.products =

                    json.data.products || [];

                this.summary =

                    json.data.summary || {};

                this.selectedProduct = null;

            } catch (error) {

                this.showFlash**(**

                    'error',

                    error.message

                );

            } finally {

                this.loading = false;

            }

        },

        openDetail(product) {

            this.selectedProduct = product;

            window.scrollTo**(**{

                top: document.body.scrollHeight,

                behavior: 'smooth'

            });

        },

        async changeStock(product, action) {

            const confirmation = action === 'decrease'

                ? confirm**(**

                    `Kurangi 1 stok ${product.product_name}?`

                )

                : true;

            if (!confirmation**)** {

                return;

            }

            try {

                const response = await fetch**(**

                    `{{ url('/admin/prediction/sales/products') }}/${product.product_id}/stock`,

                    {

                        method: 'PATCH',

                        headers: {

                            'Content-Type':

                                'application/json',

                            'X-CSRF-TOKEN':

                                '{{ csrf_token() }}',

                            'X-Requested-With':

                                'XMLHttpRequest'

                        },

                        body: JSON.stringify**(**{

                            action: action,

                            amount: 1

                        })

                    }

                );

                const json = await response.json**()**;

                if (!response.ok || !json.success) {

                    throw new Error**(**

                        json.message ||

                        'Gagal memperbarui stok.'

                    );

                }

                product.stock =

                    json.data.stock;

                this.showFlash**(**

                    'success',

                    json.message

                );

            } catch (error) {

                this.showFlash**(**

                    'error',

                    error.message

                );

            }

        },

        showFlash(type, message) {

            this.flash = {

                show: true,

                type: type,

                message: message

            };

            setTimeout**(**() => {

                this.flash.show = false;

            }, 4000**)**;

        },

        formatNumber(value) {

            return new Intl.NumberFormat**(**

                'id-ID'

            ).format**(value || 0)**;

        },

        formatDecimal(value) {

            if (

                value === null ||

                value === undefined

            ) {

                return '-';

            }

            return new Intl.NumberFormat**(**

                'id-ID',

                {

                    minimumFractionDigits: 0,

                    maximumFractionDigits: 2

                }

            ).format**(value)**;

        }

    };

}

</script>

@endpush