<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    //Tampilkan form registrasi.
    public function create(): View
    {
        return view('auth.register');
    }

    //Proses pendaftaran akun baru.
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // default role
            'is_active' => true,
        ]);

        // Trigger event Registered (bisa dipakai untuk verifikasi email)
        event(new Registered($user));

        // Login otomatis setelah daftar
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $request->first_name . '!');
    }
}
