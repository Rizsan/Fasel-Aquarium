<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // =========================================================
    // INDEX
    // =========================================================

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])
            ->latestFirst();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query
            ->paginate(15)
            ->withQueryString();

        $stats = [
    'total'               => Order::count(),
    'waiting_payment'     => Order::where('status', 'waiting_payment')->count(),
    'ready_for_pickup'    => Order::where('status', 'ready_for_pickup')->count(),
    'processing'          => Order::where('status', 'processing')->count(),
    'completed'           => Order::where('status', 'completed')->count(),
    'cancelled'           => Order::where('status', 'cancelled')->count(),
];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    // =========================================================
    // SHOW
    // =========================================================

    public function show(Order $order): View
    {
        $order->load([
            'user',
            'items.product'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    // =========================================================
    // EDIT
    // =========================================================

    public function edit(Order $order): View|RedirectResponse
    {
        if (! $order->isUpdatableByAdmin()) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with(
                    'error',
                    'Order dengan status "' .
                    $order->status_label .
                    '" tidak dapat diubah.'
                );
        }

        $order->load([
            'user',
            'items.product'
        ]);

        $availableStatuses = $this->getAvailableStatuses($order->status);

        return view(
            'admin.orders.edit',
            compact('order', 'availableStatuses')
        );
    }

    // =========================================================
    // UPDATE
    // =========================================================

    public function update(Request $request, Order $order): RedirectResponse
    {
        if (! $order->isUpdatableByAdmin()) {

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Order ini tidak dapat diubah.');
        }

        $availableStatuses = $this->getAvailableStatuses($order->status);

        $request->validate([
            'status' => [
                'required',
                'string',
                'in:' . implode(',', $availableStatuses),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ]);

        DB::beginTransaction();

        try {

            $oldStatus = $order->status;

            $order->update([
                'status' => $request->status,
                'notes'  => $request->notes,
            ]);

            Log::info('Admin updated order status', [
                'order_number' => $order->order_number,
                'admin_id'     => auth()->id(),
                'old_status'   => $oldStatus,
                'new_status'   => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with(
                    'success',
                    'Status pesanan berhasil diperbarui menjadi "' .
                    $order->fresh()->status_label .
                    '".'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Admin order update failed', [
                'order_id' => $order->id,
                'message'  => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui status.');
        }
    }

    // =========================================================
    // STATUS FLOW
    // =========================================================

    private function getAvailableStatuses(string $currentStatus): array
{
    return match ($currentStatus) {

        // Midtrans belum dibayar
        'pending' => [
            'cancelled',
        ],

        // Midtrans sudah dibayar
        'paid' => [
            'completed',
            'cancelled',
        ],

        // Cash langsung disiapkan
        'processing' => [
            'completed',
            'cancelled',
        ],

        default => [],
    };
}
}