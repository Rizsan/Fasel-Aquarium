{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>@yield('title', 'TokoApp') — Belanja Online Modern</title>

    {{-- Tailwind via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
    @if($settings?->favicon && Storage::disk('public')->exists($settings->favicon))
    <link rel="icon" type="image/png" href="{{ asset('storage/'.$settings->favicon) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @endif
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    {{-- Header --}}
    @include('partials.header')

    {{-- Alert Session --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
