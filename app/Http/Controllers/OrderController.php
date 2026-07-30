<?php

namespace App\Http\Controllers;

use App\Helpers\FormatHelper;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Midtrans\Snap;
use Midtrans\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('notification');
    }

    // =========================================================
    // INDEX — Daftar order milik user
    // =========================================================

    public function index(): View
    {
        $orders = Order::forUser(auth()->id())
            ->with(['items.product'])
            ->latestFirst()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // =========================================================
    // SHOW — Detail order user
    // =========================================================

    public function show(Order $order): View
    {
        // Pastikan order milik user yang login
        $this->authorizeOrder($order);

        $order->load(['items.product', 'user']);

        return view('orders.show', compact('order'));
    }

    // =========================================================
    // CHECKOUT — Buat order dari cart, generate snap token
    // =========================================================

    public function checkout(Request $request): RedirectResponse
{
    $request->validate([
        'payment_method' => 'required|in:cash,transfer',
    ]);

    $user = auth()->user();

    $cartItems = Cart::forUser($user->id)
        ->withDetails()
        ->get();

        // ── Validasi cart tidak kosong
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // ── Validasi semua item sebelum buat order
        foreach ($cartItems as $item) {
            if ($item->isExpired()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Item "' . $item->product->name . '" sudah expired. Hapus sebelum checkout.');
            }

            if ($item->product->stock <= 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Stok produk "' . $item->product->name . '" sudah habis.');
            }

            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')
                    ->with('error', 'Jumlah "' . $item->product->name . '" melebihi stok (' . $item->product->stock . ').');
            }
        }

        // ── Hitung total
        $totalPrice = $cartItems->sum(fn($item) => $item->subtotal);

        // ── Buat order dalam DB transaction
        DB::beginTransaction();

        try {
            // 1. Buat order
            $order = Order::create([
    'user_id'         => $user->id,
    'order_number'    => Order::generateOrderNumber(),
    'total_price'     => $totalPrice,

    // metode yang dipilih user
    'payment_method'  => $request->payment_method,

    // jika cash langsung tandai cash
    'payment_type'    => $request->payment_method === 'cash'
                            ? 'cash'
                            : null,

    // status transaksi
    'transaction_status' => $request->payment_method === 'cash'
                                ? 'cash'
                                : null,

    // id transaksi untuk pembayaran cash
    'transaction_id' => $request->payment_method === 'cash'
                            ? 'CASH-' . strtoupper(uniqid())
                            : null,

    // status pembayaran
    'payment_status' => $request->payment_method === 'cash'
                            ? 'paid'
                            : 'unpaid',

    // status order
    'status' => $request->payment_method === 'cash'
    ? 'processing'
    : 'pending',

    // waktu pembayaran
    'paid_at' => $request->payment_method === 'cash'
                    ? now()
                    : null,
]);

            // 2. Buat order items
            $itemDetails = [];

            foreach ($cartItems as $cartItem) {
                $orderItem = OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'price'      => $cartItem->product->price,
                    'subtotal'   => $cartItem->subtotal,
                ]);

                // Siapkan item details untuk Midtrans
                $itemDetails[] = [
                    'id'       => (string) $cartItem->product_id,
                    'price'    => (int) $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'name'     => mb_substr($cartItem->product->name, 0, 50),
                ];
            }

            // Jika pembayaran Cash

if ($request->payment_method === 'cash') {

    $this->finalizeOrder($order);

    DB::commit();

    return redirect()
        ->route('orders.success', $order->id)
        ->with('success', 'Pesanan berhasil dibuat.');
}

// Jika Transfer → gunakan Midtrans
$snapToken = $this->generateSnapToken(
    $order,
    $user,
    $itemDetails,
    (int) $totalPrice
);

$order->update([
    'snap_token' => $snapToken,
]);

DB::commit();

