<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#333;
            line-height:1.5;
        }

        .header{
            width:100%;
            margin-bottom:20px;
        }

        .logo{
            float:left;
            width:70px;
        }

        .company{
            margin-left:90px;
        }

        .company h2{
            margin:0;
            color:#2563eb;
            font-size:22px;
        }

        .company p{
            margin:2px 0;
            color:#666;
            font-size:11px;
        }

        .invoice-title{
            text-align:right;
        }

        .invoice-title h1{
            margin:0;
            font-size:24px;
            color:#111827;
        }

        .clearfix{
            clear:both;
        }

        .section{
            margin-top:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#2563eb;
            color:#fff;
            padding:10px;
            font-size:11px;
            text-align:left;
        }

        td{
            padding:10px;
            border-bottom:1px solid #ddd;
        }

        .text-right{
            text-align:right;
        }

        .summary{
            width:40%;
            float:right;
            margin-top:15px;
        }

        .summary td{
            border:none;
            padding:6px 0;
        }

        .total{
            font-size:15px;
            font-weight:bold;
            color:#2563eb;
            border-top:2px solid #2563eb;
        }

        .badge{
            display:inline-block;
            padding:4px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:bold;
            background:#dcfce7;
            color:#166534;
        }

        .footer{
            position:fixed;
            bottom:0;
            width:100%;
            text-align:center;
            font-size:11px;
            color:#888;
            border-top:1px solid #ddd;
            padding-top:8px;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<div class="header">

    <div class="logo">
    @if(!empty($logo))
        <img
            src="{{ $logo }}"
            alt="{{ $settings?->app_name ?? 'Fasel Aquarium' }}"
            style="width: 70px; height: 70px; object-fit: contain;"
        >
    @else
        <div style="width:70px; height:70px; background:#2563eb; color:#ffffff;
                    text-align:center; line-height:70px; font-weight:bold; font-size:22px;">
            FA
        </div>
    @endif
</div>

    <div class="company">
        <h2>Fasel Aquarium</h2>

        <p>Sistem Jual Beli Ikan Hias</p>
        <p>Email : faselaquarium@gmail.com</p>
        <p>Telepon : 083131871300</p>
    </div>

    <div class="invoice-title">
        <h1>INVOICE</h1>
    </div>

</div>

<div class="clearfix"></div>

{{-- ================= INFORMASI ================= --}}

<table>
    <tr>
        <td width="55%">
            <strong>Nama Pelanggan</strong><br>
            {{ $order->user->name }}
        </td>

                <td width="45%">
            <strong>Nomor Pesanan</strong><br>
            {{ $order->order_number }}<br><br>

            <strong>Tanggal</strong><br>
            {{ $order->created_at->format('d M Y, H:i') }}
        </td>
    </tr>
</table>

{{-- ================= DAFTAR PRODUK ================= --}}

<div class="section">

    <table>
        <thead>
            <tr>
                <th width="45%">Produk</th>
                <th width="15%" class="text-right">Jumlah</th>
                <th width="20%" class="text-right">Harga</th>
                <th width="20%" class="text-right">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                    </td>

                    <td class="text-right">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-right">
                        {{ $item->formatted_price }}
                    </td>

                    <td class="text-right">
                        {{ $item->formatted_subtotal }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- ================= RINGKASAN ================= --}}

<table class="summary">

    <tr>
        <td><strong>Metode Pembayaran</strong></td>
        <td class="text-right">
            @if($order->payment_method === 'cash')
                Bayar di Tempat
            @else
                {{ strtoupper(str_replace('_', ' ', $order->payment_type ?? $order->payment_method)) }}
            @endif
        </td>
    </tr>

    <tr>
        <td><strong>Status Pembayaran</strong></td>
        <td class="text-right">
            {{ $order->status_label }}
        </td>
    </tr>

    @if($order->transaction_id)
    <tr>
        <td><strong>ID Transaksi</strong></td>
        <td class="text-right">
            {{ $order->transaction_id }}
        </td>
    </tr>
    @endif

    <tr class="total">
        <td>Total</td>
        <td class="text-right">
            {{ $order->formatted_total_price }}
        </td>
    </tr>

</table>

<div class="clearfix"></div>

{{-- ================= CATATAN ================= --}}

<div class="section">
    <strong>Catatan</strong>

    <p style="margin-top:6px;color:#666;">
        Terima kasih telah berbelanja di <strong>Fasel Aquarium</strong>.
        Simpan invoice ini sebagai bukti transaksi.
        @if($order->payment_method === 'cash')
            Pembayaran dilakukan saat pengambilan pesanan.
        @else
            Pembayaran telah diterima dan pesanan sedang diproses.
        @endif
    </p>
</div>

{{-- ================= FOOTER ================= --}}

<div class="footer">
    Invoice ini dibuat secara otomatis oleh Sistem Jual Beli Ikan Hias
    <strong>Fasel Aquarium</strong>.<br>
    © {{ date('Y') }} Fasel Aquarium. All Rights Reserved.
</div>

</body>
</html>