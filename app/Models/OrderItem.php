<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price'    => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // =========================================================
    // Relationships
    // =========================================================

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // =========================================================
    // Accessors
    // =========================================================

    public function getFormattedPriceAttribute(): string
    {
        return FormatHelper::rupiah((int) $this->price);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return FormatHelper::rupiah((int) $this->subtotal);
    }
}
