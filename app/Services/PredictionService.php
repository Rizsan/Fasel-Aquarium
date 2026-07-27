<?php

namespace App\Services;

use App\DTOs\PredictionFilterDTO;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod;

/**
 * =====================================================================
 * PredictionService
 * =====================================================================
 * 
 * Service untuk prediction logic dengan dynamic WMA.
 * 
 * Key Changes:
 * - calculateWMA() sekarang dinamis (tidak hardcoded weight_1,2,3)
 * - Aman dari undefined array key
 * - Tidak ada division by zero
 * - Robust untuk window apapun (2-12)
 */
class PredictionService
{
    /**
     * =====================================================================
     * MAIN: Get Prediction Data
     * =====================================================================
     */
    public function getPredictionData(PredictionFilterDTO $filter): array
    {
        logger()->info('Filter', [
    'period' => $filter->period,
    'start'  => $filter->startDate->toDateString(),
    'end'    => $filter->endDate->toDateString(),
    'window' => $filter->window,
    'weights'=> $filter->weights,
]);
        // 1. Fetch revenue data per period
        $revenueData = $this->fetchRevenueByPeriod($filter);

        // 2. Calculate SMA (unchanged)
        $smaData = $this->calculateSMA($revenueData, $filter->window);
        logger()->info('SMA', $smaData->toArray());

        // 3. Calculate WMA dengan dynamic weights
        $wmaData = $this->calculateWMA($revenueData, $filter->window, $filter->weights);
        logger()->info('WMA', $wmaData->toArray());
        // 4. Build table (unchanged)
        $tableData = $this->buildTableData($revenueData, $smaData, $wmaData);
        logger()->info('Table Data', [
    'count' => $tableData->count(),
    'rows'  => $tableData->toArray(),
]);
        // 5. Build summary (updated untuk include weights info)
        $summary = $this->buildSummary($revenueData, $smaData, $wmaData, $filter);

        // 6. Get predicted products dengan dynamic weights
        $products = $this->getPredictedProducts($filter, $wmaData);
        logger()->info('Products', [
    'count' => $products->count(),
    'rows'  => $products->toArray(),
]);
        // 7. Build chart (unchanged)
        $chartData = $this->buildChartData($tableData);
        logger()->info('Chart Data', $chartData);
        return [
            'table'    => $tableData,
            'summary'  => $summary,
            'products' => $products,
            'chart'    => $chartData,
        ];
    }

