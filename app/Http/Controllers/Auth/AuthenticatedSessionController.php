<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login pengguna.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi (validasi email & password)
        $request->authenticate();

        // Cek status akun (is_active)
        $user = Auth::user();
        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ]);
        }

        // Regenerate session untuk keamanan
        $request->session()->regenerate();
        $user->update([
        'last_login_at' => now(),
        ]);
        // Redirect berdasarkan role user
        if ($user->isAdmin()) {
            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()
            ->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Proses logout pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('info', 'Anda berhasil logout.');
    }
}
