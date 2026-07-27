<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Helpers\FormatHelper;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Hanya user yang sudah login yang bisa akses cart.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =========================================================
    // INDEX — Tampilkan halaman cart
    // =========================================================

    public function index(): View
    {
        $userId = auth()->id();

        $cartItems = Cart::forUser($userId)
            ->withDetails()
            ->get();

        // Hitung total hanya dari item yang valid (tidak expired, stok cukup)
        $validTotal = $cartItems
            ->filter(fn($item) => $item->isCheckable())
            ->sum(fn($item) => $item->subtotal);

        // Cek apakah semua item bisa di-checkout
        $canCheckout = $cartItems->isNotEmpty()
            && $cartItems->every(fn($item) => $item->isCheckable());

        $blockReasons = $cartItems
            ->filter(fn($item) => ! $item->isCheckable())
            ->map(fn($item) => [
                'name'   => $item->product->name,
                'reason' => $item->getBlockReason(),
            ])
            ->values();

        return view('cart.index', compact(
            'cartItems',
            'validTotal',
            'canCheckout',
            'blockReasons',
        ));
    }

    // =========================================================
    // ADD — Tambah produk ke cart (Mendukung SweetAlert2 / AJAX)
    // =========================================================

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product  = Product::findOrFail($request->product_id);
        $userId   = auth()->id();
        $quantity = (int) $request->quantity;

        // Cek stok habis
        if ($product->stock <= 0) {
            $message = 'Maaf, stok produk "' . $product->name . '" sudah habis.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Cek apakah sudah ada di cart
        $cartItem = Cart::firstOrNew([
            'user_id'    => $userId,
            'product_id' => $product->id,
        ]);

        $newQty = $cartItem->exists
            ? $cartItem->quantity + $quantity
            : $quantity;

        // Validasi quantity tidak melebihi stok
        if ($newQty > $product->stock) {
            $message = 'Jumlah melebihi stok yang tersedia (' . $product->stock . ' item).';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        $cartItem->quantity = $newQty;

        // Jika item sudah ada, reset created_at (timer expired di-reset)
        if ($cartItem->exists) {
            $cartItem->created_at = now();
        }

        $cartItem->save();

        $successMessage = '"' . $product->name . '" berhasil ditambahkan ke keranjang.';

        // Respons khusus jika request datang dari Fetch / AJAX (Frontend SweetAlert2)
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'cart_count' => Cart::forUser($userId)->sum('quantity'), // Opsional: Untuk update counter keranjang
            ]);
        }

        // Fallback respons lama jika bukan request AJAX
        return back()->with('success', $successMessage);
    }

    // =========================================================
    // UPDATE — Update quantity item di cart
    // =========================================================

    public function update(Request $request, Cart $cart): JsonResponse
    {
        // Pastikan cart milik user yang login
        $this->authorizeCart($cart);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product     = $cart->product;
        $newQuantity = (int) $request->quantity;

        // Validasi stok — backend check
        if ($newQuantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia (' . $product->stock . ' item).',
                'max'     => $product->stock,
            ], 422);
        }

        $cart->update(['quantity' => $newQuantity]);

        return response()->json([
            'success'           => true,
            'message'           => 'Jumlah berhasil diperbarui.',
            'subtotal'          => $cart->subtotal,
            'formatted_subtotal' => $cart->formattedSubtotal,
            'new_total'         => $this->calculateTotal(),
            'formatted_total'   => FormatHelper::rupiah($this->calculateTotal()),
        ]);
    }

    // =========================================================
    // DESTROY — Hapus item dari cart
    // =========================================================

    public function destroy(Cart $cart): RedirectResponse
    {
        $this->authorizeCart($cart);

        $productName = $cart->product->name;
        $cart->delete();

        return redirect()
            ->route('cart.index')
            ->with('info', '"' . $productName . '" berhasil dihapus dari keranjang.');
    }

    // =========================================================
    // CLEAR — Kosongkan seluruh cart
    // =========================================================

    public function clear(): RedirectResponse
    {
        Cart::forUser(auth()->id())->delete();

        return redirect()
            ->route('cart.index')
            ->with('info', 'Keranjang belanja berhasil dikosongkan.');
    }

    // =========================================================
    // CHECKOUT — Validasi sebelum checkout
    // =========================================================

    public function checkout(): RedirectResponse
    {
        $userId    = auth()->id();
        $cartItems = Cart::forUser($userId)->withDetails()->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi semua item
        foreach ($cartItems as $item) {
            if ($item->isExpired()) {
                return back()->with('error', 'Item "' . $item->product->name . '" sudah expired. Hapus item tersebut sebelum checkout.');
            }

            if ($item->product->stock <= 0) {
                return back()->with('error', 'Stok produk "' . $item->product->name . '" sudah habis.');
            }

            if ($item->quantity > $item->product->stock) {
                return back()->with('error', 'Jumlah "' . $item->product->name . '" melebihi stok tersedia (' . $item->product->stock . ').');
            }
        }

        // TODO: Lanjutkan ke proses pembayaran / order
        return redirect()
            ->route('checkout.index')
            ->with('success', 'Silakan lanjutkan ke pembayaran.');
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    /**
     * Pastikan cart item milik user yang sedang login.
     */
    private function authorizeCart(Cart $cart): void
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke item ini.');
        }
    }

    /**
     * Hitung total valid (tidak expired, stok cukup).
     */
    private function calculateTotal(): int
    {
        return Cart::forUser(auth()->id())
            ->withDetails()
            ->get()
            ->filter(fn($item) => $item->isCheckable())
            ->sum(fn($item) => $item->subtotal);
    }
}