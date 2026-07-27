<?php
// app/Helpers/FormatHelper.php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format angka ke format Rupiah.
     * Contoh: 150000 → Rp 150.000
     */
    public static function rupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format Rupiah dengan suffix ribu/juta.
     * Contoh: 1500000 → Rp 1,5 Jt
     */
    public static function rupiahShort(int|float $amount): string
    {
        if ($amount >= 1_000_000) {
            return 'Rp ' . number_format($amount / 1_000_000, 1, ',', '.') . ' Jt';
        }

        if ($amount >= 1_000) {
            return 'Rp ' . number_format($amount / 1_000, 0, ',', '.') . ' Rb';
        }

        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
