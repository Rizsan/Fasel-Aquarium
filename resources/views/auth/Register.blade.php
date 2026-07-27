{{-- resources/views/auth/register.blade.php --}}
@php
    $logo = $settings?->logo_url ?: asset('assets/images/Logo.png');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar &mdash; {{ $settings?->app_name ?? 'Fasel Aquarium' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    @if($settings?->favicon_url)
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
            --gray-600:     #4b5563;
            --gray-700:     #374151;
            --gray-900:     #111827;
            --red:          #ef4444;
            --red-light:    #fef2f2;
            --green:        #22c55e;
            --green-light:  #f0fdf4;
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

        /* Subtle center glow */
        .left-panel::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle at center, #dbeafe 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Corner accent */
        .left-panel::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, #bfdbfe 0%, transparent 70%);
            bottom: 40px;
            left: -40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Brand */
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
            padding: 16px 40px 32px;
            gap: 24px;
        }

        /* Illustration */
        .illustration-wrap {
            width: 100%;
            max-width: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration-wrap svg {
            width: 100%;
            height: auto;
        }

        /* Text */
        .left-text {
            text-align: center;
        }

        .left-heading {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            line-height: 1.25;
        }

        .left-sub {
            font-size: 0.855rem;
            color: var(--gray-500);
            line-height: 1.6;
            max-width: 270px;
            margin: 0 auto;
        }

        /* Steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 0;
            width: 100%;
            max-width: 280px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 15px;
            top: 34px;
            width: 1px;
            height: 28px;
            background: linear-gradient(to bottom, #bfdbfe, transparent);
        }

        .step-num {
            width: 32px;
            height: 32px;
            background: var(--blue-light);
            border: 1.5px solid var(--blue-mid);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--blue);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .step-info {
            padding-bottom: 22px;
        }

        .step-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 2px;
        }

        .step-desc {
            font-size: 0.78rem;
            color: var(--gray-400);
        }

        /* Footer */
        .left-footer {
            position: relative;
            z-index: 2;
            padding: 18px 40px;
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
            padding: 40px;
            min-height: 100vh;
            overflow-y: auto;
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
            padding: 40px 36px;
            width: 100%;
            max-width: 460px;
            animation: fadeSlideUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Form header */
        .form-header { margin-bottom: 28px; }

        .form-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--blue-light);
            color: var(--blue);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 100px;
            margin-bottom: 12px;
        }

        .form-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.5px;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .form-subtitle {
            font-size: 0.855rem;
            color: var(--gray-500);
        }

        /* ============================================================
           ALERTS
        ============================================================ */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 0.82rem;
            margin-bottom: 18px;
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

        /* ============================================================
           FORM FIELDS
        ============================================================ */
        .form-group { margin-bottom: 16px; }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 6px;
            letter-spacing: 0.1px;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px;
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
            height: 48px;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            padding: 0 42px;
            color: var(--gray-900);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input::placeholder {
            color: var(--gray-300);
            font-weight: 400;
        }

        .form-input:hover { border-color: var(--gray-300); }

        .form-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-wrapper:focus-within .input-icon { color: var(--blue); }

        .form-input.is-invalid {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #dc2626;
            font-size: 0.73rem;
            font-weight: 500;
            margin-top: 5px;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 12px;
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
           PASSWORD STRENGTH
        ============================================================ */
        .password-strength { margin-top: 8px; }

        .strength-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 5px;
        }

        .strength-seg {
            flex: 1;
            height: 3px;
            background: var(--gray-200);
            border-radius: 2px;
            transition: background 0.3s ease;
        }

        .strength-seg.active-weak   { background: var(--red); }
        .strength-seg.active-medium { background: #f59e0b; }
        .strength-seg.active-strong { background: var(--green); }

        .strength-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gray-400);
            transition: color 0.3s ease;
        }

        .strength-label.weak   { color: var(--red); }
        .strength-label.medium { color: #f59e0b; }
        .strength-label.strong { color: var(--green); }

        /* ============================================================
           TERMS CHECKBOX
        ============================================================ */
        .terms-group { margin-bottom: 20px; }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            cursor: pointer;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--gray-500);
            user-select: none;
            line-height: 1.5;
        }

        .checkbox-label input[type="checkbox"] {
            appearance: none;
            min-width: 17px;
            height: 17px;
            border: 1.5px solid var(--gray-300);
            border-radius: 5px;
            background: var(--white);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            margin-top: 2px;
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

        .checkbox-label a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-label a:hover {
            color: var(--blue-dark);
            text-decoration: underline;
        }

        /* ============================================================
           SUBMIT BUTTON
        ============================================================ */
        .btn-register {
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

        .btn-register:hover {
            background: var(--blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37,99,235,0.2), 0 8px 20px rgba(37,99,235,0.3);
        }

        .btn-register:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(37,99,235,0.2);
        }

        .btn-register:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .btn-loading { display: none; }
        .btn-register.is-loading .btn-text { display: none; }
        .btn-register.is-loading .btn-loading {
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
           DIVIDER + FOOTER
        ============================================================ */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--gray-400);
            font-size: 0.77rem;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .form-footer {
            text-align: center;
            color: var(--gray-500);
            font-size: 0.84rem;
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
           RESPONSIVE
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
            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (min-width: 901px) and (max-width: 1100px) {
            .form-card { padding: 32px 28px; }
            .right-panel { padding: 40px 28px; }
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

        {{-- Center --}}
        <div class="left-center">

            {{-- SVG Illustration — shopping / signup themed --}}
            <div class="illustration-wrap">
                <svg viewBox="0 0 360 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background soft circle -->
                    <circle cx="180" cy="150" r="130" fill="#eff6ff" opacity="0.7"/>

                    <!-- Monitor / Laptop base -->
                    <rect x="80" y="60" width="200" height="130" rx="12" fill="white" stroke="#bfdbfe" stroke-width="2"/>
                    <rect x="80" y="60" width="200" height="130" rx="12" fill="white"/>
                    <rect x="88" y="68" width="184" height="114" rx="8" fill="#f0f9ff"/>

                    <!-- Screen content: product cards -->
                    <!-- Card 1 -->
                    <rect x="96" y="76" width="52" height="60" rx="6" fill="white" stroke="#e0f2fe" stroke-width="1"/>
                    <rect x="96" y="76" width="52" height="32" rx="6" fill="#bfdbfe"/>
                    <rect x="96" y="100" width="52" height="8" rx="3" fill="#bfdbfe"/>
                    <circle cx="122" cy="92" r="10" fill="#60a5fa" opacity="0.5"/>
                    <!-- Fish shape in card 1 -->
                    <ellipse cx="122" cy="92" rx="8" ry="5" fill="#2563eb" opacity="0.7"/>
                    <path d="M114 92 L110 89 L110 95 Z" fill="#1d4ed8" opacity="0.7"/>
                    <text x="102" y="150" font-family="sans-serif" font-size="6" fill="#6b7280" display="none">ikan</text>
                    <rect x="100" y="126" width="44" height="4" rx="2" fill="#e2e8f0"/>
                    <rect x="104" y="132" width="36" height="3" rx="1.5" fill="#93c5fd"/>

                    <!-- Card 2 -->
                    <rect x="156" y="76" width="52" height="60" rx="6" fill="white" stroke="#e0f2fe" stroke-width="1"/>
                    <rect x="156" y="76" width="52" height="32" rx="6" fill="#dcfce7"/>
                    <ellipse cx="182" cy="92" rx="8" ry="5" fill="#4ade80" opacity="0.8"/>
                    <path d="M174 92 L170 89 L170 95 Z" fill="#16a34a" opacity="0.7"/>
                    <rect x="160" y="126" width="44" height="4" rx="2" fill="#e2e8f0"/>
                    <rect x="164" y="132" width="36" height="3" rx="1.5" fill="#86efac"/>

                    <!-- Card 3 -->
                    <rect x="216" y="76" width="48" height="60" rx="6" fill="white" stroke="#e0f2fe" stroke-width="1"/>
                    <rect x="216" y="76" width="48" height="32" rx="6" fill="#fef3c7"/>
                    <ellipse cx="240" cy="92" rx="8" ry="5" fill="#fbbf24" opacity="0.8"/>
                    <path d="M232 92 L228 89 L228 95 Z" fill="#d97706" opacity="0.7"/>
                    <rect x="220" y="126" width="40" height="4" rx="2" fill="#e2e8f0"/>
                    <rect x="224" y="132" width="32" height="3" rx="1.5" fill="#fcd34d"/>

                    <!-- Bottom bar of screen -->
                    <rect x="88" y="157" width="184" height="18" rx="0" fill="#f8fafc"/>
                    <rect x="96" y="161" width="60" height="10" rx="5" fill="#2563eb"/>
                    <rect x="162" y="163" width="40" height="6" rx="3" fill="#e2e8f0"/>
                    <rect x="208" y="163" width="30" height="6" rx="3" fill="#e2e8f0"/>

                    <!-- Laptop chin -->
                    <rect x="76" y="188" width="208" height="10" rx="4" fill="#dbeafe"/>

                    <!-- Laptop stand -->
                    <path d="M155 198 L145 218 L215 218 L205 198 Z" fill="#e2e8f0"/>
                    <rect x="130" y="218" width="100" height="8" rx="4" fill="#cbd5e1"/>

                    <!-- Floating elements -->
                    <!-- Cart badge -->
                    <g transform="translate(288, 70)">
                        <circle cx="0" cy="0" r="22" fill="white" stroke="#bfdbfe" stroke-width="1.5" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.06))"/>
                        <path d="M-9 -5 L-7 -5 L-4 5 L7 5 L9 -3 L-5 -3" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="-3" cy="8" r="1.5" fill="#2563eb"/>
                        <circle cx="6" cy="8" r="1.5" fill="#2563eb"/>
                        <circle cx="8" cy="-7" r="6" fill="#ef4444"/>
                        <text x="8" y="-4.5" text-anchor="middle" font-family="sans-serif" font-size="7" font-weight="bold" fill="white">3</text>
                    </g>

                    <!-- Check badge -->
                    <g transform="translate(68, 100)">
                        <circle cx="0" cy="0" r="18" fill="white" stroke="#bbf7d0" stroke-width="1.5" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.06))"/>
                        <circle cx="0" cy="0" r="10" fill="#dcfce7"/>
                        <path d="M-5 0 L-1 4 L6 -4" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </g>

                    <!-- Star badge -->
                    <g transform="translate(300, 170)">
                        <circle cx="0" cy="0" r="18" fill="white" stroke="#fef9c3" stroke-width="1.5" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.06))"/>
                        <path d="M0 -8 L2 -2 L8 -2 L3 2 L5 8 L0 4 L-5 8 L-3 2 L-8 -2 L-2 -2 Z" fill="#fbbf24"/>
                    </g>

                    <!-- Dotted decoration -->
                    <circle cx="100" cy="40" r="3" fill="#bfdbfe" opacity="0.6"/>
                    <circle cx="115" cy="32" r="2" fill="#93c5fd" opacity="0.5"/>
                    <circle cx="250" cy="38" r="2.5" fill="#bfdbfe" opacity="0.6"/>
                    <circle cx="265" cy="28" r="2" fill="#93c5fd" opacity="0.4"/>
                </svg>
            </div>

            {{-- Text --}}
            <div class="left-text">
                <h2 class="left-heading">Bergabung Sekarang</h2>
                <p class="left-sub">Daftar gratis dan nikmati kemudahan berbelanja ikan hias pilihan.</p>
            </div>

            {{-- Steps --}}
            <div class="steps">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-info">
                        <p class="step-title">Isi data diri</p>
                        <p class="step-desc">Nama, email, dan password Anda</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-info">
                        <p class="step-title">Pilih & Pesan</p>
                        <p class="step-desc">Jelajahi katalog ikan hias kami</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-info">
                        <p class="step-title">Ambil di Toko</p>
                        <p class="step-desc">Bayar saat mengambil pesanan</p>
                    </div>
                </div>
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
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                    Daftar Gratis
                </div>
                <h1 class="form-title">Buat akun baru</h1>
                <p class="form-subtitle">Isi formulir di bawah ini untuk mulai berbelanja.</p>
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

            @if ($errors->any())
                <div class="alert alert-error">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- ===== FORM ===== --}}
            <form method="POST" action="{{ route('register.store') }}" id="registerForm" novalidate>
                @csrf

                {{-- Nama Depan + Nama Belakang --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="first_name" class="form-label">Nama Depan</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="form-input @error('first_name') is-invalid @enderror"
                                placeholder="Budi"
                                value="{{ old('first_name') }}"
                                autofocus
                                autocomplete="given-name"
                            />
                        </div>
                        @error('first_name')
                            <p class="field-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Nama Belakang</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="form-input @error('last_name') is-invalid @enderror"
                                placeholder="Santoso"
                                value="{{ old('last_name') }}"
                                autocomplete="family-name"
                            />
                        </div>
                        @error('last_name')
                            <p class="field-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            autocomplete="email"
                        />
                    </div>
                    @error('email')
                        <p class="field-error">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            oninput="checkStrength(this.value)"
                        />
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>

                    <div class="password-strength" id="strengthBox" style="display:none">
                        <div class="strength-bar">
                            <div class="strength-seg" id="seg1"></div>
                            <div class="strength-seg" id="seg2"></div>
                            <div class="strength-seg" id="seg3"></div>
                            <div class="strength-seg" id="seg4"></div>
                        </div>
                        <span class="strength-label" id="strengthLabel">Lemah</span>
                    </div>

                    @error('password')
                        <p class="field-error">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                        />
                        <button type="button" class="password-toggle" id="toggleConfirm" aria-label="Tampilkan konfirmasi password">
                            <svg id="eyeIconConfirm" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="terms-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required />
                        Saya menyetujui <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Syarat &amp; Ketentuan</a> dan <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">Kebijakan Privasi</a> Fasel Aquarium
                    </label>
                    @error('terms')
                        <p class="field-error" style="margin-top:8px">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-register" id="submitBtn">
                    <span class="btn-text">Buat Akun Sekarang</span>
                    <span class="btn-loading">
                        <span class="spinner"></span>
                        Mendaftarkan...
                    </span>
                </button>
            </form>

            <div class="divider">atau</div>

            <div class="form-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk sekarang</a>
            </div>

        </div>
    </div>

</div>

{{-- ===== SCRIPTS — UNCHANGED ===== --}}
<script>
    // Toggle password
    function makeToggle(btnId, inputId, iconId) {
        const btn   = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        btn.addEventListener('click', function () {
            const hidden = input.type === 'password';
            input.type   = hidden ? 'text' : 'password';
            icon.innerHTML = hidden
                ? '<line x1="1" y1="1" x2="23" y2="23"></line><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        });
    }
    makeToggle('togglePassword', 'password', 'eyeIcon');
    makeToggle('toggleConfirm', 'password_confirmation', 'eyeIconConfirm');

    // Password strength checker
    function checkStrength(val) {
        const box   = document.getElementById('strengthBox');
        const label = document.getElementById('strengthLabel');
        const segs  = [
            document.getElementById('seg1'),
            document.getElementById('seg2'),
            document.getElementById('seg3'),
            document.getElementById('seg4'),
        ];

        if (!val) { box.style.display = 'none'; return; }
        box.style.display = 'block';

        let score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;

        segs.forEach((s, i) => {
            s.className = 'strength-seg';
            if (i < score) {
                if (score <= 1)      s.classList.add('active-weak');
                else if (score <= 2) s.classList.add('active-medium');
                else                 s.classList.add('active-strong');
            }
        });

        if (score <= 1)      { label.textContent = 'Lemah';       label.className = 'strength-label weak'; }
        else if (score <= 2) { label.textContent = 'Sedang';      label.className = 'strength-label medium'; }
        else if (score <= 3) { label.textContent = 'Kuat';        label.className = 'strength-label strong'; }
        else                 { label.textContent = 'Sangat Kuat'; label.className = 'strength-label strong'; }
    }

    // Loading state
    const form      = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    form.addEventListener('submit', function () {
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;
    });
</script>

</body>
</html>