    /**
     * =====================================================================
     * FETCH REVENUE BY PERIOD
     * =====================================================================
     */
    private function fetchRevenueByPeriod(PredictionFilterDTO $filter): Collection
    {
        $groupFormat = $this->getGroupFormat($filter->period);
        $dateFormat = $this->getDateFormat($filter->period);

        $rows = Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereBetween('created_at', [
                $filter->startDate->startOfDay(),
                $filter->endDate->endOfDay(),
            ])
            ->selectRaw("
                DATE_FORMAT(created_at, '{$groupFormat}') AS period_key,
                SUM(total_price) AS total_revenue,
                COUNT(*) AS order_count
            ")
            ->groupByRaw("DATE_FORMAT(created_at, '{$groupFormat}')")
            ->orderByRaw("DATE_FORMAT(created_at, '{$groupFormat}')")
            ->get();
            logger()->info('Revenue Query Result', [
    'count' => $rows->count(),
    'rows'  => $rows->toArray(),
]);

        return $this->fillMissingPeriods($rows, $filter, $dateFormat);
    }

    /**
     * =====================================================================
     * FILL MISSING PERIODS
     * =====================================================================
     */
    private function fillMissingPeriods(
        Collection $rows,
        PredictionFilterDTO $filter,
        string $dateFormat
    ): Collection {
        $existing = $rows->keyBy('period_key');
        $filled = collect();

        if ($filter->period === 'daily') {
            $period = CarbonPeriod::create($filter->startDate, '1 day', $filter->endDate);
        } elseif ($filter->period === 'weekly') {
            $period = CarbonPeriod::create($filter->startDate, '1 week', $filter->endDate);
        } else {
            $period = CarbonPeriod::create(
                $filter->startDate->copy()->startOfMonth(),
                '1 month',
                $filter->endDate->copy()->startOfMonth()
            );
        }

        foreach ($period as $date) {
            $key = $date->format($this->carbonFormat($filter->period));
            logger()->info([
    'date' => $date->toDateString(),
    'generated_key' => $key,
    'exists' => $existing->has($key),
]);

            if ($existing->has($key)) {
                $row = $existing->get($key);
                $filled->push([
                    'period_key'    => $key,
                    'label' => $filter->period === 'weekly'
    ? $date->translatedFormat('F') . ' Minggu ' . ceil($date->day / 7)
    : $date->translatedFormat($dateFormat),
                    'total_revenue' => (float) $row->total_revenue,
                    'order_count'   => (int) $row->order_count,
                ]);
            } else {
                $filled->push([
                    'period_key'    => $key,
                    'label' => $filter->period === 'weekly'
    ? $date->translatedFormat('F') . ' Minggu ' . ceil($date->day / 7)
    : $date->translatedFormat($dateFormat),
                    'total_revenue' => 0.0,
                    'order_count'   => 0,
                ]);
            }
        }
logger()->info('Filled Periods', [
    'count' => $filled->count(),
    'data'  => $filled->toArray(),
]);
        return $filled;
    }

    /**
     * =====================================================================
     * SIMPLE MOVING AVERAGE (SMA)
     * =====================================================================
     * 
     * Formula: SMA = Σ(data) / window
     * 
     * Unchanged dari sebelumnya.
     * Safe: returns null jika data < window.
     */
    public function calculateSMA(Collection $data, int $window): Collection
    {
        $values = $data->pluck('total_revenue')->values();
        $result = collect();

        foreach ($values as $i => $value) {
            if ($i < $window - 1) {
                $result->push(null);
                continue;
            }

            $slice = $values->slice($i - $window + 1, $window);
            $avg = $slice->avg();
            $result->push(round($avg, 2));
        }

        return $result;
    }

    /**
     * =====================================================================
     * WEIGHTED MOVING AVERAGE (WMA) — DYNAMIC
     * =====================================================================
     * 
     * Formula: WMA = Σ(data × bobot) / Σ(bobot)
     * 
     * CHANGES:
     * ✅ Dynamic weights (bukan hardcoded [1,2,3])
     * ✅ Weights array selalu match dengan window size
     * ✅ Safe dari undefined array key
     * ✅ Safe dari division by zero
     * ✅ Type-safe dengan strict checks
     * 
     * @param Collection $data Revenue data collection
     * @param int $window Window size (2-12)
     * @param array<int, int> $weights Dynamic weights array (count = window)
     * @return Collection WMA values (null jika insufficient data)
     */
    public function calculateWMA(
        Collection $data,
        int $window,
        array $weights
    ): Collection {
        // Safety: weights count must equal window
        if (count($weights) !== $window) {
            throw new \LogicException(
                "Weights count (" . count($weights) . ") must equal window ({$window})"
            );
        }

        $values = $data->pluck('total_revenue')->values();
        $result = collect();
        $weightSum = array_sum($weights);

        // Safety: weights sum must be > 0
        if ($weightSum <= 0) {
            throw new \LogicException("Sum of weights must be > 0");
        }

        foreach ($values as $i => $value) {
            // Insufficient data untuk window ini
            if ($i < $window - 1) {
                $result->push(null);
                continue;
            }

            // SLICE data sesuai window size
            $slice = $values->slice($i - $window + 1, $window);

            // Calculate weighted sum
            $weighted = 0.0;
            $sliceIndex = 0;

            foreach ($slice as $sliceValue) {
                // ✅ SAFE: sliceIndex selalu < count($weights) karena:
                //    - slice count = window (selalu dibuat dengan $window)
                //    - weights count = window (sudah di-validate)
                //    - loop runs exactly window times
                $weighted += (float) $sliceValue * $weights[$sliceIndex];
                $sliceIndex++;
            }

            // Calculate WMA
            $wma = $weighted / $weightSum;
            $result->push(round($wma, 2));
        }

        return $result;
    }

    /**
     * =====================================================================
     * BUILD TABLE DATA
     * =====================================================================
     */
    private function buildTableData(
    Collection $revenue,
    Collection $sma,
    Collection $wma
): Collection {
    return $revenue->values()->map(function ($row, $i) use ($sma, $wma, $revenue) {

        $smaVal = $sma->get($i);
        $wmaVal = $wma->get($i);

        /**
         * ============================================================
         * FIX PREDICTION SHIFT
         * ============================================================
         *
         * WMA pada periode sekarang digunakan untuk
         * memprediksi periode berikutnya.
         *
         * Jadi:
         * - Mei WMA 240k -> prediksi Juni
         * - Juni WMA 120k -> prediksi Juli
         */

        $prediction = null;

        // Ambil WMA dari periode sebelumnya
        if ($i > 0) {
            $prediction = $wma->get($i - 1);
        }

        return [
            'label'      => $row['label'],
            'actual'     => $row['total_revenue'],
            'orders'     => $row['order_count'],
            'sma'        => $smaVal,
            'wma'        => $wmaVal,
            'prediction' => $prediction,
        ];
    });
}

    /**
     * =====================================================================
     * BUILD SUMMARY
     * =====================================================================
     * 
     * Updated untuk include weights info.
     */
    private function buildSummary(
        Collection $revenue,
        Collection $sma,
        Collection $wma,
        PredictionFilterDTO $filter
    ): array {
        $totalRevenue = $revenue->sum('total_revenue');

        $avgRevenue = $revenue
            ->where('total_revenue', '>', 0)
            ->avg('total_revenue') ?? 0;

        $lastWma = $wma
            ->filter(fn($v) => $v !== null)
            ->last() ?? 0;

        return [
            'total_revenue'      => $totalRevenue,
            'avg_revenue'        => round($avgRevenue, 2),
            'next_prediction'    => round($lastWma, 2),
            'data_points'        => $revenue->count(),
            'prediction_method'  => 'Weighted Moving Average (WMA)',
            'window_size'        => $filter->window,
            'weights'            => $filter->weights, // Include weights untuk info
            'weights_display'    => $filter->getWeightsAsString(),
        ];
    }

    /**
     * =====================================================================
     * GET PREDICTED PRODUCTS (DYNAMIC WEIGHTS)
     * =====================================================================
     * 
     * Predict produk yang kemungkinan laku di masa depan.
     * 
     * CHANGES:
     * ✅ Menggunakan dynamic weights dari filter
     * ✅ Safe dari undefined array key (sudah di-validate di WMA)
     * ✅ Proper handling insufficient data
     */
    private function getPredictedProducts(
        PredictionFilterDTO $filter,
        Collection $wmaRevenueData
    ): Collection {
        $groupFormat = $this->getGroupFormat($filter->period);

        // 1. Fetch product history
        $productHistory = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereBetween('orders.created_at', [
                $filter->startDate->startOfDay(),
                $filter->endDate->endOfDay(),
            ])
            ->selectRaw("
                products.id,
                products.name as product_name,
                DATE_FORMAT(orders.created_at, '{$groupFormat}') AS period_key,
                SUM(order_items.quantity) AS qty_per_period
            ")
            ->groupByRaw("
                products.id,
                products.name,
                DATE_FORMAT(orders.created_at, '{$groupFormat}')
            ")
            ->orderBy('products.id')
            ->orderByRaw("DATE_FORMAT(orders.created_at, '{$groupFormat}')")
            ->get();

        if ($productHistory->isEmpty()) {
            return collect();
        }

        // 2. Group by product & calculate WMA per product dengan dynamic weights
        $products = $productHistory
            ->groupBy('product_name')
            ->map(function ($items) use ($filter) {
                $quantities = $items->pluck('qty_per_period')->values();

                // Edge case: insufficient data
                if ($quantities->isEmpty()) {
                    return null;
                }

                // ✅ SAFE: calculateWMA sekarang robust dengan dynamic weights
                try {
                    $qtyData = collect($items->map(fn($item) => [
                        'total_revenue' => $item->qty_per_period,
                    ])->values());

                    // Gunakan window minimal jika data < filter->window
                    $effectiveWindow = min($filter->window, $quantities->count());

                    // Generate weights untuk effective window
                    $effectiveWeights = array_slice($filter->weights, 0, $effectiveWindow);
                    if (count($effectiveWeights) < $effectiveWindow) {
                        // Auto-generate jika perlu
                        $effectiveWeights = range(1, $effectiveWindow);
                    }

                    $wmaQty = $this->calculateWMA($qtyData, $effectiveWindow, $effectiveWeights);

                    $lastWma = $wmaQty
                        ->filter(fn($v) => $v !== null)
                        ->last() ?? 0;

                    $totalQty = $quantities->sum();

                    return [
                        'product_name'  => $items->first()->product_name,
                        'total_qty'     => (int) $totalQty,
                        'wma'           => round($lastWma, 2),
                        'predicted_qty' => (int) ceil($lastWma),
                    ];
                } catch (\Exception $e) {
                    // Log error tapi jangan stop process
                    return null;
                }
            })
            ->filter(fn($item) => $item !== null) // Remove failed items
            ->sortByDesc('wma')
            ->values();

        if ($products->isEmpty()) {
            return collect();
        }

        return $products
    ->take(10)
    ->values()
    ->map(function ($item, $index) {

        $rank = $index + 1;

        if ($rank <= 3) {
            $badge = 'high_potential';
        } elseif ($rank <= 7) {
            $badge = 'stable';
        } else {
            $badge = 'low';
        }

        return [
            'rank'          => $rank,
            'product_name'  => $item['product_name'],
            'total_qty'     => $item['total_qty'],
            'wma'           => $item['wma'],
            'predicted_qty' => $item['predicted_qty'],
            'badge'         => $badge,
        ];
    });
    }

    /**
     * =====================================================================
     * BUILD CHART DATA
     * =====================================================================
     */
    private function buildChartData(Collection $tableData): array
    {
        return [
            'labels'     => $tableData->pluck('label')->toArray(),
            'actual'     => $tableData->pluck('actual')->toArray(),
            'sma'        => $tableData->pluck('sma')->toArray(),
            'wma'        => $tableData->pluck('wma')->toArray(),
            'prediction' => $tableData->pluck('prediction')->toArray(),
        ];
    }

    /**
     * =====================================================================
     * FORMAT HELPERS
     * =====================================================================
     */
    private function getGroupFormat(string $period): string
    {
        return match ($period) {
            'daily'  => '%Y-%m-%d',
            'weekly' => '%x-W%v',
            default  => '%Y-%m',
        };
    }

    private function getDateFormat(string $period): string
    {
        return match ($period) {
            'daily'  => 'd M Y',
            'weekly' => '\M\i\n\g\g\u W Y',
            default  => 'F Y',
        };
    }

    private function carbonFormat(string $period): string
    {
        return match ($period) {
            'daily'  => 'Y-m-d',
            'weekly' => 'o-\WW',
            default  => 'Y-m',
        };
    }
}
