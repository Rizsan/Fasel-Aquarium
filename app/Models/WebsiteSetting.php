<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Supabase\SupabaseStorageService;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        'app_name',
        'logo',
        'favicon',
        'slogan',
        'email',
        'phone',
        'whatsapp',
        'address',
        'instagram',
        'facebook',
        'latitude',
        'longitude',
        'timezone',
        'date_format',
        'products_per_page',
        'maintenance_mode',
        'copyright_text',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'maintenance_mode' => 'boolean',
        'products_per_page' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return app(SupabaseStorageService::class)
            ->websiteAssetUrl($this->logo);
    }

    public function getFaviconUrlAttribute(): ?string
    {
        if (!$this->favicon) {
            return null;
        }

        return app(SupabaseStorageService::class)
            ->websiteAssetUrl($this->favicon);
    }

    /**
     * Get single setting menggunakan Cache & firstOrCreate agar aman dari race condition
     */
    public static function getInstance()
    {
        return self::firstOrCreate([], [
            'app_name'          => 'Fasel Aquarium',
            'slogan'            => 'Platform E-commerce Terpercaya Untuk Ikan Hias',
            'email'             => 'info@faselaquarium.com',
            'phone'             => '083131871300',
            'whatsapp'          => '083131871300',
            'address'           => 'Indramayu, Jawa Barat',
            'latitude'          => -6.3334185,
            'longitude'         => 108.3242836,
            'timezone'          => 'Asia/Jakarta',
            'date_format'       => 'd/m/Y',
            'products_per_page' => 12,
            'maintenance_mode'  => false,
            'copyright_text'    => 'Semua Hak Dilindungi',
        ]);
    }

    /**
     * Get setting by key
     */
    public static function getSetting($key, $default = null)
    {
        $setting = self::getInstance();
        return $setting->{$key} ?? $default;
    }

    /**
     * Update setting dan otomatis membersihkan cache yang lama
     */
    public static function updateSetting($key, $value)
    {
        $setting = self::firstOrCreate([]);
        $setting->update([$key => $value]);

        return $setting;
    }
}