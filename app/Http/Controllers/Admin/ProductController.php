<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Supabase\SupabaseStorageService;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index — Daftar Produk
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $products = Product::query()
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create — Form Tambah Produk
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('admin.products.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store — Simpan Produk Baru
    |--------------------------------------------------------------------------
    */

    public function store(StoreProductRequest $request): RedirectResponse
{
    $data = $request->validated();

    // Handle upload gambar
    if ($request->hasFile('image')) {

    $data['image'] = app(SupabaseStorageService::class)
        ->uploadProduct($request->file('image'));

}

    Product::create($data);

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Produk berhasil ditambahkan!');
}

    /*
    |--------------------------------------------------------------------------
    | Show — Detail Produk
    |--------------------------------------------------------------------------
    */

    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit — Form Edit Produk
    |--------------------------------------------------------------------------
    */

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update — Perbarui Produk
    |--------------------------------------------------------------------------
    */

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
    $data = $request->validated();

    if ($request->hasFile('image')) {

    $storage = app(SupabaseStorageService::class);

    if ($product->image) {
        $storage->delete(
            env('SUPABASE_PRODUCT_BUCKET'),
            $product->image
        );
    }

    $data['image'] = $storage->uploadProduct(
        $request->file('image')
    );
}

    $product->update($data);

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Produk berhasil diperbarui!');
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy — Hapus Produk
    |--------------------------------------------------------------------------
    */

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {

    app(SupabaseStorageService::class)
        ->delete(
            env('SUPABASE_PRODUCT_BUCKET'),
            $product->image
        );

}

$product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
