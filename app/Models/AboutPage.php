<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Services\Supabase\SupabaseStorageService;

class AboutPage extends Model
{
    use SoftDeletes;

    protected $table = 'about_pages';

    protected $fillable = [
        'title',
        'about_content',
        'why_choose_us',
        'how_to_shop',
        'facilities',
        'contact_address',
        'contact_whatsapp',
        'contact_instagram',
        'contact_phone',
        'operation_hours',
        'gallery_1',
        'gallery_2',
        'gallery_3',
        'gallery_4',
        'gallery_5',
    ];

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    protected function galleryUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return app(SupabaseStorageService::class)
            ->websiteAssetUrl($path);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getGallery1UrlAttribute(): ?string
    {
        return $this->galleryUrl($this->gallery_1);
    }

    public function getGallery2UrlAttribute(): ?string
    {
        return $this->galleryUrl($this->gallery_2);
    }

    public function getGallery3UrlAttribute(): ?string
    {
        return $this->galleryUrl($this->gallery_3);
    }

    public function getGallery4UrlAttribute(): ?string
    {
        return $this->galleryUrl($this->gallery_4);
    }

    public function getGallery5UrlAttribute(): ?string
    {
        return $this->galleryUrl($this->gallery_5);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get single about page atau create default jika tidak ada
     */
    public static function getInstance()
    {
        return self::first() ?? self::create([
            'title' => 'Tentang Kami',
            'about_content' => 'Konten halaman tentang kami akan ditampilkan di sini.',
        ]);
    }

    /**
     * Get all galleries as Collection
     */
    public function getGalleries(): Collection
    {
        $galleries = [];

        for ($i = 1; $i <= 5; $i++) {

            $pathField = "gallery_{$i}";
            $urlField  = "gallery_{$i}_url";

            if ($this->{$pathField}) {

                $galleries[] = [
                    'key'  => $pathField,
                    'path' => $this->{$pathField},
                    'url'  => $this->{$urlField},
                ];
            }
        }

        return collect($galleries);
    }
}