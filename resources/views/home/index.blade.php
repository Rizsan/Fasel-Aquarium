{{-- resources/views/home/index.blade.php --}}
@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-16 px-4">
    <div class="max-w-7xl mx-auto text-center">
        
        <!-- Teks Kapsul Atas (Dinamis berdasarkan status login) -->
        <span class="inline-flex items-center gap-2 bg-blue-500 bg-opacity-40 text-blue-100 text-xs font-semibold px-4 py-1.5 rounded-full mb-4 uppercase tracking-widest select-none">
            @auth
                <span>👋 Halo, {{ auth()->user()->name }}!</span>
                <span class="opacity-40">|</span>
            @endauth
            <span>PUSAT IKAN HIAS INDRAMAYU</span>
        </span>

        <!-- Headline Utama -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
            Temukan Ikan Hias<br>
            <span class="text-yellow-300">Idaman</span> Pilihan Anda
        </h1>

        <!-- Sub-headline -->
        <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">
            Ratusan pilihan ikan hias hiasan rumah terlengkap di Indramayu. Tanpa kurir, silakan cek langsung ke lokasi sepuasnya.
        </p>

        <!-- Aksi Utama (Hanya memunculkan tombol login jika guest) -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <a href="#products"
               class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-8 py-3.5 rounded-full transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto">
                Mulai Cari
            </a>

            @guest
                <a href="{{ route('login') }}"
                   class="bg-transparent hover:bg-white hover:text-blue-950 text-white font-semibold px-8 py-3.5 rounded-full transition border-2 border-white w-full sm:w-auto text-center">
                    Masuk / Daftar
                </a>
            @endguest
        </div>

        {{-- Stats Bawah --}}
        <div class="grid grid-cols-3 gap-6 max-w-md mx-auto mt-12 border-t border-white border-opacity-10 pt-8 items-center">
            <!-- Kolom 1: Produk -->
            <div class="text-center">
                <p class="text-2xl font-extrabold text-yellow-300">
                    {{ number_format($totalProducts) }}+
                </p>
                <p class="text-blue-200 text-xs mt-1">
                    Produk
                </p>
            </div>

            <!-- Kolom 2: Pelanggan -->
            <div class="text-center">
                <p class="text-2xl font-extrabold text-yellow-300">
                    {{ number_format($totalUsers) }}+
                </p>
                <p class="text-blue-200 text-xs mt-1">
                    Pelanggan
                </p>
            </div>

            <!-- Kolom 3: Metode Belanja -->
            <div class="text-center flex flex-col justify-center leading-none">
                <p class="text-2xl font-extrabold text-yellow-300 uppercase">
                    PCB
                </p>
                <p class="text-blue-200 text-[10px] mt-1 font-bold uppercase tracking-wider whitespace-nowrap">
                    Pantau Cocok Bayar
                </p>
            </div>
        </div>

    </div>
</section>

    {{-- Category Chips --}}
    <section class="bg-white border-b border-gray-100 py-4 px-4">
        <div class="max-w-7xl mx-auto flex gap-3 overflow-x-auto pb-1 scrollbar-hide">
            @foreach(['Semua'] as $cat)
                <button class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium border transition
                    {{ $loop->first ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400 hover:text-blue-600' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </section>

    {{-- Product Section --}}
    <section id="products" class="py-12 px-4">
        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        @if(request('search'))
                            Hasil Pencarian: <span class="text-blue-600">"{{ request('search') }}"</span>
                        @else
                            Produk Pilihan
                        @endif
                    </h2>

                    <p class="text-gray-500 text-sm mt-1">
                        {{ $products->total() }} produk ditemukan
                    </p>
                </div>
            </div>

            {{-- Grid --}}
            @if($products->isNotEmpty())

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 flex flex-col items-center gap-3">

                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }}
                        dari {{ $products->total() }} produk
                    </p>

                    {{ $products->links() }}
                </div>

            @else
                <div class="text-center py-20">
                    <p class="text-xl font-semibold text-gray-500">Produk tidak ditemukan</p>
                    <p class="text-gray-400 mt-2">Coba kata kunci lain</p>

                    <a href="{{ route('home') }}"
                       class="inline-block mt-4 text-blue-600 hover:underline text-sm">
                        Lihat semua produk
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12 px-4">
    <div class="max-w-4xl mx-auto text-center text-white">
        @guest
            <!-- Tampilan Penawaran Konten untuk Pengunjung (Belum Login) -->
            <h2 class="text-3xl font-bold mb-3">Dapatkan Penawaran Spesial!</h2>
            <p class="text-indigo-200 mb-6">
                Daftar sekarang dan nikmati diskon 10% untuk pembelian pertama Anda.
            </p>

            <a href="{{ route('login') }}"
               class="inline-block bg-white text-indigo-600 font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-lg">
                Daftar Gratis
            </a>
        @endguest

        @auth
            <!-- Tampilan Apresiasi untuk Pengguna yang Sudah Bergabung (Sudah Login) -->
            <h2 class="text-3xl font-bold mb-3">Terima Kasih Telah Bergabung!</h2>
            <p class="text-indigo-200 mb-6">
                Terima kasih atas kepercayaan Anda. Akun Anda kini telah aktif dan siap digunakan untuk berbelanja ikan hias.
            </p>

            <!-- Label Status Informatif Berupa Teks Biasa (Bukan Bentuk Tombol) -->
            <div class="inline-flex items-center gap-2 text-yellow-300 text-sm font-semibold select-none bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm border border-white/10">
                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Akun Anda Telah Aktif & Terverifikasi</span>
            </div>
        @endauth
    </div>
</section>

@endsection