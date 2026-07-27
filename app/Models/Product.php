<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Supabase\SupabaseStorageService;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
            'stock'     => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStockStatusAttribute(): string
    {
        return match (true) {
            $this->stock === 0   => 'Habis',
            $this->stock <= 10   => 'Menipis',
            default              => 'Tersedia',
        };
    }

    public function getStockStatusColorAttribute(): string
    {
        return match (true) {
            $this->stock === 0  => 'red',
            $this->stock <= 10  => 'yellow',
            default             => 'green',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function getImageUrlAttribute(): string
{
    if (!$this->image) {
        return 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image';
    }

    return app(SupabaseStorageService::class)
        ->productUrl($this->image);
}

    public function orderItems()
    {
    return $this->hasMany(OrderItem::class);
    }
}


