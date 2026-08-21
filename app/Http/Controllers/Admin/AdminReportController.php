<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportController extends Controller
{
    // =========================================================
    // Constants
    // =========================================================
    private const PAID_STATUSES = ['paid', 'completed'];

    // =========================================================
    // index() — Halaman utama laporan
    // =========================================================
    public function index(Request $request)
    {
        // --- Validasi & Ambil Parameter Filter ---
        $validated = $request->validate([
            'month'      => 'nullable|integer|between:1,12',
            'year'       => 'nullable|integer|min:2020|max:2030',
            'date_start' => 'nullable|date',
            'date_end'   => 'nullable|date|after_or_equal:date_start',
        ]);

        $month     = $validated['month']      ?? Carbon::now()->month;
        $year      = $validated['year']       ?? Carbon::now()->year;
        $dateStart = $validated['date_start'] ?? null;
        $dateEnd   = $validated['date_end']   ?? null;

        // --- Build Query Utama ---
        $query = Order::query()
            ->with(['user', 'items.product'])
            ->whereIn('status', self::PAID_STATUSES);

        // --- Terapkan Filter ---
        if ($dateStart && $dateEnd) {
            // Mode: Date Range
            $query->whereBetween('created_at', [
                Carbon::parse($dateStart)->startOfDay(),
                Carbon::parse($dateEnd)->endOfDay(),
            ]);
        } else {
            // Mode: Bulan & Tahun
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        // --- Ambil Data Terfilter (Semua, untuk chart & summary) ---
        $allOrders = $query->clone()->get();

        // --- Statistik Ringkasan ---
        $totalRevenue       = $allOrders->sum('total_price');
        $totalOrders        = $allOrders->count();
        $totalItemsSold     = $allOrders->sum(fn($o) => $o->items->sum('quantity'));
        $averageTransaction = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        // --- Growth (vs periode sebelumnya) ---
        $previousRevenue = $this->getPreviousRevenue($month, $year, $dateStart, $dateEnd);
        $growthPercent   = $previousRevenue > 0
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
            : ($totalRevenue > 0 ? 100 : 0);

        // --- Chart Data ---
        $chartData = $this->buildChartData($allOrders, $month, $year, $dateStart, $dateEnd);

        // --- Produk Terlaris Berdasarkan Jumlah Terjual ---
        $topProducts = $this->getTopProducts($allOrders);

        // --- Payment Method Stats ---
        $paymentStats = $this->getPaymentStats($allOrders);

        // --- Paginated Table ---
        $orders = $query->clone()
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        // --- Tahun tersedia untuk dropdown ---
        $availableYears = $this->getAvailableYears();

        return view('admin.reports.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'averageTransaction',
            'growthPercent',
            'previousRevenue',
            'chartData',
            'topProducts',
            'paymentStats',
            'month',
            'year',
            'dateStart',
            'dateEnd',
            'availableYears',
        ));
    }

    // =========================================================
    // exportPdf() — Download PDF
    // =========================================================
    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'month'      => 'nullable|integer|between:1,12',
            'year'       => 'nullable|integer|min:2020|max:2030',
            'date_start' => 'nullable|date',
            'date_end'   => 'nullable|date|after_or_equal:date_start',
        ]);

        $month     = $validated['month']      ?? Carbon::now()->month;
        $year      = $validated['year']       ?? Carbon::now()->year;
        $dateStart = $validated['date_start'] ?? null;
        $dateEnd   = $validated['date_end']   ?? null;

        $query = Order::query()
            ->with(['user', 'items.product'])
            ->whereIn('status', self::PAID_STATUSES);

        if ($dateStart && $dateEnd) {
            $query->whereBetween('created_at', [
                Carbon::parse($dateStart)->startOfDay(),
                Carbon::parse($dateEnd)->endOfDay(),
            ]);
        } else {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        $orders             = $query->orderByDesc('created_at')->get();
        $totalRevenue       = $orders->sum('total_price');
        $totalOrders        = $orders->count();
        $totalItemsSold     = $orders->sum(fn($o) => $o->items->sum('quantity'));
        $averageTransaction = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $topProducts        = $this->getTopProducts($orders);
        $paymentStats       = $this->getPaymentStats($orders);

        $periodLabel = $dateStart && $dateEnd
            ? Carbon::parse($dateStart)->format('d M Y') . ' - ' . Carbon::parse($dateEnd)->format('d M Y')
            : Carbon::create($year, $month)->translatedFormat('F Y');

        $filename = 'laporan-penjualan-' . strtolower(
            $dateStart && $dateEnd
                ? Carbon::parse($dateStart)->format('d-m-Y') . '-sampai-' . Carbon::parse($dateEnd)->format('d-m-Y')
                : Carbon::create($year, $month)->format('F-Y')
        ) . '.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'averageTransaction',
            'topProducts',
            'paymentStats',
            'periodLabel',
            'month',
            'year',
            'dateStart',
            'dateEnd',
        ))->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    // =========================================================
    // exportExcel() — Download Excel
    // =========================================================
    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'month'      => 'nullable|integer|between:1,12',
            'year'       => 'nullable|integer|min:2020|max:2030',
            'date_start' => 'nullable|date',
            'date_end'   => 'nullable|date|after_or_equal:date_start',
        ]);

        $month     = $validated['month']      ?? Carbon::now()->month;
        $year      = $validated['year']       ?? Carbon::now()->year;
        $dateStart = $validated['date_start'] ?? null;
        $dateEnd   = $validated['date_end']   ?? null;

        $query = Order::query()
            ->with(['user', 'items.product'])
            ->whereIn('status', self::PAID_STATUSES);

        if ($dateStart && $dateEnd) {
            $query->whereBetween('created_at', [
                Carbon::parse($dateStart)->startOfDay(),
                Carbon::parse($dateEnd)->endOfDay(),
            ]);
        } else {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        $orders  = $query->orderByDesc('created_at')->get();
        $summary = [
            'total_revenue'    => $orders->sum('total_price'),
            'total_orders'     => $orders->count(),
            'total_items_sold' => $orders->sum(fn($o) => $o->items->sum('quantity')),
        ];

        $filename = 'laporan-penjualan-' . Carbon::create($year, $month)->format('F-Y') . '.xlsx';

        return Excel::download(new SalesReportExport($orders, $summary), $filename);
    }

    // =========================================================
    // Private Helpers
    // =========================================================

    private function getPreviousRevenue(
        int $month,
        int $year,
        ?string $dateStart,
        ?string $dateEnd
    ): float {
        if ($dateStart && $dateEnd) {
            $diffDays = Carbon::parse($dateStart)->diffInDays(Carbon::parse($dateEnd)) + 1;
            $prevEnd   = Carbon::parse($dateStart)->subDay();
            $prevStart = $prevEnd->copy()->subDays($diffDays - 1);

            return Order::whereIn('status', self::PAID_STATUSES)
                ->whereBetween('created_at', [$prevStart->startOfDay(), $prevEnd->endOfDay()])
                ->sum('total_price');
        }

        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear  = $month === 1 ? $year - 1 : $year;

        return Order::whereIn('status', self::PAID_STATUSES)
            ->whereMonth('created_at', $prevMonth)
            ->whereYear('created_at', $prevYear)
            ->sum('total_price');
    }

    private function buildChartData(
        $orders,
        int $month,
        int $year,
        ?string $dateStart,
        ?string $dateEnd
    ): array {
        if ($dateStart && $dateEnd) {
            // Chart per hari dalam date range
            $start  = Carbon::parse($dateStart)->startOfDay();
            $end    = Carbon::parse($dateEnd)->endOfDay();
            $labels = [];
            $data   = [];

            $current = $start->copy();
            while ($current->lte($end)) {
                $dateKey  = $current->format('Y-m-d');
                $labels[] = $current->format('d M');
                $data[]   = $orders
                    ->filter(fn($o) => $o->created_at->format('Y-m-d') === $dateKey)
                    ->sum('total_price');
                $current->addDay();
            }
        } else {
            // Chart per hari dalam bulan
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $labels      = [];
            $data        = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateKey  = Carbon::create($year, $month, $day)->format('Y-m-d');
                $labels[] = $day;
                $data[]   = $orders
                    ->filter(fn($o) => $o->created_at->format('Y-m-d') === $dateKey)
                    ->sum('total_price');
            }
        }

        return compact('labels', 'data');
    }

    /**
     * Produk terlaris berdasarkan jumlah/unit yang terjual.
     * Bukan berdasarkan total pendapatan.
     */
    private function getTopProducts($orders): \Illuminate\Support\Collection
    {
        return $orders
            ->flatMap(fn($o) => $o->items)
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;

                return [
                    'name'     => $product?->name ?? 'Produk Dihapus',
                    'quantity' => (int) $items->sum('quantity'),
                    'revenue'  => (float) $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
    }

    private function getPaymentStats($orders): \Illuminate\Support\Collection
    {
        return $orders
            ->groupBy(fn($o) => strtolower($o->payment_type ?? 'unknown'))
            ->map(fn($group, $type) => [
                'type'    => strtoupper($type),
                'count'   => $group->count(),
                'revenue' => $group->sum('total_price'),
            ])
            ->sortByDesc('count')
            ->values();
    }

    private function getAvailableYears(): array
    {
        $years = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return empty($years) ? [Carbon::now()->year] : $years;
    }
}