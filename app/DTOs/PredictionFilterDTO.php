<?php

namespace App\DTOs;

use Carbon\Carbon;

/**
 * =====================================================================
 * PredictionFilterDTO
 * =====================================================================
 * 
 * DTO untuk filter prediksi dengan dynamic weighting.
 * 
 * Key Features:
 * - Dynamic weights berdasarkan window size
 * - Auto-generate ascending weights jika tidak ada input
 * - Validasi bobot otomatis
 * - Type-safe array handling
 */
class PredictionFilterDTO
{
    /**
     * @param string $period Periode (daily, weekly, monthly)
     * @param Carbon $startDate Tanggal mulai
     * @param Carbon $endDate Tanggal selesai
     * @param int $window Ukuran window (2-12)
     * @param array<int, int> $weights Array bobot (dinamis sesuai window)
     */
    public function __construct(
        public readonly string $period,
        public readonly Carbon $startDate,
        public readonly Carbon $endDate,
        public readonly int    $window,
        public readonly array  $weights, // DYNAMIC: auto-generated or user-provided
    ) {
        // Safety check
        $this->validateWeights();
    }

    /**
     * =====================================================================
     * Factory Method: fromRequest
     * =====================================================================
     * 
     * Parse request data dan generate dynamic weights.
     * 
     * @param array<string, mixed> $data Request data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        $period    = $data['period'] ?? 'monthly';
        $startDate = Carbon::parse($data['start_date'] ?? now()->subMonths(6)->startOfMonth());
        $endDate   = Carbon::parse($data['end_date'] ?? now()->endOfMonth());
        $window    = (int) ($data['window'] ?? 3);
        $window    = max(2, min($window, 12)); // Clamp: 2-12

        // STEP 1: Parse weights dari request
        $weights = self::parseWeights(
            $data['weights'] ?? null,
            $window
        );

        return new self(
            period:    $period,
            startDate: $startDate,
            endDate:   $endDate,
            window:    $window,
            weights:   $weights,
        );
    }

    /**
     * =====================================================================
     * Parse Weights
     * =====================================================================
     * 
     * Parse weights dari berbagai format input:
     * - null → auto-generate ascending [1,2,3,...,n]
     * - "1,2,3" → parse comma-separated string
     * - [1,2,3] → gunakan array langsung
     * 
     * @param mixed $input Raw weight input dari request
     * @param int $window Target window size
     * @return array<int, int> Validated weights array
     */
    private static function parseWeights(mixed $input, int $window): array
    {
        // CASE 1: Tidak ada input → auto-generate ascending
        if ($input === null || $input === '' || $input === []) {
            return self::generateAscendingWeights($window);
        }

        $weights = [];

        // CASE 2: Input adalah string (CSV)
        if (is_string($input)) {
            $parsed = array_map(
                fn($val) => (int) trim($val),
                explode(',', $input)
            );
            $weights = array_values($parsed);
        }
        // CASE 3: Input adalah array
        elseif (is_array($input)) {
            $weights = array_map(
                fn($val) => (int) $val,
                array_values($input)
            );
        }

        // Validasi jumlah bobot
        if (count($weights) !== $window) {
            // Auto-normalize: generate sesuai window jika tidak match
            return self::generateAscendingWeights($window);
        }

        // Validasi setiap bobot harus >= 1
        foreach ($weights as $w) {
            if ($w < 1) {
                return self::generateAscendingWeights($window);
            }
        }

        return $weights;
    }

    /**
     * =====================================================================
     * Generate Ascending Weights
     * =====================================================================
     * 
     * Generate bobot ascending: [1, 2, 3, ..., n]
     * Ini adalah DEFAULT behavior yang paling logic:
     * - Data terlama mendapat bobot kecil
     * - Data terbaru mendapat bobot besar
     * - Natural progression
     * 
     * @param int $window Jumlah bobot yang diinginkan
     * @return array<int, int> [1, 2, 3, ..., n]
     */
    private static function generateAscendingWeights(int $window): array
    {
        return array_map(fn($i) => $i + 1, range(0, $window - 1));
    }

    /**
     * =====================================================================
     * Validate Weights
     * =====================================================================
     * 
     * Safety check untuk memastikan weights valid.
     * Dijalankan di constructor.
     * 
     * @return void
     * @throws \LogicException Jika weights invalid
     */
    private function validateWeights(): void
    {
        // Check 1: Jumlah weights harus = window
        if (count($this->weights) !== $this->window) {
            throw new \LogicException(
                "Weights count (" . count($this->weights) . ") must equal window ({$this->window})"
            );
        }

        // Check 2: Semua bobot harus > 0
        foreach ($this->weights as $i => $weight) {
            if ($weight < 1) {
                throw new \LogicException(
                    "Weight at index {$i} must be >= 1, got {$weight}"
                );
            }
        }

        // Check 3: Weights harus integer
        foreach ($this->weights as $weight) {
            if (!is_int($weight)) {
                throw new \LogicException(
                    "All weights must be integers"
                );
            }
        }
    }

    /**
     * =====================================================================
     * Helper: Get Sum of Weights
     * =====================================================================
     * 
     * @return int
     */
    public function getWeightSum(): int
    {
        return array_sum($this->weights);
    }

    /**
     * =====================================================================
     * Helper: Get Weights as String
     * =====================================================================
     * 
     * Format weights untuk display di frontend.
     * 
     * @return string "1,2,3,4,5"
     */
    public function getWeightsAsString(): string
    {
        return implode(',', $this->weights);
    }
}
