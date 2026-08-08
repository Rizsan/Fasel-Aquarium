@extends('layouts.admin')

@section('title', 'Prediksi Pendapatan')

@section('content')
<div
    x-data="predictionApp()"
    x-init="init()"
    class="min-h-screen bg-white"
>

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Prediksi Pendapatan
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Analisis tren &amp; prediksi menggunakan Simple &amp; Weighted Moving Average
                </p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-full font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Live Data
                </span>
            </div>
        </div>
    </div>

    {{-- ================================================================
         FLASH MESSAGE
    ================================================================ --}}
    <div
        x-show="flashMessage.show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        :class="flashMessage.type === 'success'
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
            : 'bg-red-50 border-red-200 text-red-800'"
        class="mb-6 flex items-center gap-3 border rounded-xl px-4 py-3 text-sm"
    >
        <template x-if="flashMessage.type === 'success'">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </template>
        <template x-if="flashMessage.type === 'error'">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </template>
        <span x-text="flashMessage.message"></span>
        <button @click="flashMessage.show = false" class="ml-auto opacity-60 hover:opacity-100">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ================================================================
         FILTER PANEL
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm3 4a1 1 0 011-1h10a1 1 0 010 2H7a1 1 0 01-1-1zm4 4a1 1 0 011-1h2a1 1 0 010 2h-2a1 1 0 01-1-1z"/>
            </svg>
            <h2 class="text-sm font-semibold text-gray-700">Filter &amp; Konfigurasi</h2>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">

                {{-- Periode --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Periode</label>
                    <select
                        x-model="filter.period"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                    </select>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Tanggal Mulai</label>
                    <input
                        type="date"
                        x-model="filter.start_date"
                        :max="filter.end_date"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                    <p x-show="errors.start_date" x-text="errors.start_date" class="mt-1 text-xs text-red-500"></p>
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Tanggal Selesai</label>
                    <input
                        type="date"
                        x-model="filter.end_date"
                        :min="filter.start_date"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                    <p x-show="errors.end_date" x-text="errors.end_date" class="mt-1 text-xs text-red-500"></p>
                </div>

                {{-- Window --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Window <span class="font-normal text-gray-400">(periode)</span>
                    </label>
                    <select
                        x-model="filter.window"
                        @change="updateWeightsInputs()"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>

                {{-- Tombol Toggle Weight Config --}}
                <div class="flex items-end">
                    <button
                        @click="showWeightConfig = !showWeightConfig"
                        type="button"
                        class="w-full flex items-center justify-center gap-2 text-sm font-semibold px-3 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Bobot WMA
                    </button>
                </div>

                {{-- Tombol Analisa --}}
                <div class="flex items-end">
                    <button
                        @click="fetchData"
                        :disabled="loading"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-all duration-200 shadow-sm shadow-blue-200"
                    >
                        <template x-if="!loading">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </template>
                        <template x-if="loading">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Menganalisa...' : 'Analisa'"></span>
                    </button>
                </div>
            </div>

            {{-- WEIGHT CONFIG SECTION (DYNAMIC) --}}
            <div x-show="showWeightConfig" x-transition class="mt-5 pt-5 border-t border-gray-100">
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <div class="flex items-start gap-3 mb-4">
                        <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-purple-900 mb-0.5">Konfigurasi Bobot WMA (Weighted Moving Average)</p>
                            <p class="text-xs text-purple-700">
                                Bobot otomatis disesuaikan dengan window size (<span x-text="filter.window"></span> periode).
                                <br>Format: angka terpisah koma atau biarkan kosong untuk auto-generate ascending [1,2,3,...,<span x-text="filter.window"></span>]
                            </p>
                        </div>
                    </div>

                    {{-- Input Bobot Dinamis --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-2">
                                Masukkan Bobot (<span x-text="filter.window"></span> nilai, dipisah koma)
                                <span class="font-normal text-gray-400 ml-1">(opsional)</span>
                            </label>
                            <input
                                type="text"
                                x-model="filter.weights"
                                @input="validateWeightsInput()"
                                placeholder="Kosong untuk auto-generate, atau: 1,2,3,4,5"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            >
                            <div class="mt-2 p-2.5 bg-white border border-purple-100 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    <strong>Bobot saat ini:</strong>
                                    <span x-text="getDisplayWeights()"></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1" x-show="!filter.weights">
                                    ℹ️ Auto-generate: <span x-text="'[' + generateDefaultWeights().join(', ') + ']'"></span>
                                </p>
                                <p class="text-xs text-red-600 mt-1" x-show="weightsError" x-text="weightsError"></p>
                            </div>
                        </div>

                        {{-- Quick Presets --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Preset Cepat</p>
                            <div class="grid grid-cols-3 gap-2">
                                <button
                                    @click="setWeightsPreset('ascending')"
                                    type="button"
                                    class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-purple-50 transition"
                                >
                                    📈 Ascending
                                </button>
                                <button
                                    @click="setWeightsPreset('equal')"
                                    type="button"
                                    class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-purple-50 transition"
                                >
                                    ➡️ Equal
                                </button>
                                <button
                                    @click="setWeightsPreset('exponential')"
                                    type="button"
                                    class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-purple-50 transition"
                                >
                                    📊 Exponential
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         LOADING SKELETON
    ================================================================ --}}
    <template x-if="loading">
        <div class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                <template x-for="i in 5">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 animate-pulse">
                        <div class="h-3 bg-gray-200 rounded w-1/2 mb-3"></div>
                        <div class="h-8 bg-gray-200 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                    </div>
                </template>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-5 animate-pulse">
                <div class="h-4 bg-gray-200 rounded w-1/4 mb-6"></div>
                <div class="h-64 bg-gray-100 rounded-xl"></div>
            </div>
        </div>
    </template>

    {{-- ================================================================
         MAIN CONTENT
    ================================================================ --}}
    <div x-show="!loading && hasData" class="space-y-6">

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">

                {{-- Total Pendapatan --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pendapatan</p>
                            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="formatRupiah(result.summary.total_revenue)"></p>
                            <p class="mt-1 text-xs text-gray-400" x-text="result.summary.data_points + ' periode'"></p>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Rata-rata Pendapatan --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Rata-rata / Periode</p>
                            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="formatRupiah(result.summary.avg_revenue)"></p>
                            <p class="mt-1 text-xs text-gray-400">Per periode aktif</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Prediksi Berikutnya --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 shadow-sm shadow-blue-200 hover:shadow-md hover:shadow-blue-200 transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wide">Prediksi Berikutnya</p>
                            <p class="mt-2 text-2xl font-bold text-white" x-text="formatRupiah(result.summary.next_prediction)"></p>
                            <p class="mt-1 text-xs text-blue-300">Berdasarkan WMA</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Metode Prediksi --}}
                <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-5 shadow-sm shadow-purple-200 hover:shadow-md hover:shadow-purple-200 transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-purple-200 uppercase tracking-wide">Metode Prediksi</p>
                            <p class="mt-2 text-base font-bold text-white">WMA</p>
                            <p class="mt-1 text-xs text-purple-300">Window: <span x-text="result.summary.window_size"></span></p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Produk Berpotensi Tinggi --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0 flex-1 mr-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Produk Berpotensi</p>
                            <p class="mt-2 text-base font-bold text-gray-900 truncate"
                               x-text="result.products[0] ? result.products[0].product_name : '-'"></p>
                            <p class="mt-1 text-xs text-gray-400"
                               x-text="result.products[0] ? 'Prediksi: ' + result.products[0].predicted_qty + ' unit' : 'Tidak ada'"></p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- CHART --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-800">Grafik Perbandingan Aktual vs Prediksi</h2>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 bg-blue-500 rounded inline-block"></span> Aktual
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 bg-emerald-500 rounded inline-block"></span> SMA
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 bg-purple-500 rounded inline-block" style="border-radius: 1px; background-image: linear-gradient(to right, #a855f7 0%, #a855f7 50%, transparent 50%);"></span> WMA
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="relative h-72 sm:h-80">
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABLE + PRODUCTS GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- DATA TABLE --}}
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <h2 class="text-sm font-semibold text-gray-800">Tabel Data Prediksi</h2>
                        </div>
                        <span
                            class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg"
                            x-text="result.table.length + ' periode'"
                        ></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 sticky top-0 bg-gray-50">Periode</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 sticky top-0 bg-gray-50">Aktual</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 sticky top-0 bg-gray-50">SMA</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 sticky top-0 bg-gray-50">WMA</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 sticky top-0 bg-gray-50">Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, index) in paginatedTable" :key="index">
                                    <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors">
                                        <td class="px-4 py-3 font-medium text-gray-800" x-text="row.label"></td>
                                        <td class="px-4 py-3 text-right font-semibold text-blue-700" x-text="formatRupiah(row.actual)"></td>
                                        <td class="px-4 py-3 text-right text-emerald-600" x-text="row.sma !== null ? formatRupiah(row.sma) : '-'"></td>
                                        <td class="px-4 py-3 text-right text-purple-600" x-text="row.wma !== null ? formatRupiah(row.wma) : '-'"></td>
                                        <td class="px-4 py-3 text-right">
                                            <span
                                                x-show="row.prediction !== null"
                                                class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-md"
                                                x-text="formatRupiah(row.prediction)"
                                            ></span>
                                            <span x-show="row.prediction === null" class="text-gray-400">-</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between" x-show="totalPages > 1">
                        <span class="text-xs text-gray-400"
                              x-text="'Halaman ' + currentPage + ' dari ' + totalPages"></span>
                        <div class="flex gap-1">
                            <button
                                @click="currentPage--"
                                :disabled="currentPage === 1"
                                class="px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg disabled:opacity-40 hover:bg-gray-50 transition"
                            >
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button
                                @click="currentPage++"
                                :disabled="currentPage === totalPages"
                                class="px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg disabled:opacity-40 hover:bg-gray-50 transition"
                            >
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- PREDICTED PRODUCTS --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-800">Produk Diprediksi Laku</h2>
                    </div>

                    {{-- Empty state --}}
                    <template x-if="result.products.length === 0">
                        <div class="px-5 py-10 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-sm">Belum ada data produk</p>
                        </div>
                    </template>

                    <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
                        <template x-for="(product, idx) in result.products" :key="idx">
                            <div class="px-4 py-3.5 hover:bg-gray-50/60 transition-colors flex items-center gap-3">

                                {{-- Rank --}}
                                <div
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    :class="{
                                        'bg-emerald-100 text-emerald-700': product.rank === 1,
                                        'bg-blue-100 text-blue-600': product.rank === 2,
                                        'bg-amber-100 text-amber-700': product.rank === 3,
                                        'bg-gray-50 text-gray-400': product.rank > 3
                                    }"
                                    x-text="'#' + product.rank"
                                ></div>

                                {{-- Info --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-800 truncate" x-text="product.product_name"></p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <span x-text="'Prediksi: ' + product.predicted_qty + ' unit'"></span>
                                    </p>
                                </div>

                                {{-- Badge --}}
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full"
                                        :class="{
                                            'bg-emerald-100 text-emerald-700': product.badge === 'high_potential',
                                            'bg-blue-100 text-blue-700': product.badge === 'stable',
                                            'bg-red-100 text-red-600': product.badge === 'low'
                                        }"
                                        x-text="product.badge === 'high_potential' ? 'Potensi Tinggi' : (product.badge === 'stable' ? 'Stabil' : 'Rendah')"
                                    ></span>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>
    </template>

    {{-- ================================================================
         EMPTY STATE
    ================================================================ --}}
    <template x-if="!loading && !hasData">
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6h13M9 17l3 3m-3-3l-3 3M3 7h13M3 7l3-3M3 7l3 3"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Data Prediksi</h3>
            <p class="text-sm text-gray-500 max-w-sm">
                Atur filter periode dan tanggal di atas, kemudian klik <strong>Analisa</strong> untuk memulai prediksi pendapatan.
            </p>
        </div>
    </template>

</div>
@endsection

@push('scripts')

<script>
function predictionApp() {
    return {
        loading: false,
        hasData: false,
        showWeightConfig: false,
        chartInstance: null,
        currentPage: 1,
        perPage: 10,
        weightsError: '',

        filter: {
            period:     'monthly',
            start_date: '{{ $defaults['start_date'] }}',
            end_date:   '{{ $defaults['end_date'] }}',
            window:     '3',
            weights:    '', // DYNAMIC: CSV format atau kosong untuk auto-generate
        },

        errors: {
            start_date: '',
            end_date:   '',
        },

        flashMessage: {
            show:    false,
            type:    'success',
            message: '',
        },

        result: {
            summary:  {},
            table:    [],
            products: [],
            chart:    {},
        },

        get paginatedTable() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.result.table.slice(start, start + this.perPage);
        },

        get totalPages() {
            return Math.ceil(this.result.table.length / this.perPage);
        },

        init() {
            this.$nextTick(() => {
                this.fetchData();
            });
        },

        /**
         * =====================================================================
         * UPDATE WEIGHTS INPUTS WHEN WINDOW CHANGES
         * =====================================================================
         */
        updateWeightsInputs() {
            // Auto-generate bobot sesuai window baru
            const defaultWeights = this.generateDefaultWeights();
            this.filter.weights = defaultWeights.join(',');
            this.weightsError = '';
        },

        /**
         * =====================================================================
         * VALIDATE WEIGHTS INPUT
         * =====================================================================
         */
        validateWeightsInput() {
            const window = parseInt(this.filter.window);
            const input = this.filter.weights.trim();

            // Kosong = valid (akan di-auto-generate di backend)
            if (!input) {
                this.weightsError = '';
                return;
            }

            // Parse input
            const parts = input.split(',').map(v => {
                const num = parseInt(v.trim());
                return isNaN(num) ? null : num;
            });

            // Validasi
            if (parts.includes(null)) {
                this.weightsError = '⚠️ Format tidak valid. Gunakan angka terpisah koma.';
                return;
            }

            if (parts.some(v => v < 1)) {
                this.weightsError = '⚠️ Semua bobot harus >= 1.';
                return;
            }

            if (parts.length !== window) {
                this.weightsError = `⚠️ Jumlah bobot (${parts.length}) tidak sesuai window (${window}). Akan di-auto-generate.`;
                return;
            }

            // Valid
            this.weightsError = '';
        },

        /**
         * =====================================================================
         * GENERATE DEFAULT WEIGHTS (ASCENDING)
         * =====================================================================
         */
        generateDefaultWeights() {
            const window = parseInt(this.filter.window);
            return Array.from({ length: window }, (_, i) => i + 1);
        },

        /**
         * =====================================================================
         * GET DISPLAY WEIGHTS
         * =====================================================================
         */
        getDisplayWeights() {
            const input = this.filter.weights.trim();
            if (!input) {
                const defaultWeights = this.generateDefaultWeights();
                return '[' + defaultWeights.join(', ') + '] (auto-generate)';
            }
            return '[' + input.replace(/,/g, ', ') + ']';
        },

        /**
         * =====================================================================
         * SET WEIGHTS PRESET
         * =====================================================================
         */
        setWeightsPreset(type) {
            const window = parseInt(this.filter.window);
            let weights = [];

            if (type === 'ascending') {
                // [1, 2, 3, 4, ...]
                weights = Array.from({ length: window }, (_, i) => i + 1);
            } else if (type === 'equal') {
                // [1, 1, 1, 1, ...]
                weights = Array.from({ length: window }, () => 1);
            } else if (type === 'exponential') {
                // [1, 2, 4, 8, ...]
                weights = Array.from({ length: window }, (_, i) => Math.pow(2, i));
            }

            this.filter.weights = weights.join(',');
            this.weightsError = '';
        },

        validate() {
            this.errors = { start_date: '', end_date: '' };
            let valid = true;

            if (!this.filter.start_date) {
                this.errors.start_date = 'Tanggal mulai wajib diisi.';
                valid = false;
            }
            if (!this.filter.end_date) {
                this.errors.end_date = 'Tanggal selesai wajib diisi.';
                valid = false;
            }
            if (this.filter.start_date && this.filter.end_date) {
                if (new Date(this.filter.end_date) < new Date(this.filter.start_date)) {
                    this.errors.end_date = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
                    valid = false;
                }
            }
            return valid;
        },

        async fetchData() {
            if (!this.validate()) return;

            this.loading = true;
            this.hasData = false;
            this.currentPage = 1;

            const params = new URLSearchParams({
                period:     this.filter.period,
                start_date: this.filter.start_date,
                end_date:   this.filter.end_date,
                window:     this.filter.window,
                weights:    this.filter.weights, // CSV format atau kosong
                _token:     '{{ csrf_token() }}',
            });

            try {
                const response = await fetch(`{{ route('admin.prediction.data') }}?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });

                const json = await response.json();
                console.group("========== PREDICTION ==========");

                console.log("Request Filter");
                console.table({
                    period: this.filter.period,
                    start_date: this.filter.start_date,
                    end_date: this.filter.end_date,
                    window: this.filter.window,
                    weights: this.filter.weights
                });

                console.log("HTTP Status");
                console.log(response.status);

                console.log("Raw JSON");
                console.log(json);

                if (json.data) {
                    console.log("Summary");
                    console.log(json.data.summary);
                    console.log("Chart Labels");
                    console.log(json.data.chart.labels);
                    console.log("Actual");
                    console.log(json.data.chart.actual);
                    console.log("SMA");
                    console.log(json.data.chart.sma);
                    console.log("WMA");
                    console.log(json.data.chart.wma);
                }

                console.groupEnd();
                
                if (!response.ok || !json.success) {
                    throw new Error(json.message || 'Gagal memuat data.');
                }

                this.result = json.data;
this.hasData = this.result.table && this.result.table.length > 0;

if (this.hasData) {

    await this.$nextTick();

    this.renderChart(this.result.chart);

    const weightsUsed =
        this.result.summary.weights_display || 'auto-generated';

    this.showFlash(
        'success',
        `Analisis dengan window=${this.result.summary.window_size}, bobot=[${weightsUsed}]`
    );

} else {
    this.showFlash(
        'error',
        'Tidak ada data pada rentang tanggal tersebut.'
    );
} else {
                    this.showFlash('error', 'Tidak ada data pada rentang tanggal tersebut.');
                }
            } catch (err) {
                this.showFlash('error', err.message || 'Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                this.loading = false;
            }
        },

        renderChart(chartData) {
            console.group("========== RENDER CHART ==========");
            console.log("Chart Data");
            console.log(chartData);
            console.log("Labels :", chartData.labels?.length);
            console.log("Actual :", chartData.actual?.length);
            console.log("SMA    :", chartData.sma?.length);
            console.log("WMA    :", chartData.wma?.length);
            console.groupEnd();
            
            try {
                const canvas = document.getElementById('predictionChart');
                
                if (!canvas) {
                    console.error("Canvas tidak ditemukan");
                    return;
                }

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    console.error("Context null");
                    return;
                }

                if (typeof Chart === 'undefined') {
                    console.error("Chart.js belum dimuat");
                    return;
                }

                // Cek apakah canvas sudah memiliki dimensi
                console.log("Canvas width: ", canvas.width);
                console.log("Canvas height: ", canvas.height);

                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Aktual',
                                data: chartData.actual,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59,130,246,0.08)',
                                borderWidth: 2.5,
                                pointBackgroundColor: '#3b82f6',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: 'SMA',
                                data: chartData.sma,
                                borderColor: '#10b981',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointBackgroundColor: '#10b981',
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                tension: 0.4,
                                borderDash: [],
                                spanGaps: false, // Perbaikan: spanGaps dimatikan
                            },
                            {
                                label: 'WMA',
                                data: chartData.wma,
                                borderColor: '#a855f7',
                                backgroundColor: 'transparent',
                                borderWidth: 2.5,
                                pointBackgroundColor: '#a855f7',
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                tension: 0.4,
                                borderDash: [5, 4],
                                spanGaps: false, // Perbaikan: spanGaps dimatikan
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#94a3b8',
                                bodyColor: '#f1f5f9',
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: (context) => {
                                        if (context.parsed.y === null) return null;
                                        return ` ${context.dataset.label}: ${this.formatRupiah(context.parsed.y)}`;
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { size: 11 },
                                    autoSkip: true,         // Perbaikan untuk label mingguan/harian yg banyak
                                    maxTicksLimit: 10,      // Max label tampil di sumbu X
                                    maxRotation: 45,
                                    minRotation: 45,
                                },
                                border: { display: false },
                            },
                            y: {
                                grid: {
                                    color: 'rgba(226,232,240,0.6)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { size: 11 },
                                    callback: (value) => this.formatRupiahShort(value),
                                },
                                border: { display: false },
                            },
                        },
                        animation: {
                            duration: 700,
                            easing: 'easeInOutQuart',
                        },
                    },
                });

                console.log("SUCCESS CREATE CHART");
                console.log(this.chartInstance);

            } catch (e) {
                console.error("FAILED CREATE CHART");
                console.error(e);
            }
        },

        showFlash(type, message) {
            this.flashMessage = { show: true, type, message };
            setTimeout(() => { this.flashMessage.show = false; }, 5000);
        },

        formatRupiah(value) {
            if (value === null || value === undefined) return '-';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        formatRupiahShort(value) {
            if (value >= 1_000_000_000) return 'Rp ' + (value / 1_000_000_000).toFixed(1) + 'M';
            if (value >= 1_000_000)     return 'Rp ' + (value / 1_000_000).toFixed(1) + 'jt';
            if (value >= 1_000)         return 'Rp ' + (value / 1_000).toFixed(0) + 'rb';
            return 'Rp ' + value;
        },
    };
}
</script>
@endpush
