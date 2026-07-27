<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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
     * Get all galleries
     */
    public function getGalleries(): Collection
{
    $galleries = [];

    for ($i = 1; $i <= 5; $i++) {
        $galleryKey = "gallery_{$i}";

        if (!empty($this->{$galleryKey})) {
            $galleries[] = [
                'key'  => $galleryKey,
                'path' => $this->{$galleryKey},
            ];
        }
    }

    return collect($galleries);
}
}
