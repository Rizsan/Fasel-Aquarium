<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MortalityRecord;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MortalityController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'cause' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $query = MortalityRecord::query()
            ->with(['product', 'user'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = trim($request->string('search')->toString());

                $q->where(function ($query) use ($term) {
                    $query->whereHas('product', fn ($product) =>
                        $product->where('name', 'like', "%{$term}%")
                    )
                    ->orWhere('cause', 'like', "%{$term}%")
                    ->orWhere('notes', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('product_id'), fn ($q) =>
                $q->where('product_id', $request->integer('product_id'))
            )
            ->when($request->filled('cause'), fn ($q) =>
                $q->where('cause', $request->string('cause')->toString())
            )
            ->when($request->filled('date_from'), fn ($q) =>
                $q->whereDate('date', '>=', $request->date('date_from'))
            )
            ->when($request->filled('date_to'), fn ($q) =>
                $q->whereDate('date', '<=', $request->date('date_to'))
            );

        $mortalityRecords = $query
            ->latest('date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => (int) MortalityRecord::sum('quantity'),
            'this_month' => (int) MortalityRecord::whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])->sum('quantity'),
            'affected_products' => (int) MortalityRecord::distinct('product_id')->count('product_id'),
            'top_cause' => MortalityRecord::query()
                ->select('cause', DB::raw('SUM(quantity) as total'))
                ->whereNotNull('cause')
                ->where('cause', '<>', '')
                ->groupBy('cause')
                ->orderByDesc('total')
                ->first(),
        ];

        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'stock']);

        $causes = MortalityRecord::query()
            ->whereNotNull('cause')
            ->where('cause', '<>', '')
            ->distinct()
            ->orderBy('cause')
            ->pluck('cause');

        $monthly = MortalityRecord::query()
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as period, SUM(quantity) as total")
            ->whereBetween('date', [
                now()->subMonths(5)->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $chartLabels = [];
        $chartValues = [];
        $period = now()->subMonths(5)->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $key = $period->format('Y-m');
            $chartLabels[] = $period->translatedFormat('M Y');
            $chartValues[] = (int) ($monthly[$key] ?? 0);
            $period->addMonth();
        }

        $byProduct = MortalityRecord::query()
            ->select('product_id', DB::raw('SUM(quantity) as total'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->product?->name ?? 'Produk dihapus',
                'total' => (int) $row->total,
            ]);

        $byCause = MortalityRecord::query()
            ->select('cause', DB::raw('SUM(quantity) as total'))
            ->whereNotNull('cause')
            ->where('cause', '<>', '')
            ->groupBy('cause')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->cause,
                'total' => (int) $row->total,
            ]);

        return view('admin.mortality.index', compact(
            'mortalityRecords',
            'stats',
            'products',
            'causes',
            'chartLabels',
            'chartValues',
            'byProduct',
            'byCause'
        ));
    }

    public function create(): View
    {
        $products = Product::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'stock']);

        return view('admin.mortality.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'cause' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::query()
                ->whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];

            if ($quantity > $product->stock) {
                abort(422, "Jumlah ikan mati melebihi stok {$product->name} yang tersedia ({$product->stock} ekor).");
            }

            MortalityRecord::create([
                ...$validated,
                'quantity' => $quantity,
                'created_by' => auth()->id(),
            ]);

            $product->decrement('stock', $quantity);
        });

        return redirect()
            ->route('admin.mortality.index')
            ->with('success', 'Data mortality berhasil dicatat dan stock ikan telah diperbarui.');
    }

    public function show(MortalityRecord $mortality): View
    {
        $mortality->load(['product', 'user']);

        return view('admin.mortality.show', compact('mortality'));
    }

    public function edit(MortalityRecord $mortality): View
    {
        $mortality->load('product');

        $products = Product::query()
            ->where(function ($query) use ($mortality) {
                $query->where('is_active', true)
                    ->orWhere('id', $mortality->product_id);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'stock']);

        return view('admin.mortality.edit', compact('mortality', 'products'));
    }

    public function update(Request $request, MortalityRecord $mortality): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'cause' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($validated, $mortality) {
            $record = MortalityRecord::query()
                ->whereKey($mortality->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldProductId = (int) $record->product_id;
            $newProductId = (int) $validated['product_id'];
            $oldQuantity = (int) $record->quantity;
            $newQuantity = (int) $validated['quantity'];

            if ($oldProductId === $newProductId) {
                $product = Product::query()
                    ->whereKey($oldProductId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $delta = $newQuantity - $oldQuantity;

                if ($delta > 0 && $delta > $product->stock) {
                    abort(422, "Tambahan mortality melebihi stok {$product->name} yang tersedia ({$product->stock} ekor).");
                }

                if ($delta > 0) {
                    $product->decrement('stock', $delta);
                } elseif ($delta < 0) {
                    $product->increment('stock', abs($delta));
                }
            } else {
                $ids = collect([$oldProductId, $newProductId])->sort()->values();

                $lockedProducts = Product::query()
                    ->whereIn('id', $ids)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $oldProduct = $lockedProducts->get($oldProductId);
                $newProduct = $lockedProducts->get($newProductId);

                if (!$oldProduct || !$newProduct) {
                    abort(422, 'Produk yang dipilih tidak ditemukan.');
                }

                if ($newQuantity > $newProduct->stock) {
                    abort(422, "Jumlah ikan mati melebihi stok {$newProduct->name} yang tersedia ({$newProduct->stock} ekor).");
                }

                $oldProduct->increment('stock', $oldQuantity);
                $newProduct->decrement('stock', $newQuantity);
            }

            $record->update([
                ...$validated,
                'quantity' => $newQuantity,
            ]);
        });

        return redirect()
            ->route('admin.mortality.index')
            ->with('success', 'Data mortality berhasil diperbarui dan stock telah disesuaikan.');
    }

    public function destroy(MortalityRecord $mortality): RedirectResponse
    {
        DB::transaction(function () use ($mortality) {
            $record = MortalityRecord::query()
                ->whereKey($mortality->id)
                ->lockForUpdate()
                ->firstOrFail();

            $product = Product::query()
                ->whereKey($record->product_id)
                ->lockForUpdate()
                ->first();

            if ($product) {
                $product->increment('stock', (int) $record->quantity);
            }

            $record->delete();
        });

        return redirect()
            ->route('admin.mortality.index')
            ->with('success', 'Data mortality berhasil dihapus dan stock telah dikembalikan.');
    }
}
