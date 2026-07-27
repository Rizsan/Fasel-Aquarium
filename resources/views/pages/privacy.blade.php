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
                            <span class="text-gray-400 select-none">Kebijakan Privasi</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
                Kebijakan Privasi
            </h1>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl">
                Bagaimana komitmen Fasel Aquarium dalam menjaga keamanan privasi dan data pribadi milik pengguna.
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
                        <span class="mr-3 text-gray-400">1.</span> Data yang Dikumpulkan
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Kami hanya meminta dan menyimpan data pribadi seminimal mungkin yang diperlukan untuk keperluan pendaftaran akun serta transaksi di dalam platform kami. Data yang kami kumpulkan meliputi:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Nama Lengkap Pengguna</li>
                        <li>Alamat Email Aktif</li>
                        <li>Nomor Kontak WhatsApp</li>
                    </ul>
                    <p class="text-gray-600 font-medium italic bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500">
                        * Karena platform ini sepenuhnya menerapkan sistem Ambil Sendiri (Pick-up/PCB) tanpa kurir pihak ketiga, kami berkomitmen untuk tidak meminta informasi alamat rumah lengkap Anda demi menjaga kenyamanan privasi Anda.
                    </p>
                </section>

                <!-- Section 2 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">2.</span> Tujuan Penggunaan Data
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Setiap data pribadi yang diinputkan oleh pelanggan di platform Fasel Aquarium diolah secara internal dan hanya dimanfaatkan untuk tujuan-tujuan berikut:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Melakukan proses validasi serta verifikasi akun pengguna baru.</li>
                        <li>Memproses lembar pesanan ikan hias yang Anda buat di platform.</li>
                        <li>Melakukan konfirmasi pencatatan pelunasan atau status pembayaran.</li>
                        <li>Sebagai data validasi saat konfirmasi pengambilan fisik ikan hias di gerai kami.</li>
                        <li>Menghubungi nomor WhatsApp pelanggan secara langsung apabila ditemukan adanya kendala mendadak terkait ketersediaan stok atau antrean pesanan.</li>
                    </ul>
                </section>

                <!-- Section 3 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">3.</span> Keamanan Data
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Kami sangat menghargai privasi Anda dan menerapkan standar keamanan digital untuk melindungi data pelanggan yang kami kelola secara internal. Kami memberikan jaminan penuh atas hal-hal berikut:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Data pribadi disimpan dengan aman di database terenkripsi kami.</li>
                        <li>Data Anda <strong>tidak akan pernah diperjualbelikan</strong> kepada organisasi atau pihak eksternal mana pun.</li>
                        <li>Data tidak dibagikan kepada pihak ketiga di luar kebutuhan pemrosesan sistem transaksi inti.</li>
                        <li>Kami berkomitmen tidak akan menyalahgunakan data Anda untuk mengirimkan spam atau pesan promosi massal yang mengganggu tanpa izin tertulis dari Anda.</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">4.</span> Hak Pengguna
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Selaku pemilik sah dari data pribadi Anda, pelanggan memiliki hak-hak penuh di platform kami untuk melakukan tindakan-tindakan berikut:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 leading-relaxed">
                        <li>Mengubah, memperbarui, atau memperbaiki kesalahan informasi pada profil data akun Anda kapan saja.</li>
                        <li>Mengajukan permohonan pembatalan keanggotaan dan penutupan akun secara permanen.</li>
                        <li>Meminta penghapusan total seluruh data pribadi Anda dari sistem database kami dengan menghubungi admin resmi.</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-3 text-gray-400">5.</span> Kontak Admin
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Apabila Anda memiliki pertanyaan lebih lanjut mengenai kebijakan privasi ini atau bermaksud mengajukan hak penghapusan data pribadi, silakan hubungi saluran customer service resmi Fasel Aquarium di bawah ini:
                    </p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 max-w-md space-y-3">
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.288 1.498 4.757 1.498 5.4 0 9.794-4.394 9.797-9.793.001-2.615-1.012-5.074-2.855-6.918-1.843-1.843-4.298-2.856-6.913-2.857-5.399 0-9.796 4.396-9.799 9.796-.001 1.57.43 3.097 1.246 4.457l-.993 3.627 3.71-.973zm11.104-5.962c-.29-.146-1.72-.85-1.984-.946-.265-.096-.458-.145-.65.146-.192.29-.745.945-.913 1.138-.167.192-.336.216-.627.07-2.9-.125-4.473-1.313-5.23-2.625-.29-.5-.034-.77.218-1.02.227-.225.5-.58.75-.87.25-.29.332-.493.497-.822.166-.328.083-.615-.04-.87-.124-.255-1.046-2.52-1.433-3.456-.377-.907-.762-.785-1.046-.8-.242-.012-.52-.015-.797-.015-.278 0-.73.104-1.112.522-.38.417-1.454 1.42-1.454 3.465 0 2.046 1.49 4.02 1.697 4.3.208.278 2.934 4.5 7.108 6.3 1.0.43 1.77.686 2.373.877 1.002.317 1.916.273 2.637.165.803-.12 1.72-.7 1.962-1.344.242-.643.242-1.194.17-1.31-.072-.117-.265-.166-.556-.312z"/>
                            </svg>
                            <span class="font-medium mr-2">WhatsApp:</span> 
                            <a href="https://wa.me/083131871300" target="_blank" class="text-blue-600 hover:underline">083131871300</a>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium mr-2">Email:</span> 
                            <a href="mailto:faselaquarium@gmail.com" class="text-blue-600 hover:underline">faselaquarium@gmail.com</a>
                        </div>
                    </div>
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