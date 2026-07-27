<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with('product')
            ->latest()
            ->get();

        return view('products.wishlist.index', compact('wishlists'));
    }

    /**
     * TOGGLE WISHLIST (AJAX ONLY)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = auth()->user();

        $wishlist = $user->wishlists()
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from wishlist'
            ]);
        }

        $new = $user->wishlists()->create([
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Added to wishlist',
            'id' => $new->id
        ]);
    }

    /**
     * OPTIONAL: kalau masih mau pakai form biasa (non AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist');
    }
}