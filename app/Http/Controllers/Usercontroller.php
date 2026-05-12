<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    // =========================================================================
    // INDEX — Daftar akun (Admin only)
    // =========================================================================

    /**
     * GET /users
     * Hanya Admin yang bisa melihat daftar semua akun.
     */
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $query = User::query();

        // Filter berdasarkan role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter aktif / nonaktif
        if ($request->input('tampilkan') === 'nonaktif') {
            $query->onlyTrashed();
        } elseif ($request->input('tampilkan') === 'semua') {
            $query->withTrashed();
        }

        // Search nama atau email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    // =========================================================================
    // SHOW — Lihat profil akun
    // =========================================================================

    /**
     * GET /users/{user}
     * Admin: bisa lihat siapa saja.
     * Pustakawan & Anggota: hanya bisa lihat profil sendiri.
     */
    public function show(Request $request, int $id): View
    {
        $authUser = $request->user();

        // Non-admin hanya boleh lihat profil sendiri
        if (! $authUser->isAdmin() && $authUser->id !== $id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        // Admin bisa lihat akun yang sudah dinonaktifkan sekalipun
        $user = $authUser->isAdmin()
            ? User::withTrashed()->findOrFail($id)
            : User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    // =========================================================================
    // CREATE + STORE — Buat akun baru (Admin only)
    // =========================================================================

    /**
     * GET /users/create
     */
    public function create(): View
    {
        $this->authorizeAdmin();

        return view('users.create');
    }

    /**
     * POST /users
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Hash password sebelum disimpan
        $data['password'] = Hash::make($data['password']);

        // Upload foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('profiles', 'public');
        }

        $user = User::create($data);

        return redirect()
            ->route('users.show', $user)
            ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil dibuat.");
    }

    // =========================================================================
    // EDIT + UPDATE — Ubah data akun
    // =========================================================================

    /**
     * GET /users/{user}/edit
     * Admin: semua akun. Pustakawan/Anggota: hanya diri sendiri.
     */
    public function edit(Request $request, int $id): View
    {
        $authUser = $request->user();

        if (! $authUser->isAdmin() && $authUser->id !== $id) {
            abort(403, 'Anda hanya dapat mengubah data akun Anda sendiri.');
        }

        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * PUT/PATCH /users/{user}
     */
    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $authUser = $request->user();

        if (! $authUser->isAdmin() && $authUser->id !== $id) {
            abort(403, 'Anda hanya dapat mengubah data akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $data = $request->validated();

        // Hanya admin yang boleh mengubah role
        if (! $authUser->isAdmin()) {
            unset($data['role']);
        }

        // Hash password baru jika diisi
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Jangan update password jika kosong
        }

        // Ganti foto profil jika ada file baru
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    // =========================================================================
    // DESTROY — Nonaktifkan akun (Soft Delete) — Admin only
    // =========================================================================

    /**
     * DELETE /users/{user}
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorizeAdmin();

        $user    = User::findOrFail($id);
        $authUser = request()->user();

        // Admin tidak bisa menonaktifkan dirinya sendiri
        if ($authUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->delete(); // SoftDelete

        return redirect()
            ->route('users.index')
            ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil dinonaktifkan.");
    }

    // =========================================================================
    // RESTORE — Pulihkan akun yang dinonaktifkan — Admin only
    // =========================================================================

    /**
     * PATCH /users/{user}/restore
     */
    public function restore(int $id): RedirectResponse
    {
        $this->authorizeAdmin();

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()
            ->route('users.index')
            ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil dipulihkan.");
    }

    // =========================================================================
    // HELPER PRIVATE
    // =========================================================================

    /**
     * Abort 403 jika bukan admin.
     * Dipakai di method yang khusus admin agar tidak repetitif.
     */
    private function authorizeAdmin(): void
    {
        if (! request()->user()?->isAdmin()) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Admin.');
        }
    }
}
