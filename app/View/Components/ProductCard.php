<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public bool $isWishlisted;

    public function __construct(public Product $product)
    {
        $this->isWishlisted = $this->checkWishlist();
    }

    private function checkWishlist(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()
            ->wishlists()
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function render()
    {
        return view('components.product-card');
    }
}