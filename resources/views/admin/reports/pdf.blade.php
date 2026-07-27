<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan {{ $periodLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* =========================================================
           HEADER
           ========================================================= */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 24px 32px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-brand h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-brand p {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 3px;
        }

        .header-meta {
            text-align: right;
        }

        .header-meta h2 {
            font-size: 13px;
            font-weight: 600;
            opacity: 0.9;
        }

        .header-meta p {
            font-size: 10px;
            opacity: 0.6;
            margin-top: 4px;
        }

        /* =========================================================
           BODY
           ========================================================= */
        .body { padding: 24px 32px; }

        /* =========================================================
           STATS CARDS
           ========================================================= */
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 24px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .stats-row { display: table-row; }

        .stat-card {
            display: table-cell;
            width: 25%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            border-left: 4px solid #3b82f6;
        }

        .stat-card.green  { border-left-color: #10b981; }
        .stat-card.amber  { border-left-color: #f59e0b; }
        .stat-card.violet { border-left-color: #8b5cf6; }

        .stat-label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        /* =========================================================
           SECTION TITLE
           ========================================================= */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #3b82f6;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #dbeafe;
        }

        /* =========================================================
           TABLE
           ========================================================= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            background: #1e40af;
            color: white;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) { background: #f8fafc; }

        tbody tr:hover { background: #eff6ff; }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
            color: #334155;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-paid      { background: #dbeafe; color: #1d4ed8; }
        .badge-completed { background: #d1fae5; color: #065f46; }

        /* =========================================================
           TOP PRODUCTS
           ========================================================= */
        .top-products {
            margin-bottom: 24px;
        }

        .product-row {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 6px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .product-rank {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            font-size: 10px;
            font-weight: 700;
            text-align: center;
            line-height: 24px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .product-name {
            flex: 1;
            font-size: 10px;
            font-weight: 600;
            color: #1e293b;
        }

        .product-qty {
            font-size: 10px;
            color: #64748b;
            margin-right: 16px;
        }

        .product-revenue {
            font-size: 10px;
            font-weight: 700;
            color: #1e40af;
        }

        /* =========================================================
           TOTAL ROW
           ========================================================= */
        .total-row {
            background: #1e40af;
            color: white;
        }

        .total-row td {
            color: white;
            font-weight: 700;
            padding: 12px;
        }

        /* =========================================================
           FOOTER
           ========================================================= */
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-left {
            font-size: 9px;
            color: #94a3b8;
        }

        .footer-right {
            text-align: right;
        }

        .footer-right .signature-label {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 32px;
        }

        .footer-right .signature-name {
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
            border-top: 1px solid #334155;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- ============================================================
         HEADER
         ============================================================ --}}
    <div class="header">
        <div class="header-brand">
            <h1>Toko Online</h1>
            <p>Platform E-Commerce Terpercaya</p>
        </div>
        <div class="header-meta">
            <h2>Laporan Penjualan</h2>
            <p>Periode: {{ $periodLabel }}</p>
            <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <div class="body">

        {{-- ============================================================
             STATISTIK RINGKASAN
             ============================================================ --}}
        <div style="margin-bottom: 8px;">
            <p class="section-title">Ringkasan Statistik</p>
        </div>
        <table class="stats-grid">
            <tr class="stats-row">
                <td class="stat-card">
                    <div class="stat-label">Total Penjualan</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </td>
                <td class="stat-card violet">
                    <div class="stat-label">Total Order</div>
                    <div class="stat-value">{{ number_format($totalOrders) }} pesanan</div>
                </td>
                <td class="stat-card amber">
                    <div class="stat-label">Produk Terjual</div>
                    <div class="stat-value">{{ number_format($totalItemsSold) }} item</div>
                </td>
                <td class="stat-card green">
                    <div class="stat-label">Rata-rata Transaksi</div>
                    <div class="stat-value">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        {{-- ============================================================
             TOP PRODUK
             ============================================================ --}}
        @if($topProducts->count() > 0)
        <div class="top-products">
            <p class="section-title">Produk Terlaris</p>
            @foreach($topProducts as $idx => $product)
            <div class="product-row">
                <div class="product-rank" style="background: {{ ['#3b82f6','#8b5cf6','#f59e0b','#10b981','#ef4444'][$idx] ?? '#3b82f6' }}">
                    {{ $idx + 1 }}
                </div>
                <div class="product-name">{{ $product['name'] }}</div>
                <div class="product-qty">{{ $product['quantity'] }} terjual</div>
                <div class="product-revenue">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ============================================================
             TABEL TRANSAKSI
             ============================================================ --}}
        <p class="section-title">Rincian Transaksi</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 4%">No</th>
                    <th style="width: 18%">Order Number</th>
                    <th style="width: 18%">Customer</th>
                    <th style="width: 14%">Tanggal</th>
                    <th style="width: 16%; text-align: right">Total</th>
                    <th style="width: 12%; text-align: center">Status</th>
                    <th style="width: 12%; text-align: center">Pembayaran</th>
                    <th style="width: 6%; text-align: center">Item</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $i => $order)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600; color: #1d4ed8; font-family: monospace; font-size: 9px;">
                        {{ $order->order_number }}
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $order->user->name ?? '-' }}</div>
                        <div style="color: #94a3b8; font-size: 9px;">{{ $order->user->email ?? '-' }}</div>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td style="text-align: right; font-weight: 700;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $order->status === 'completed' ? 'badge-completed' : 'badge-paid' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="text-align: center; text-transform: uppercase; font-weight: 600; font-size: 9px;">
                        {{ $order->payment_type ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $order->items->sum('quantity') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Tidak ada data transaksi
                    </td>
                </tr>
                @endforelse

                {{-- Total Row --}}
                @if($orders->count() > 0)
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; font-size: 11px;">TOTAL PENDAPATAN</td>
                    <td style="text-align: right; font-size: 12px;">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </td>
                    <td colspan="3"></td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- ============================================================
             METODE PEMBAYARAN
             ============================================================ --}}
        @if($paymentStats->count() > 0)
        <p class="section-title">Statistik Metode Pembayaran</p>
        <table>
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th style="text-align: center">Jumlah Transaksi</th>
                    <th style="text-align: right">Total Pendapatan</th>
                    <th style="text-align: center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php $totalCount = $paymentStats->sum('count'); @endphp
                @foreach($paymentStats as $stat)
                <tr>
                    <td style="font-weight: 600;">{{ $stat['type'] }}</td>
                    <td style="text-align: center;">{{ $stat['count'] }} transaksi</td>
                    <td style="text-align: right; font-weight: 700;">
                        Rp {{ number_format($stat['revenue'], 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        {{ $totalCount > 0 ? number_format(($stat['count'] / $totalCount) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- ============================================================
             FOOTER
             ============================================================ --}}
        <div class="footer">
            <div class="footer-left">
                <p>Dokumen ini digenerate secara otomatis oleh sistem.</p>
                <p>Dicetak pada: {{ now()->format('d M Y, H:i:s') }} WIB</p>
                <p>Halaman 1 dari 1</p>
            </div>
            <div class="footer-right">
                <div class="signature-label">Mengetahui,<br>Administrator</div>
                <div class="signature-name">Admin Toko Online</div>
            </div>
        </div>

    </div>
</body>
</html>
