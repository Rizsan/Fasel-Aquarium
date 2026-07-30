<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Services\Supabase\SupabaseStorageService;

class ProfileController extends Controller
{
    // =========================================================
    // index() — Halaman Profile User
    // =========================================================
    public function index(): View
    {
        $user = Auth::user()->load('orders');

        $stats = [
            'total_orders'    => $user->orders()->count(),
            'completed_orders'=> $user->orders()->where('status', 'completed')->count(),
            'pending_orders'  => $user->orders()->whereIn('status', ['pending', 'paid', 'processing'])->count(),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    // =========================================================
    // update() — Update Profile User
    // =========================================================
    public function update(
    Request $request,
    SupabaseStorageService $storage
): RedirectResponse
    {
        $user = Auth::user();

        // --- Validasi ---
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan.',
            'profile_photo.image'    => 'File harus berupa gambar.',
            'profile_photo.mimes'    => 'Format gambar: jpg, jpeg, png.',
            'profile_photo.max'      => 'Ukuran gambar maksimal 2MB.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        // --- Update Data Profile ---
        $updateData = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        // --- Upload Foto Profile ---
       if ($request->hasFile('profile_photo')) {

    // Hapus foto lama
    if ($user->profile_photo) {
        $storage->delete(
            env('SUPABASE_PROFILE_BUCKET'),
            $user->profile_photo
        );
    }

    // Upload ke Supabase
    $updateData['profile_photo'] = $storage
        ->uploadProfilePhoto(
            $request->file('profile_photo')
        );
}

        // --- Update Password (opsional) ---
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // --- Simpan ke Database ---
        $user->update($updateData);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // =========================================================
    // deletePhoto() — Hapus Foto Profile
    // =========================================================
    public function deletePhoto(
    Request $request,
    SupabaseStorageService $storage
): RedirectResponse
{
    $user = Auth::user();

    if (!$user->profile_photo) {
        return redirect()
            ->route('profile.index')
            ->with('error', 'Foto profil tidak ditemukan.');
    }

    $deleted = $storage->delete(
        env('SUPABASE_PROFILE_BUCKET'),
        $user->profile_photo
    );

    if (!$deleted) {
        return redirect()
            ->route('profile.index')
            ->with('error', 'Gagal menghapus foto profil.');
    }

    $user->update([
        'profile_photo' => null
    ]);

    return redirect()
        ->route('profile.index')
        ->with('success', 'Foto profil berhasil dihapus.');
}
}
