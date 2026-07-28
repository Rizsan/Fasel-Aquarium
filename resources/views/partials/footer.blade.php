@php
    $settings = \App\Models\WebsiteSetting::getInstance();
@endphp

{{-- resources/views/partials/footer.blade.php --}}
<footer class="bg-gray-900 text-gray-300 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Kolom 1 — Deskripsi & Sosial Media --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    @if($settings?->logo_url)
    <img
        src="{{ $settings->logo_url }}"
        class="w-8 h-8 rounded-lg object-cover"
        alt="{{ $settings?->app_name ?? 'Fasel Aquarium' }}">
@else
    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
        </svg>
    </div>
@endif
                    
                    <span class="text-white font-bold text-lg">
                        {{ $settings?->app_name ?? 'Fasel Aquarium' }}
                    </span>
                </div>

                <p class="text-gray-400 text-sm leading-relaxed">
                    {{ $settings?->slogan ?? 'Platform Penjualan Ikan Hias Indramayu' }}
                </p>

                {{-- Sosial Media (Hanya muncul jika diisi di admin) --}}
                <div class="flex gap-3 mt-4">
                    @if($settings?->instagram)
                        <a href="{{ $settings->instagram }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    @endif

                    @if($settings?->facebook)
                        <a href="{{ $settings->facebook }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kolom 2 — Kontak & Link --}}
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Informasi</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Tentang Kami
                        </a>
                    </li>
                    <li>
    <a href="{{ route('terms') }}"
       class="text-gray-400 hover:text-white transition flex items-center gap-2">
        Syarat & Ketentuan
    </a>
</li>

<li>
    <a href="{{ route('privacy') }}"
       class="text-gray-400 hover:text-white transition flex items-center gap-2">
        Kebijakan Privasi
    </a>
</li>
                </ul>

                <h3 class="text-white font-semibold mt-6 mb-3 text-sm uppercase tracking-wider">Kontak</h3>
                <div class="space-y-2 text-sm text-gray-400">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $settings?->email ?? 'faselaquarium@gmail.com' }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $settings?->phone ?? '-' }}
                    </p>
                    <p class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="leading-tight">{{ $settings?->address ?? 'Indramayu, Jawa Barat' }}</span>
                    </p>
                </div>
            </div>

            {{-- Kolom 3 — Leaflet Map --}}
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Lokasi Kami</h3>
                <div id="footer-map" class="w-full h-48 rounded-xl overflow-hidden border border-gray-700 shadow-lg"></div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-800 mt-10 pt-6 text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} <span class="text-white font-medium">{{ $settings?->app_name ?? 'Fasel Aquarium' }}</span>. Semua hak dilindungi.
            </p>
        </div>
    </div>
</footer>

{{-- Leaflet Map Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Koordinat Default Indramayu jika database kosong
    const lat = {{ $settings?->latitude ?? -6.3334185 }};
    const lng = {{ $settings?->longitude ?? 108.3242836 }};

    const map = L.map('footer-map', {
        center: [lat, lng],
        zoom: 17,
        zoomControl: true,
        scrollWheelZoom: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    const markerIcon = L.divIcon({
        html: '<div style="background:#2563eb;width:14px;height:14px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4);"></div>',
        className: '',
        iconSize: [14, 14],
        iconAnchor: [7, 7],
    });

    L.marker([lat, lng], { icon: markerIcon })
        .addTo(map)
        .bindPopup(`
            <strong>{{ $settings?->app_name ?? 'Fasel Aquarium' }}</strong><br>
            <small>{{ $settings?->address ?? 'Indramayu, Jawa Barat' }}</small>
        `)
        .openPopup();
});
</script>