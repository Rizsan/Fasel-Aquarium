<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Services\Supabase\SupabaseStorageService;

class AdminProfileController extends Controller
{
    // =========================================================
    // index() — Halaman Profile Admin
    // =========================================================
    public function index(): View
    {
        $user = Auth::user();

        // Quick stats untuk admin
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_orders'   => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'paid'])->count(),
            'total_revenue'  => Order::whereIn('status', ['paid', 'completed'])->sum('total_price'),
        ];

        return view('admin.profile.index', compact('user', 'stats'));
    }

    // =========================================================
    // update() — Update Profile Admin
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
            'current_password'  => ['nullable'],
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

    if ($user->profile_photo) {
        $storage->delete(
            env('SUPABASE_PROFILE_BUCKET'),
            $user->profile_photo
        );
    }

    $updateData['profile_photo'] = $storage
        ->uploadProfilePhoto(
            $request->file('profile_photo')
        );
}

        // --- Update Password (opsional) ---
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.profile.index')
            ->with('success', 'Profil admin berhasil diperbarui.');
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

        if ($user->profile_photo) {
            $storage->delete(
    env('SUPABASE_PROFILE_BUCKET'),
    $user->profile_photo
);
            $user->update(['profile_photo' => null]);
        }

        return redirect()
            ->route('admin.profile.index')
            ->with('success', 'Foto profil berhasil dihapus.');
    }
}
