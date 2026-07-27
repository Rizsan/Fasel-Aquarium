<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistik ringkasan
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
            'total_admin'    => User::where('role', 'admin')->count(),
            'active_users'   => User::where('role', 'user')
                                    ->whereDate('updated_at', today())
                                    ->count(),
        ];

        // 5 produk terbaru
        $recentProducts = Product::latest()->take(5)->get();

        // 5 pengguna terbaru
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentUsers'));
    }
}
