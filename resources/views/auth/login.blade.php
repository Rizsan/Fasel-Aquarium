{{-- resources/views/auth/login.blade.php --}}
@php
    $logo = ($settings?->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings->logo))
        ? asset('storage/' . $settings->logo)
        : asset('assets/images/Logo.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk &mdash; {{ $settings?->app_name ?? 'Fasel Aquarium' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    @if($settings?->favicon && Storage::disk('public')->exists($settings->favicon))
        <link rel="icon" type="image/png" href="{{ $settings->favicon_url }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --white:        #ffffff;
            --bg-soft:      #f8fafc;
            --blue:         #2563eb;
            --blue-dark:    #1d4ed8;
            --blue-light:   #eff6ff;
            --blue-mid:     #dbeafe;
            --gray-50:      #f9fafb;
            --gray-100:     #f3f4f6;
            --gray-200:     #e5e7eb;
            --gray-300:     #d1d5db;
            --gray-400:     #9ca3af;
            --gray-500:     #6b7280;
            --gray-700:     #374151;
            --gray-900:     #111827;
            --red:          #ef4444;
            --red-light:    #fef2f2;
            --green:        #22c55e;
            --green-light:  #f0fdf4;
            --amber-light:  #fffbeb;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white);
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
        }

        /* ============================================================
           PAGE LAYOUT — 2 COLUMN
        ============================================================ */
        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 45% 55%;
        }

        /* ============================================================
           LEFT PANEL
        ============================================================ */
        .left-panel {
            position: relative;
            background-color: var(--white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid var(--gray-100);
        }

        /* Subtle background blob */
        .left-panel::before {
            content: '';
            position: absolute;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle at center, #dbeafe 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Top-right subtle accent */
        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #bfdbfe 0%, transparent 70%);
            top: -60px;
            right: -60px;
            pointer-events: none;
            z-index: 0;
        }

        /* Brand bar at top */
        .left-brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 32px 40px;
        }

        .left-brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--white);
            border: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .left-brand-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.3px;
        }

        /* Center content */
        .left-center {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 40px 40px;
            gap: 28px;
        }

        /* Illustration wrapper */
        .illustration-wrap {
            width: 100%;
            max-width: 340px;
            aspect-ratio: 1 / 0.85;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* SVG illustration — aquarium themed */
        .illustration-wrap svg {
            width: 100%;
            height: 100%;
        }

        .left-text {
            text-align: center;
        }

        .left-heading {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            line-height: 1.25;
        }

        .left-sub {
            font-size: 0.875rem;
            color: var(--gray-500);
            line-height: 1.6;
            max-width: 280px;
            margin: 0 auto;
        }

        /* Feature pills */
        .left-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            max-width: 320px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--blue-light);
            border: 1px solid var(--blue-mid);
            color: var(--blue);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 100px;
        }

        .pill-dot {
            width: 5px;
            height: 5px;
            background: var(--blue);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Footer */
        .left-footer {
            position: relative;
            z-index: 2;
            padding: 20px 40px;
            font-size: 0.75rem;
            color: var(--gray-400);
            border-top: 1px solid var(--gray-100);
        }

        /* ============================================================
           RIGHT PANEL
        ============================================================ */
        .right-panel {
            background-color: var(--bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            min-height: 100vh;
        }

        /* Form card */
        .form-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            box-shadow:
                0 1px 3px rgba(0,0,0,0.04),
                0 8px 24px rgba(0,0,0,0.06),
                0 24px 48px rgba(0,0,0,0.04);
            padding: 44px 40px;
            width: 100%;
            max-width: 440px;
            animation: fadeSlideUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Form header */
        .form-header {
            margin-bottom: 32px;
        }

        .form-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--blue-light);
            color: var(--blue);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 100px;
            margin-bottom: 14px;
        }

        .form-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.6px;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .form-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
            line-height: 1.5;
        }

        /* ============================================================
           ALERTS
        ============================================================ */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 0.825rem;
            margin-bottom: 20px;
            line-height: 1.5;
            animation: fadeSlideUp 0.3s ease both;
        }

        .alert svg { flex-shrink: 0; margin-top: 1px; }

        .alert-error {
            background: var(--red-light);
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .alert-success {
            background: var(--green-light);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-info {
            background: var(--blue-light);
            border: 1px solid var(--blue-mid);
            color: var(--blue-dark);
        }

        /* ============================================================
           FORM FIELDS
        ============================================================ */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 7px;
            letter-spacing: 0.1px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            height: 50px;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            padding: 0 44px;
            color: var(--gray-900);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .form-input::placeholder {
            color: var(--gray-300);
            font-weight: 400;
        }

        .form-input:hover {
            border-color: var(--gray-300);
        }

        .form-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: var(--white);
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--blue);
        }

        .form-input.is-invalid {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #dc2626;
            font-size: 0.76rem;
            font-weight: 500;
            margin-top: 6px;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray-400);
            padding: 4px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: color 0.2s ease, background 0.15s ease;
        }

        .password-toggle:hover {
            color: var(--gray-700);
            background: var(--gray-100);
        }

        /* ============================================================
           REMEMBER + FORGOT ROW
        ============================================================ */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 12px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.83rem;
            font-weight: 500;
            color: var(--gray-600);
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid var(--gray-300);
            border-radius: 5px;
            background: var(--white);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: background 0.15s, border-color 0.15s;
        }

        .checkbox-label input[type="checkbox"]:checked {
            background: var(--blue);
            border-color: var(--blue);
        }

        .checkbox-label input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 5px;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .forgot-link {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--blue);
            text-decoration: none;
            white-space: nowrap;
            transition: color 0.2s ease;
        }

        .forgot-link:hover { color: var(--blue-dark); text-decoration: underline; }

        /* ============================================================
           SUBMIT BUTTON
        ============================================================ */
        .btn-login {
            width: 100%;
            height: 50px;
            background: var(--blue);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.1px;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 2px rgba(37,99,235,0.2), 0 4px 12px rgba(37,99,235,0.25);
        }

        .btn-login:hover {
            background: var(--blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37,99,235,0.2), 0 8px 20px rgba(37,99,235,0.3);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(37,99,235,0.2);
        }

        .btn-login:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading state */
        .btn-loading { display: none; }
        .btn-login.is-loading .btn-text { display: none; }
        .btn-login.is-loading .btn-loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.65s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ============================================================
           DIVIDER
        ============================================================ */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: var(--gray-400);
            font-size: 0.78rem;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        /* ============================================================
           FOOTER LINK
        ============================================================ */
        .form-footer {
            text-align: center;
            color: var(--gray-500);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-footer a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
            margin-left: 3px;
        }

        .form-footer a:hover {
            color: var(--blue-dark);
            text-decoration: underline;
        }

        /* ============================================================
           RESPONSIVE — Mobile: hide left panel
        ============================================================ */
        @media (max-width: 900px) {
            .page { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel {
                background: var(--white);
                padding: 40px 20px;
                align-items: flex-start;
                padding-top: 60px;
            }
            .form-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }

        @media (min-width: 901px) and (max-width: 1100px) {
            .form-card { padding: 36px 32px; }
        }
    </style>
</head>
<body>

<div class="page">

    {{-- ================================================================
         LEFT PANEL
    ================================================================ --}}
    <div class="left-panel">

        {{-- Brand --}}
        <div class="left-brand">
            <div class="left-brand-logo">
                <img src="{{ $logo }}" alt="{{ $settings?->app_name ?? 'Fasel Aquarium' }}">
            </div>
            <span class="left-brand-name">{{ $settings?->app_name ?? 'Fasel Aquarium' }}</span>
        </div>

        {{-- Center: Illustration + Text --}}
        <div class="left-center">

            {{-- SVG Illustration: Aquarium / Fish themed --}}
            <div class="illustration-wrap">
                <svg viewBox="0 0 400 340" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Aquarium tank body -->
                    <rect x="40" y="80" width="320" height="210" rx="18" fill="#eff6ff" stroke="#bfdbfe" stroke-width="2"/>

                    <!-- Water fill -->
                    <rect x="40" y="120" width="320" height="170" rx="0" fill="#dbeafe" opacity="0.6"/>
                    <rect x="40" y="260" width="320" height="30" rx="0" fill="#bfdbfe" opacity="0.5"/>

                    <!-- Tank top rim -->
                    <rect x="30" y="72" width="340" height="16" rx="8" fill="#93c5fd" opacity="0.4"/>

                    <!-- Tank stand/base -->
                    <rect x="80" y="290" width="240" height="18" rx="6" fill="#e2e8f0"/>
                    <rect x="100" y="308" width="200" height="12" rx="5" fill="#cbd5e1"/>

                    <!-- Bubbles -->
                    <circle cx="100" cy="200" r="5" fill="#bfdbfe" opacity="0.8"/>
                    <circle cx="108" cy="180" r="3.5" fill="#dbeafe" opacity="0.7"/>
                    <circle cx="95" cy="165" r="2.5" fill="#bfdbfe" opacity="0.6"/>

                    <circle cx="300" cy="210" r="4" fill="#bfdbfe" opacity="0.8"/>
                    <circle cx="307" cy="192" r="3" fill="#dbeafe" opacity="0.7"/>
                    <circle cx="298" cy="178" r="2" fill="#bfdbfe" opacity="0.6"/>

                    <!-- Seaweed left -->
                    <path d="M80 270 C75 250, 90 235, 80 215 C72 198, 88 182, 78 165"
                          stroke="#4ade80" stroke-width="4" stroke-linecap="round" fill="none" opacity="0.7"/>
                    <ellipse cx="78" cy="162" rx="8" ry="12" fill="#4ade80" opacity="0.5" transform="rotate(-15 78 162)"/>

                    <!-- Seaweed right -->
                    <path d="M330 270 C325 248, 340 232, 330 210 C322 193, 338 178, 328 158"
                          stroke="#34d399" stroke-width="4" stroke-linecap="round" fill="none" opacity="0.7"/>
                    <ellipse cx="328" cy="155" rx="8" ry="11" fill="#34d399" opacity="0.5" transform="rotate(10 328 155)"/>

                    <!-- Coral/decoration middle -->
                    <ellipse cx="200" cy="268" rx="28" ry="8" fill="#f9a8d4" opacity="0.5"/>
                    <path d="M190 268 C188 252, 195 240, 192 228" stroke="#f472b6" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <path d="M200 268 C200 250, 205 237, 203 222" stroke="#ec4899" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <path d="M210 268 C212 252, 206 238, 210 226" stroke="#f472b6" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <circle cx="192" cy="225" r="6" fill="#f9a8d4" opacity="0.8"/>
                    <circle cx="203" cy="219" r="5" fill="#fbcfe8" opacity="0.8"/>
                    <circle cx="210" cy="223" r="6" fill="#f9a8d4" opacity="0.8"/>

                    <!-- Fish 1 — main blue fish -->
                    <g transform="translate(145, 168)">
                        <ellipse cx="0" cy="0" rx="38" ry="18" fill="#2563eb"/>
                        <path d="M-38 0 L-56 -16 L-56 16 Z" fill="#1d4ed8"/>
                        <circle cx="28" cy="-5" r="5" fill="white"/>
                        <circle cx="30" cy="-6" r="2.5" fill="#1e3a8a"/>
                        <!-- Stripes -->
                        <path d="M-5 -17 Q0 0 -5 17" stroke="#1d4ed8" stroke-width="2" fill="none" opacity="0.4"/>
                        <path d="M8 -17 Q13 0 8 17" stroke="#1d4ed8" stroke-width="2" fill="none" opacity="0.4"/>
                        <!-- Fin -->
                        <path d="M5 -18 L15 -30 L25 -18" fill="#3b82f6" opacity="0.7"/>
                    </g>

                    <!-- Fish 2 — small orange fish -->
                    <g transform="translate(260, 195) scale(-1,1)">
                        <ellipse cx="0" cy="0" rx="22" ry="11" fill="#fb923c"/>
                        <path d="M-22 0 L-34 -10 L-34 10 Z" fill="#ea580c"/>
                        <circle cx="16" cy="-3" r="3.5" fill="white"/>
                        <circle cx="17" cy="-4" r="1.8" fill="#431407"/>
                        <path d="M3 -10 L9 -18 L15 -10" fill="#fdba74" opacity="0.8"/>
                    </g>

                    <!-- Fish 3 — tiny yellow fish top right -->
                    <g transform="translate(290, 150)">
                        <ellipse cx="0" cy="0" rx="16" ry="8" fill="#fbbf24"/>
                        <path d="M-16 0 L-24 -7 L-24 7 Z" fill="#f59e0b"/>
                        <circle cx="11" cy="-2" r="2.5" fill="white"/>
                        <circle cx="12" cy="-2.5" r="1.2" fill="#451a03"/>
                    </g>

                    <!-- Sand / gravel bottom -->
                    <ellipse cx="200" cy="270" rx="155" ry="10" fill="#fde68a" opacity="0.45"/>
                    <ellipse cx="200" cy="272" rx="155" ry="6" fill="#fcd34d" opacity="0.3"/>

                    <!-- Small pebbles -->
                    <circle cx="140" cy="271" r="4" fill="#d1d5db" opacity="0.6"/>
                    <circle cx="155" cy="273" r="3" fill="#e5e7eb" opacity="0.6"/>
                    <circle cx="240" cy="272" r="4" fill="#d1d5db" opacity="0.6"/>
                    <circle cx="260" cy="270" r="3" fill="#e5e7eb" opacity="0.6"/>

                    <!-- Light reflection on tank glass -->
                    <path d="M56 88 L56 280" stroke="white" stroke-width="3" stroke-linecap="round" opacity="0.25"/>
                    <path d="M68 88 L68 200" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity="0.15"/>
                </svg>
            </div>

            {{-- Text --}}
            <div class="left-text">
                <h2 class="left-heading">Selamat Datang Kembali</h2>
                <p class="left-sub">Belanja ikan hias menjadi lebih mudah, aman, dan menyenangkan.</p>
            </div>

            {{-- Feature pills --}}
            <div class="left-pills">
                <span class="pill"><span class="pill-dot"></span>Katalog Lengkap</span>
                <span class="pill"><span class="pill-dot"></span>Bayar di Toko</span>
                <span class="pill"><span class="pill-dot"></span>Lacak Pesanan</span>
                <span class="pill"><span class="pill-dot"></span>Aman & Terpercaya</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="left-footer">
            &copy; {{ date('Y') }} {{ $settings?->app_name ?? 'Fasel Aquarium' }}. Seluruh hak cipta dilindungi.
        </div>
    </div>

    {{-- ================================================================
         RIGHT PANEL — FORM
    ================================================================ --}}
    <div class="right-panel">
        <div class="form-card">

            {{-- Header --}}
            <div class="form-header">
                <div class="form-badge">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                    Ruang Pelanggan
                </div>
                <h1 class="form-title">Masuk ke akun Anda</h1>
                <p class="form-subtitle">Silakan masukkan email dan password untuk melanjutkan.</p>
            </div>

            {{-- ===== ALERTS ===== --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- ===== FORM ===== --}}
            <form method="POST" action="{{ route('login.store') }}" id="loginForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input @error('email') is-invalid @enderror"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            autofocus
                            autocomplete="email"
                        />
                    </div>
                    @error('email')
                        <p class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                        />
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">
    Lupa password?
</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" id="submitBtn">
                    <span class="btn-text">Masuk Sekarang</span>
                    <span class="btn-loading">
                        <span class="spinner"></span>
                        Memverifikasi...
                    </span>
                </button>
            </form>

            <div class="divider">atau</div>

            <div class="form-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>

        </div>
    </div>

</div>

{{-- ===== SCRIPTS — UNCHANGED ===== --}}
<script>
    // Toggle visibility password
    const toggleBtn   = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon     = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        eyeIcon.innerHTML = isHidden
            ? '<line x1="1" y1="1" x2="23" y2="23"></line><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    });

    // Loading state saat submit
    const form      = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function () {
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;
    });
</script>

</body>
</html>