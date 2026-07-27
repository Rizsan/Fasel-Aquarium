<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\WebsiteSetting;

class HomeController extends Controller
{
    // Tampilkan homepage dengan daftar produk
    public function index(Request $request): View
    {
        $query = Product::active()->latest();

        // Filter search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $settings = WebsiteSetting::first();

$perPage = $settings?->products_per_page ?? 12;

$products = $query
    ->paginate($perPage)
    ->withQueryString();

// Statistik
$totalProducts = Product::count();
$totalUsers = User::where('role', 'user')->count();

return view('home.index', compact(
    'products',
    'totalProducts',
    'totalUsers'
));
    }

    public function show($id): View
{
    $product = Product::findOrFail($id);

    $relatedProducts = Product::query()
        ->select('products.*')
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
        ->where('products.id', '!=', $product->id)
        ->groupBy(
            'products.id',
            'products.name',
            'products.description',
            'products.price',
            'products.stock',
            'products.image',
            'products.created_at',
            'products.updated_at'
        )
        ->orderByDesc('total_sold')
        ->latest('products.created_at')
        ->take(8)
        ->get();

    return view('products.show', [
        'product' => $product,
        'relatedProducts' => $relatedProducts,
    ]);
}
}