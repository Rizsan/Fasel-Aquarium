<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Tampilkan halaman tentang kami
     */
    public function index()
    {
        $about = AboutPage::getInstance();
        $settings = WebsiteSetting::getInstance();
        $galleries = $about->getGalleries();

        return view('about', [
            'about' => $about,
            'settings' => $settings,
            'galleries' => $galleries,
        ]);
    }
}
