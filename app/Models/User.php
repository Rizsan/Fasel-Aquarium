<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\Supabase\SupabaseStorageService;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
        'profile_photo',
        'last_login_at',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi data (JSON/Array).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast kolom ke tipe data yang sesuai.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // =========================================================
    // Accessors
    // =========================================================

    /**
     * URL foto profile user.
     */
    public function getProfilePhotoUrlAttribute(): ?string
{
    if (!$this->profile_photo) {
        return null;
    }

    return app(SupabaseStorageService::class)
        ->profilePhotoUrl($this->profile_photo);
}

    /**
     * Initial avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials ?: 'U';
    }

    /**
     * Label role.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'user'  => 'Pelanggan',
            default => ucfirst($this->role),
        };
    }

    // =========================================================
    // Role Helpers
    // =========================================================

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Cek apakah akun aktif.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    // =========================================================
    // Query Scopes
    // =========================================================

    /**
     * Scope: mencari berdasarkan nama atau email.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%');
        });
    }

    /**
     * Scope: memfilter berdasarkan role.
     */
    public function scopeFilterRole(Builder $query, ?string $role): Builder
    {
        if (!$role) return $query;

        return $query->where('role', $role);
    }

    /**
     * Scope: memfilter berdasarkan status aktif/nonaktif.
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if ($status === null || $status === '') return $query;

        return $query->where('is_active', $status === 'active');
    }

    /**
     * Scope: hanya admin.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: hanya regular user.
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope: hanya user aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =========================================================
    // Relations
    // =========================================================

    /**
     * Wishlist relation.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Orders relation.
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
}