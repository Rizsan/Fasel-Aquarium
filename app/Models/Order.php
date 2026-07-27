<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'status',
        'snap_token',
        'payment_type',
        'transaction_status',
        'transaction_id',
        'paid_at',
        'notes',
        'payment_method',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'paid_at'     => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Alias agar tetap kompatibel jika ada pemanggilan orderItems()
    public function orderItems(): HasMany
    {
        return $this->items();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedTotalPriceAttribute(): string
    {
        return FormatHelper::rupiah((int) $this->total_price);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'pending'    => 'Menunggu Pembayaran',

            'paid'       => 'Sudah Dibayar',

            'processing' => 'Sedang Disiapkan',

            'completed'  => 'Selesai',

            'cancelled'  => 'Dibatalkan',

            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {

            'pending'    => 'yellow',

            'paid'       => 'blue',

            'processing' => 'indigo',

            'completed'  => 'green',

            'cancelled'  => 'red',

            default      => 'gray',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {

            'pending'
                => 'bg-yellow-100 text-yellow-800 border-yellow-200',

            'paid'
                => 'bg-blue-100 text-blue-800 border-blue-200',

            'processing'
                => 'bg-indigo-100 text-indigo-800 border-indigo-200',

            'completed'
                => 'bg-green-100 text-green-800 border-green-200',

            'cancelled'
                => 'bg-red-100 text-red-800 border-red-200',

            default
                => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {

            'pending'    => 'warning',

            'paid'       => 'primary',

            'processing' => 'info',

            'completed'  => 'success',

            'cancelled'  => 'danger',

            default      => 'secondary',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public static function generateOrderNumber(): string
    {
        do {

            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));

        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function isPendingPayment(): bool
    {
        return $this->status === 'pending'
            && !empty($this->snap_token);
    }

    public function isPaid(): bool
    {
        return in_array($this->status, [
            'paid',
            'processing',
            'completed'
        ]);
    }

    /**
     * Admin hanya boleh mengubah status
     * ketika pesanan sudah dibayar.
     */
    public function isUpdatableByAdmin(): bool
    {
        return in_array($this->status, [
            'paid',
            'processing',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithFullDetails($query)
    {
        return $query->with([
            'user',
            'items.product',
        ]);
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest();
    }

    /**
     * Order yang dianggap sudah menghasilkan pendapatan.
     */
    public const VALID_STATUSES = [
        'paid',
        'processing',
        'completed',
    ];

    public function scopePaid($query)
    {
        return $query->whereIn('status', self::VALID_STATUSES);
    }

    public function scopeRevenue($query)
    {
        return $query->whereIn('status', self::VALID_STATUSES);
    }
}