<?php
// app/Models/Cart.php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // =========================================================
    // Relationships
    // =========================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // =========================================================
    // Accessors
    // =========================================================

    /**
     * Subtotal = harga x quantity.
     */
    public function getSubtotalAttribute(): int
    {
        return $this->product->price * $this->quantity;
    }

    /**
     * Subtotal dalam format Rupiah.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return FormatHelper::rupiah($this->subtotal);
    }

    // =========================================================
    // Business Logic
    // =========================================================

    /**
     * Item dianggap expired jika sudah lebih dari 3 hari
     * sejak ditambahkan ke cart.
     */
    public function isExpired(): bool
    {
        return $this->created_at->diffInDays(now()) >= 3;
    }

    /**
     * Cek apakah item bisa di-checkout.
     */
    public function isCheckable(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        if ($this->product->stock <= 0) {
            return false;
        }

        if ($this->quantity > $this->product->stock) {
            return false;
        }

        return true;
    }

    /**
     * Alasan item tidak bisa checkout.
     */
    public function getBlockReason(): ?string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->product->stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->quantity > $this->product->stock) {
            return 'exceeds_stock';
        }

        return null;
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithDetails($query)
    {
        return $query->with('product')->latest();
    }
}
