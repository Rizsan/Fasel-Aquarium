@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans antialiased text-gray-800">
    
    <!-- Hero Section Sederhana -->
    <div class="bg-white border-b border-gray-200 py-12 md:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="hover:text-blue-600 transition-colors">Beranda</a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="text-gray-400 select-none">Syarat & Ketentuan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
                Syarat & Ketentuan
            </h1>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl">
                Aturan main dan ketentuan penggunaan layanan platform Fasel Aquarium Indramayu.
            </p>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 gap-8">
            
            <!-- Tombol Kembali -->
            <div class="mb-2">
                <a href="/" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Card Utama Konten -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-10 space-y-10">
                
                <!-- Section 1 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">1.</span> Ketentuan Umum
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Platform Fasel Aquarium melayani pembelian ikan hias secara online untuk wilayah lokal.</li>
                        <li>Pengguna wajib menggunakan layanan yang disediakan di platform ini dengan itikad baik dan tidak menyalahgunakan sistem.</li>
                        <li>Pengguna wajib memberikan informasi data diri yang benar, akurat, dan dapat dipertanggungjawabkan saat melakukan transaksi.</li>
                        <li>Layanan platform ini <strong>hanya melayani wilayah Indramayu dan sekitarnya</strong>.</li>
                    </ul>
                </section>

                <!-- Section 2 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">2.</span> Ketentuan Tanpa Pengiriman
                    </h2>
                    <div class="p-4 bg-amber-50 rounded-lg text-amber-900 border border-amber-200 mb-3 text-sm">
                        <strong>Pemberitahuan Penting:</strong> Fasel Aquarium <strong>TIDAK</strong> menyediakan metode pengiriman menggunakan jasa kurir, ekspedisi, maupun paket logistik apa pun demi menjaga keselamatan dan kesehatan ikan.
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Semua transaksi wajib diselesaikan secara langsung oleh pembeli melalui salah satu metode berikut:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li><strong>Pick Up (Ambil Sendiri):</strong> Pembeli melakukan pemesanan, menyelesaikan pembayaran, lalu datang ke gerai toko fisik kami untuk mengambil ikan hias yang telah dipesan.</li>
                        <li><strong>PCB (Pantau, Cocok, Bayar):</strong> Pembeli memesan atau mengamankan stok ikan di platform, kemudian datang langsung ke gerai kami untuk memantau kualitas fisik serta kelincahan ikan secara langsung. Jika kondisi ikan dirasa cocok dan sesuai harapan, pembeli dapat langsung melakukan pelunasan pembayaran di lokasi dan membawa ikan pulang.</li>
                    </ul>
                </section>

                <!-- Section 3 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">3.</span> Batas Waktu Pengambilan
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Setelah proses checkout berhasil dilakukan oleh pembeli di sistem e-commerce, ikan hias pesanan Anda akan otomatis masuk ke status <em>booking</em> dengan batas waktu <strong>maksimal 2x24 jam</strong>.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Apabila dalam kurun waktu tersebut pembeli belum datang mengambil pesanan atau tidak memberikan konfirmasi lanjutan yang jelas kepada pihak admin, maka <strong>pesanan akan otomatis dibatalkan secara sepihak oleh sistem</strong> agar stok ikan dapat dilepas kembali untuk dijual kepada pelanggan lain.
                    </p>
                </section>

                <!-- Section 4 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">4.</span> Garansi dan Pembatalan
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Ikan hias yang sudah diserahterimakan, keluar dari toko, dan dibawa pulang oleh pihak pembeli <strong>tidak dapat diretur, ditukar, atau dikembalikan</strong> dengan alasan apa pun.</li>
                        <li>Pembeli memiliki hak dan kewajiban penuh untuk memeriksa dengan sangat teliti kondisi fisik, kesehatan, dan kelincahan ikan secara saksama pada saat proses pengambilan di lokasi gerai kami.</li>
                        <li>Jika saat proses pantau langsung di lokasi kondisi fisik ikan ternyata terbukti cacat, sakit, atau tidak sesuai dengan foto/ekspektasi yang tertera pada deskripsi produk, pembeli <strong>berhak membatalkan transaksi sepenuhnya di lokasi</strong> sebelum membawa ikan tersebut pulang ke rumah.</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">5.</span> Pembayaran
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Fasel Aquarium menerima penyelesaian transaksi melalui beberapa opsi metode pembayaran resmi, yaitu:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Pembayaran Tunai (Cash) langsung di gerai toko fisik kami.</li>
                        <li>Transfer Bank secara manual ke rekening resmi milik Fasel Aquarium.</li>
                        <li>Online Payment Gateway melalui sistem Midtrans (jika fitur ini diaktifkan di platform).</li>
                    </ul>
                    <p class="text-gray-600 leading-relaxed">
                        Sebuah pesanan hanya akan dianggap sah dan diproses lebih lanjut setelah sistem menerima pelunasan pembayaran yang berhasil atau divalidasi oleh admin kami.
                    </p>
                </section>

                <!-- Section 6 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">6.</span> Perubahan Ketentuan
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Pihak manajemen Fasel Aquarium mempunyai hak penuh untuk mengubah, memperbarui, menambah, atau menghapus poin-poin dalam halaman Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan tertulis terlebih dahulu demi menyesuaikan dengan operasional toko. Pengguna disarankan untuk membaca halaman ini secara berkala.
                    </p>
                </section>

            </div>

            <!-- Footer Card Pembaruan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 max-w-xs ml-auto text-center">
                <span class="text-xs text-gray-400 block uppercase font-semibold tracking-wider">Terakhir Diperbarui</span>
                <span class="text-sm font-medium text-gray-700">12 Juli 2026</span>
            </div>

        </div>
    </div>
</div>
@endsection