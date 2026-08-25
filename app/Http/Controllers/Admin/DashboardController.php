<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\MortalityRecord;
use Illuminate\Support\Facades\DB;
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

        // Ringkasan mortality
        $mortalityStats = [
            'total' => (int) MortalityRecord::sum('quantity'),
            'this_month' => (int) MortalityRecord::whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])->sum('quantity'),
            'affected_products' => (int) MortalityRecord::distinct('product_id')->count('product_id'),
            'top_cause' => MortalityRecord::query()
                ->select('cause', DB::raw('SUM(quantity) as total'))
                ->whereNotNull('cause')
                ->where('cause', '<>', '')
                ->groupBy('cause')
                ->orderByDesc('total')
                ->first(),
        ];

        $recentMortality = MortalityRecord::with('product')
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentProducts',
            'recentUsers',
            'mortalityStats',
            'recentMortality'
        ));
    }
}
