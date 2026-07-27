<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $about->title }} - {{ $settings->app_name }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

@if($settings?->favicon_url)
    <link rel="icon" type="image/png" href="{{ $settings->favicon_url }}">
@else
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
@endif
</head>
<body class="bg-gray-50/50 text-gray-800 antialiased selection:bg-blue-500 selection:text-white">
    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if($settings?->logo)
                    <img src="{{ $settings->logo_url }}" alt="Logo" class="h-9 w-auto object-contain">
                @endif
                <span class="font-bold text-xl tracking-tight bg-gradient-to-r from-gray-900 to-blue-900 bg-clip-text text-transparent">{{ $settings->app_name }}</span>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300 group">
                <span class="transform group-hover:-translate-x-1 transition-transform duration-300">←</span> Kembali ke Beranda
            </a>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section id="hero" class="relative bg-white pt-20 pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] rounded-full bg-blue-400/10 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[35rem] h-[35rem] rounded-full bg-purple-400/10 blur-[120px]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 text-left">
                    <span class="inline-block px-4 py-1.5 mb-5 text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Tentang Perusahaan</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-[1.1] mb-6">
                        {{ $about->title }}
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 font-normal leading-relaxed max-w-xl mb-8">
                        {{ $settings->slogan }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#galeri" class="px-6 py-3.5 bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            Lihat Galeri Kami
                        </a>
                        <a href="#lokasi" class="px-6 py-3.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 hover:-translate-y-0.5 transition-all duration-300">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl transform rotate-3 scale-102 opacity-10 blur-sm"></div>
                        <div class="relative bg-white p-4 rounded-2xl border border-gray-100 shadow-xl">
                            <div class="aspect-square bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white p-8 overflow-hidden relative group">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <div class="text-center relative z-10 transform group-hover:scale-105 transition-transform duration-500">
                                    <i class="fa-solid fa-fish-fins text-7xl mb-4 animate-bounce duration-1000"></i>
                                    <p class="font-bold text-2xl tracking-wide uppercase">{{ $settings->app_name }}</p>
                                    <div class="w-12 h-1 bg-white/60 mx-auto my-3 rounded-full"></div>
                                    <p class="text-sm text-blue-100 italic">{{ $settings->slogan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TENTANG KAMI SECTION --}}
    <section class="py-20 bg-white border-t border-gray-100/80">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center gap-2">
                        <div class="w-8 h-[2px] bg-blue-600 rounded-full"></div>
                        <span class="text-xs font-bold text-blue-600 tracking-wider uppercase">Kenali Kami</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Tentang Kami</h2>
                    <div class="text-gray-600 leading-relaxed space-y-5 text-base sm:text-lg font-normal">
                        @foreach(explode("\n", $about->about_content) as $line)
    @if(trim($line))
        <p class="text-justify">
            {{ $line }}
        </p>
    @endif
@endforeach
                        @foreach($lines as $line)
                            @if(trim($line))
                                <p class="text-justify">{!! $line !!}</p>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="grid grid-cols-2 gap-4 sm:gap-6">
                        @foreach($galleries->take(4) as $gallery)
                            <div class="rounded-2xl overflow-hidden bg-white border border-gray-100 p-2 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                <div class="rounded-xl overflow-hidden h-40 sm:h-48 relative">
                                    <img src="{{ $gallery->image_url }}" 
                                         alt="Preview Gallery" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MENGAPA MEMILIH KAMI --}}
    <section class="py-20 bg-gray-50/70 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 mb-3 text-[11px] font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Keunggulan</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Mengapa Memilih {{ $settings->app_name }}?</h2>
                <div class="w-12 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 sm:gap-8">
                @php
                    $features = preg_split('/[\n✓\-]/', $about->why_choose_us);
                    $iconList = ['fa-star', 'fa-shield-halved', 'fa-thumbs-up', 'fa-award', 'fa-heart', 'fa-gem'];
                @endphp
                @foreach($features as $key => $feature)
                    @if(trim($feature))
                        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            <div class="flex flex-col sm:flex-row items-start gap-5">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-inner">
                                        <i class="fas {{ $iconList[$key % count($iconList)] }} text-xl"></i>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Kelebihan Layanan</h3>
                                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed font-normal">{{ trim($feature) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- CARA BERBELANJA --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="inline-block px-3 py-1 mb-3 text-[11px] font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Panduan</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Bagaimana Cara Berbelanja?</h2>
                <div class="w-12 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="relative">
                @php
                    $steps = preg_split('/\d+\./', $about->how_to_shop);
                    $filteredSteps = array_values(array_filter(array_map('trim', $steps)));
                    $totalSteps = count($filteredSteps);
                @endphp
                
                {{-- Alur Garis Penghubung Desktop --}}
                <div class="hidden md:block absolute top-10 left-[10%] right-[10%] h-[2px] bg-gray-100 z-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 w-1/2 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-10 relative z-10">
                    @foreach($filteredSteps as $index => $step)
                        <div class="text-center group flex flex-col items-center md:block">
                            <div class="relative mb-6 inline-flex items-center justify-center md:mx-auto">
                                <div class="absolute inset-0 bg-blue-100 rounded-full scale-0 group-hover:scale-125 transition-transform duration-300 opacity-60"></div>
                                <div class="relative inline-flex items-center justify-center h-20 w-20 rounded-full bg-white border-4 border-gray-50 text-blue-600 shadow-md group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-50 transition-all duration-300 font-extrabold text-xl z-10">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="bg-gray-50 md:bg-transparent p-5 md:p-0 rounded-2xl border border-gray-100 md:border-0 w-full max-w-sm md:max-w-none shadow-sm md:shadow-none">
                                <h4 class="font-bold text-gray-900 text-base mb-2 tracking-tight">Langkah {{ $index + 1 }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed font-normal px-2">{{ $step }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- FASILITAS --}}
    <section class="py-20 bg-gray-50/70 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 mb-3 text-[11px] font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Kenyamanan</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Fasilitas Kami</h2>
                <div class="w-12 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="max-w-4xl mx-auto bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 hidden sm:block">
                        <div class="h-14 w-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-building-circle-check text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-gray-600 text-base sm:text-lg leading-relaxed font-normal space-y-2 prose max-w-none">
                        {!! nl2br($about->facilities) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- GALERI LENGKAP --}}
    <section id="galeri" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 mb-3 text-[11px] font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Dokumentasi</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Galeri Dokumentasi</h2>
                <div class="w-12 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($galleries as $gallery)
                        <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-300 group cursor-pointer hover:-translate-y-1">
                            <div class="relative h-64 rounded-xl overflow-hidden">
                                <img src="{{ $gallery->image_url }}" 
                                     alt="Gallery Photo" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/70 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                                    <div class="text-white transform translate-y-3 group-hover:translate-y-0 transition-transform duration-300">
                                        <p class="text-xs uppercase tracking-widest text-blue-400 font-bold mb-1">Koleksi Terkini</p>
                                        <h4 class="font-bold text-base tracking-tight">{{ $settings->app_name }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 max-w-md mx-auto">
                    <i class="fa-regular fa-images text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500 font-medium">Galeri foto belum tersedia</p>
                </div>
            @endif
        </div>
    </section>

    {{-- LOKASI / MAP --}}
    <section id="lokasi" class="py-20 bg-gray-50/70 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 mb-3 text-[11px] font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full">Kunjungi Kami</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Lokasi & Kontak</h2>
                <div class="w-12 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                {{-- Map Card --}}
                <div class="lg:col-span-7 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex items-center gap-3 mb-4 px-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                        <h3 class="text-gray-900 font-bold text-base tracking-tight">Peta Petunjuk Arah</h3>
                    </div>
                    <div id="about-map" class="w-full h-96 sm:h-[26rem] rounded-xl overflow-hidden border border-gray-100 shadow-inner relative z-0 flex-grow"></div>
                </div>

                {{-- Info Card --}}
                <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between space-y-8">
                    <div class="space-y-6">
                        {{-- Alamat --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 h-11 w-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Alamat Lengkap</h4>
                                <p class="text-gray-600 text-sm sm:text-base font-normal leading-relaxed">{{ $about->contact_address ?? $settings->address }}</p>
                            </div>
                        </div>

                        {{-- Jam Operasional --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                                <i class="fas fa-clock text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Jam Operasional</h4>
                                <p class="text-gray-600 text-sm sm:text-base font-normal leading-relaxed">{{ $about->operation_hours }}</p>
                            </div>
                        </div>

                        {{-- Hubungi Kami --}}
                        <div class="flex items-start gap-4 pt-2 border-t border-gray-100">
                            <div class="flex-shrink-0 h-11 w-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shadow-sm">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <div class="space-y-3 w-full">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Saluran Komunikasi</h4>
                                <div class="grid grid-cols-1 gap-2">
                                    @if($about->contact_whatsapp)
                                        <a href="https://wa.me/{{ str_replace(['0', '-', ' ', '+'], '', $about->contact_whatsapp) }}" 
                                           class="flex items-center gap-3 text-sm sm:text-base text-gray-600 hover:text-green-600 transition duration-300 group">
                                            <i class="fab fa-whatsapp text-green-500 text-lg w-5"></i>
                                            <span class="font-medium group-hover:underline">{{ $about->contact_whatsapp }}</span>
                                        </a>
                                    @endif
                                    
                                    @if($about->contact_phone)
                                        <a href="tel:{{ $about->contact_phone }}" 
                                           class="flex items-center gap-3 text-sm sm:text-base text-gray-600 hover:text-blue-600 transition duration-300 group">
                                            <i class="fas fa-phone-alt text-blue-500 text-sm w-5"></i>
                                            <span class="font-medium group-hover:underline">{{ $about->contact_phone }}</span>
                                        </a>
                                    @endif
                                    
                                    @if($about->contact_instagram)
                                        <a href="https://instagram.com/{{ str_replace('@', '', $about->contact_instagram) }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 text-sm sm:text-base text-gray-600 hover:text-pink-600 transition duration-300 group">
                                            <i class="fab fa-instagram text-pink-500 text-lg w-5"></i>
                                            <span class="font-medium group-hover:underline">{{ $about->contact_instagram }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl p-8 sm:p-12 text-center text-white shadow-xl relative overflow-hidden group">
                <div class="absolute inset-0 z-0">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10"></div>
                    <div class="absolute bottom-0 left-0 w-60 h-60 bg-indigo-500/10 rounded-full blur-xl transform -translate-x-10 translate-y-10"></div>
                </div>
                <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Siap Berbelanja?</h2>
                    <p class="text-blue-100 text-base sm:text-lg leading-relaxed font-normal">
                        Jelajahi koleksi ikan hias kami yang lengkap dan berkualitas tinggi sekarang juga. Kami siap melayani Anda dengan sepenuh hati.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 pt-2">
                        <a href="{{ route('home') }}" class="px-8 py-3.5 bg-white text-blue-700 font-bold text-sm rounded-xl shadow-md hover:bg-blue-50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            Lihat Produk Kami
                        </a>
                        <a href="#lokasi" class="px-8 py-3.5 bg-blue-500/30 text-white font-bold text-sm rounded-xl hover:bg-blue-500/40 border border-white/20 hover:-translate-y-0.5 transition-all duration-300">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const lat = {{ $settings?->latitude ?? -6.3334185 }};
    const lng = {{ $settings?->longitude ?? 108.3242836 }};

    const map = L.map('about-map', {
        center: [lat, lng],
        zoom: 17,
        zoomControl: true,
        scrollWheelZoom: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const markerIcon = L.divIcon({
        html: `
            <div style="
                background:#2563eb;
                width:14px;
                height:14px;
                border-radius:50%;
                border:3px solid #fff;
                box-shadow:0 2px 8px rgba(0,0,0,.4);
            "></div>
        `,
        className: '',
        iconSize: [14,14],
        iconAnchor: [7,7]
    });

    L.marker([lat, lng], { icon: markerIcon })
        .addTo(map)
        .bindPopup(`
            <strong>{{ $settings?->app_name }}</strong><br>
            {{ $settings?->address }}
        `)
        .openPopup();

    setTimeout(() => map.invalidateSize(), 300);

});
</script>

</body>
</html>