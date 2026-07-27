<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Menampilkan halaman Syarat & Ketentuan.
     */
    public function terms(): View
    {
        return view('pages.terms');
    }

    /**
     * Menampilkan halaman Kebijakan Privasi.
     */
    public function privacy(): View
    {
        return view('pages.privacy');
    }
}