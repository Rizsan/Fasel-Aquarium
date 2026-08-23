<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\DTOs\PredictionFilterDTO;
use App\Http\Controllers\Controller;
use App\Services\PredictionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * =====================================================================
 * PredictionController
 * =====================================================================
 * 
 * Manage prediction data requests dengan dynamic weighting.
 */
class PredictionController extends Controller
{
    public function __construct(
        private readonly PredictionService $predictionService
    ) {}

    /**
     * =====================================================================
     * Index: Tampilkan halaman prediksi
     * =====================================================================
     */
    public function index(Request $request)
    {
        // Default: 6 bulan terakhir dengan auto-generated weights
        $defaults = [
            'period'     => 'monthly',
            'start_date' => now()->subMonths(6)->startOfMonth()->format('Y-m-d'),
            'end_date'   => now()->endOfMonth()->format('Y-m-d'),
            'window'     => 3,
            'weights'    => '1,2,3', // Default ascending untuk window=3
        ];

        return view('admin.prediction.index', ['defaults' => $defaults]);
    }

    /**
     * =====================================================================
     * getData: AJAX endpoint untuk fetch prediksi data
     * =====================================================================
     * 
     * Validasi dynamic weights berdasarkan window size.
     */
    public function getData(Request $request): JsonResponse
    {
        // STEP 1: Validasi input
        $window = (int) ($request->input('window') ?? 3);
        $window = max(2, min($window, 12)); // Clamp: 2-12

        // STEP 2: Validasi dengan custom rule
        $validated = $request->validate([
            'period'  => ['required', 'in:daily,weekly,monthly'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'window'  => ['nullable', 'integer', 'min:2', 'max:12'],
            'weights' => [
                'nullable',
                'string',
                // Custom rule: validate weights format
                function ($attribute, $value, $fail) use ($window) {
                    if (!$this->validateWeightsFormat($value, $window)) {
                        $fail("Weights must be {$window} comma-separated integers >= 1");
                    }
                },
            ],
        ]);

        try {
            // STEP 3: Create DTO (auto-generate weights jika perlu)
            $filter = PredictionFilterDTO::fromRequest(array_merge(
                $validated,
                ['window' => $window]
            ));

            // STEP 4: Get prediction data
            $data = $this->predictionService->getPredictionData($filter);

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\LogicException $e) {
            // Validation error dari DTO
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // Server error
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * =====================================================================
     * Validate Weights Format
     * =====================================================================
     *
     * Helper untuk validasi weights format.
     *
     * @param string|null $value Input weights (CSV format)
     * @param int $window Target window size
     * @return bool
     */
    private function validateWeightsFormat(?string $value, int $window): bool
    {
        // Null/empty adalah valid (akan di-auto-generate)
        if ($value === null || $value === '') {
            return true;
        }

        // Parse weights
        $weights = array_map(
            fn($v) => trim($v),
            explode(',', $value)
        );

        // Check jumlah
        if (count($weights) !== $window) {
            return false;
        }

        // Check setiap elemen adalah integer >= 1
        foreach ($weights as $w) {
            if (!ctype_digit($w) || (int)$w < 1) {
                return false;
            }
        }

        return true;
    }
    /**
 * =====================================================================
 * HALAMAN PREDIKSI PENJUALAN
 * =====================================================================
 */
public function sales()
{
    $defaults = [
        'period'     => 'monthly',
        'start_date' => now()
            ->subMonths(6)
            ->startOfMonth()
            ->format('Y-m-d'),

        'end_date'   => now()
            ->endOfMonth()
            ->format('Y-m-d'),

        'window'     => 3,
        'weights'    => '1,2,3',
    ];

    return view('admin.prediction.sales', [
        'defaults' => $defaults,
    ]);
}
/**
 * =====================================================================
 * DATA PREDIKSI PENJUALAN
 * =====================================================================
 */
public function salesData(Request $request): JsonResponse
{
    $window = (int) ($request->input('window') ?? 3);

    $window = max(2, min($window, 12));

    $validated = $request->validate([
        'period' => [
            'required',
            'in:daily,weekly,monthly'
        ],

        'start_date' => [
            'required',
            'date'
        ],

        'end_date' => [
            'required',
            'date',
            'after_or_equal:start_date'
        ],

        'window' => [
            'nullable',
            'integer',
            'min:2',
            'max:12'
        ],

        'weights' => [
            'nullable',
            'string',

            function ($attribute, $value, $fail) use ($window) {

                if (!$this->validateWeightsFormat(
                    $value,
                    $window
                )) {
                    $fail(
                        "Weights harus {$window} angka yang dipisahkan koma."
                    );
                }
            }
        ],
    ]);

    try {

        $filter = PredictionFilterDTO::fromRequest(
            array_merge(
                $validated,
                ['window' => $window]
            )
        );

        $products = $this->predictionService
            ->getProductPredictions($filter);

        return response()->json([
            'success' => true,

            'data' => [
                'products' => $products,

                'summary' => [
                    'product_count' => $products->count(),

                    'total_prediction' => $products->sum(
                        'predicted_qty'
                    ),

                    'window' => $filter->window,

                    'weights' => $filter->weights,

                    'weights_display' =>
                        $filter->getWeightsAsString(),
                ],
            ],
        ]);

    } catch (\LogicException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Validation error: ' . $e->getMessage(),
        ], 422);

    } catch (\Exception $e) {

        logger()->error(
            'Sales Prediction Error',
            [
                'error' => $e->getMessage(),
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}
/**
 * =====================================================================
 * UPDATE STOCK
 * =====================================================================
 */
public function updateStock(
    Request $request,
    Product $product
): JsonResponse {

    $validated = $request->validate([
        'action' => [
            'required',
            'in:increase,decrease'
        ],

        'amount' => [
            'required',
            'integer',
            'min:1',
            'max:1000'
        ],
    ]);

    $amount = (int) $validated['amount'];

    if ($validated['action'] === 'increase') {

        $product->increment('stock', $amount);

    } else {

        if ($product->stock < $amount) {

            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi untuk dikurangi.',
            ], 422);
        }

        $product->decrement('stock', $amount);
    }

    $product->refresh();

    return response()->json([
        'success' => true,

        'message' => $validated['action'] === 'increase'
            ? "Stok {$product->name} berhasil ditambahkan."
            : "Stok {$product->name} berhasil dikurangi.",

        'data' => [
            'product_id' => $product->id,
            'stock' => (int) $product->stock,
        ],
    ]);
}
}