return redirect()
    ->route('orders.payment', $order->id)
    ->with('success', 'Order berhasil dibuat. Silakan selesaikan pembayaran.');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Checkout failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    // =========================================================
    // PAYMENT — Halaman pembayaran dengan Snap popup
    // =========================================================

    public function payment(Order $order): View|RedirectResponse
    {
        $this->authorizeOrder($order);

        // Jika sudah dibayar, redirect ke success
        if ($order->isPaid()) {
            return redirect()->route('orders.success', $order->id);
        }

        // Jika dibatalkan
        if ($order->status === 'cancelled') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Order ini telah dibatalkan.');
        }

        // Jika snap token tidak ada, coba regenerate
        if (!$order->snap_token) {
            try {
                $order->load('items.product');
                $itemDetails = $order->items->map(fn($item) => [
                    'id'       => (string) $item->product_id,
                    'price'    => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name'     => mb_substr($item->product->name, 0, 50),
                ])->toArray();

                $snapToken = $this->generateSnapToken(
                    $order,
                    auth()->user(),
                    $itemDetails,
                    (int) $order->total_price
                );

                $order->update(['snap_token' => $snapToken]);
            } catch (\Throwable $e) {
                Log::error('Snap token regeneration failed', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);

                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Gagal memuat payment gateway. Coba lagi nanti.');
            }
        }

        $order->load('items.product');
        $clientKey = config('midtrans.client_key');
        $snapUrl   = config('midtrans.snap_url');

        return view('orders.payment', compact('order', 'clientKey', 'snapUrl'));
    }

    // =========================================================
    // SUCCESS — Halaman sukses setelah pembayaran
    // =========================================================

    public function success(Order $order): View|RedirectResponse
    {
        $this->authorizeOrder($order);

        if (!$order->isPaid()) {
            return redirect()->route('orders.payment', $order->id);
        }

        $order->load('items.product');

        return view('orders.success', compact('order'));
    }

    // =========================================================
    // FINISH — Callback dari Snap setelah transaksi (frontend)
    // =========================================================

    public function finish(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $transactionStatus = $request->query('transaction_status');
        $orderId           = $request->query('order_id');

        // Refresh status dari database (notification mungkin sudah update)
        $order->refresh();

        if ($order->isPaid()) {
            return redirect()->route('orders.success', $order->id)
                ->with('success', 'Pembayaran berhasil! Terima kasih.');
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pembayaran tidak berhasil. Silakan coba lagi.');
        }

        // Pending (transfer bank, dll)
        return redirect()->route('orders.show', $order->id)
            ->with('info', 'Pembayaran sedang diproses. Kami akan memberitahu Anda setelah konfirmasi.');
    }

    // =========================================================
    // NOTIFICATION — Webhook dari Midtrans Server
    // =========================================================

    public function notification(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info('=== WEBHOOK MASUK ===');

    Log::info('RAW BODY', [
        'body' => $request->getContent(),
    ]);

    Log::info('HEADERS', $request->headers->all());

        try {
            // Validasi notifikasi dari Midtrans
            $payload = json_decode($request->getContent(), true);
                $orderId           = $payload['order_id'];
                $transactionId     = $payload['transaction_id'];
                $transactionStatus = $payload['transaction_status'];
                $paymentType       = $payload['payment_type'];
                $fraudStatus       = $payload['fraud_status'] ?? null;

            Log::info('Midtrans notification received', [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type'       => $paymentType,
                'fraud_status'       => $fraudStatus,
            ]);

            // Cari order berdasarkan order_number
            $order = Order::where('order_number', $orderId)->first();
            Log::info('Order ditemukan', [
            'id' => $order?->id,
            'order_number' => $order?->order_number,
]);
            if (!$order) {
                Log::warning('Midtrans notification: order not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Proses berdasarkan status transaksi
            DB::beginTransaction();

            try {
                $this->handleTransactionStatus(
                    $order,
                    $transactionStatus,
                    $transactionId,
                    $paymentType,
                    $fraudStatus
                );

                DB::commit();

                return response()->json(['message' => 'OK'], 200);

            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Throwable $e) {

    Log::error('Midtrans notification error', [
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString(),
    ]);

    return response()->json([
        'error' => $e->getMessage()
    ], 500);
}
    }

    // =========================================================
    // PRIVATE: Generate Snap Token
    // =========================================================

    private function generateSnapToken(
        Order $order,
        $user,
        array $itemDetails,
        int $totalPrice
    ): string {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => $totalPrice,
            ],
            'item_details'    => $itemDetails,
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'callbacks' => [
                'finish' => route('orders.finish', $order->id),
            ],
            'expiry' => [
                'unit'     => 'hours',
                'duration' => 24,
            ],
        ];
        Log::info('Generate Snap Params', [
    'order_id' => $order->order_number,
    'params' => $params,
]);
        return Snap::getSnapToken($params);
    }

    // =========================================================
    // PRIVATE: Handle Transaction Status dari Midtrans
    // =========================================================

    private function handleTransactionStatus(
        Order $order,
        string $transactionStatus,
        string $transactionId,
        ?string $paymentType,
        ?string $fraudStatus
    ): void {
        // Jangan proses ulang jika order sudah final
        if (in_array($order->status, ['paid', 'completed', 'cancelled'])) {
            Log::info('Midtrans notification skipped: order already in final state', [
                'order_number' => $order->order_number,
                'status'       => $order->status,
            ]);
            return;
        }

        switch ($transactionStatus) {

            // ── BERHASIL BAYAR
            case 'settlement':
            case 'capture':
                $isValid = true;

                // Jika capture, cek fraud status
                if ($transactionStatus === 'capture') {
                    $isValid = ($fraudStatus === 'accept');
                }

                if ($isValid) {
                    $this->markAsPaid($order, $transactionId, $paymentType);
                } else {
                    // Fraud terdeteksi
                    $this->markAsCancelled($order, $transactionId, $paymentType, 'Fraud detected');
                }
                break;

            // ── PENDING (transfer bank, VA, dll)
            case 'pending':
                $order->update([
                    'transaction_status' => 'pending',
                    'transaction_id'     => $transactionId,
                    'payment_type'       => $paymentType,
                ]);

                Log::info('Order payment pending', [
                    'order_number'   => $order->order_number,
                    'payment_type'   => $paymentType,
                    'transaction_id' => $transactionId,
                ]);
                break;

            // ── DIBATALKAN / EXPIRED / DITOLAK
            case 'cancel':
            case 'expire':
            case 'deny':
                $this->markAsCancelled($order, $transactionId, $paymentType, $transactionStatus);
                break;

            default:
                Log::warning('Midtrans: unknown transaction status', [
                    'status'       => $transactionStatus,
                    'order_number' => $order->order_number,
                ]);
        }
    }

    // =========================================================
    // PRIVATE: Mark order as paid + kurangi stok + clear cart
    // =========================================================

    private function markAsPaid(Order $order, string $transactionId, ?string $paymentType): void
    {
        // Update status order
        $order->update([
    'payment_status'     => 'paid',
    'status'             => 'paid',
    'transaction_status' => 'settlement',
    'transaction_id'     => $transactionId,
    'payment_type'       => $paymentType,
    'paid_at'            => now(),
]);

        $this->finalizeOrder($order);

        Log::info('Order marked as paid', [
            'order_number'   => $order->order_number,
            'payment_type'   => $paymentType,
            'transaction_id' => $transactionId,
        ]);
    }
    private function finalizeOrder(Order $order): void
{
    $order->loadMissing('items');

    // Kurangi stok produk
    foreach ($order->items as $item) {
        Product::where('id', $item->product_id)
            ->decrement('stock', $item->quantity);
    }

    // Kosongkan keranjang user
    Cart::where('user_id', $order->user_id)->delete();
}

    // =========================================================
    // PRIVATE: Mark order as cancelled
    // =========================================================

    private function markAsCancelled(
        Order $order,
        string $transactionId,
        ?string $paymentType,
        string $reason
    ): void {
        $order->update([
            'status'             => 'cancelled',
            'transaction_status' => $reason,
            'transaction_id'     => $transactionId,
            'payment_type'       => $paymentType,
        ]);

        Log::info('Order cancelled', [
            'order_number' => $order->order_number,
            'reason'       => $reason,
        ]);
    }

    // =========================================================
    // PRIVATE: Authorize order milik user
    // =========================================================

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }
    }

    use Barryvdh\DomPDF\Facade\Pdf;

public function downloadPdf(Order $order)
{
    $order->load('items.product', 'user');

    $pdf = Pdf::loadView('orders.pdf', compact('order'));

    return $pdf->download("Invoice-{$order->order_number}.pdf");
}
}
