<?php
// app/Http/Controllers/Admin/AdminUserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // =========================================================
    // INDEX
    // =========================================================

    public function index(Request $request): View
    {
        $query = User::withCount('orders')
            ->search($request->search)
            ->filterRole($request->role)
            ->filterStatus($request->status)
            ->latest();

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total'          => User::count(),
            'admin'          => User::where('role', 'admin')->count(),
            'user'           => User::where('role', 'user')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    // =========================================================
    // CREATE
    // =========================================================

    public function create(): View
    {
        return view('admin.users.create');
    }

    // =========================================================
    // STORE
    // =========================================================

    public function store(
    Request $request,
    SupabaseStorageService $storage
): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Password::min(8)],
            'role'          => ['required', 'in:admin,user'],
            'is_active'     => ['boolean'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $profilePhoto = null;

if ($request->hasFile('profile_photo')) {
    $profilePhoto = $storage
        ->uploadProfilePhoto(
            $request->file('profile_photo')
        );
}

        User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'role'          => $validated['role'],
            'is_active'     => $request->boolean('is_active', true),
            'profile_photo' => $profilePhoto,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    // =========================================================
    // SHOW
    // =========================================================

    public function show(User $user): View
    {
        $user->loadCount('orders')
             ->load(['orders' => fn($q) => $q->latest()->take(5)]);

        $totalTransaksi = $user->orders()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total_price');

        return view('admin.users.show', compact('user', 'totalTransaksi'));
    }

    // =========================================================
    // EDIT
    // =========================================================

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    // =========================================================
    // UPDATE
    // =========================================================

    public function update(
    Request $request,
    User $user,
    SupabaseStorageService $storage
): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'      => ['nullable', 'confirmed', Password::min(8)],
            'role'          => ['required', 'in:admin,user'],
            'is_active'     => ['boolean'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $updateData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'is_active' => $request->boolean('is_active', $user->is_active),
        ];

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update profile_photo
        if ($request->hasFile('profile_photo')) {
            // Hapus foto profil lama jika ada
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

        $user->update($updateData);

        return redirect()
            ->route('admin.users.show', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // =========================================================
    // DESTROY
    // =========================================================

    public function destroy(
    User $user,
    SupabaseStorageService $storage
): RedirectResponse
    {
        // Admin tidak boleh menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus foto profil dari storage jika ada
        if ($user->profile_photo) {
            $storage->delete(
    env('SUPABASE_PROFILE_BUCKET'),
    $user->profile_photo
);
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna "' . $userName . '" berhasil dihapus.');
    }

    // =========================================================
    // TOGGLE STATUS
    // =========================================================

    public function toggleStatus(User $user): RedirectResponse
    {
        // Admin tidak boleh menonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $statusLabel = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Pengguna "' . $user->name . '" berhasil ' . $statusLabel . '.');
    }

    // =========================================================
    // RESET PASSWORD
    // =========================================================

    public function resetPassword(User $user): RedirectResponse
    {
        $newPassword = 'Password123!';
        $user->update(['password' => Hash::make($newPassword)]);

        Log::info('Password reset by admin', [
            'admin_id' => auth()->id(),
            'user_id'  => $user->id,
        ]);

        return back()->with('success', 'Password "' . $user->name . '" berhasil direset ke: ' . $newPassword);
    }
}