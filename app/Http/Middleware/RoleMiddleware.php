<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    //Handle an incoming request.
     //@param  string  $role  Role yang diizinkan (e.g., 'admin')
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah role cocok
        if ($request->user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek apakah akun aktif
        if (! $request->user()->isActive()) {
            abort(403, 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
