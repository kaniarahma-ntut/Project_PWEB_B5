<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // =========================================================================
    // REDIRECT — Kirim user ke halaman login Google
    // =========================================================================

    /**
     * GET /auth/google
     *
     * Mengarahkan browser ke halaman consent Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    // =========================================================================
    // CALLBACK — Proses data dari Google
    // =========================================================================

    /**
     * GET /auth/google/callback
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }

        // Cari berdasarkan google_id terlebih dahulu, lalu fallback ke email
        $user = User::withTrashed()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // Jika akun sudah ada
        if ($user) {
            // Tolak login jika akun dinonaktifkan oleh admin
            if ($user->trashed()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            }

            // Lengkapi google_id jika akun lama belum punya google_id
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            // Update foto profil dari Google jika user belum punya foto
            if (! $user->foto_profil && $googleUser->getAvatar()) {
                $user->update([
                    'foto_profil' => $googleUser->getAvatar(),
                ]);
            }

            // Pengaman untuk akun lama: jika status_verifikasi kosong, anggap sudah approved
            if (! $user->status_verifikasi) {
                $user->update([
                    'status_verifikasi' => User::STATUS_APPROVED,
                ]);
            }

            // Tolak login jika akun masih menunggu verifikasi admin
            if ($user->isPending()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Akun Anda masih menunggu verifikasi admin.');
            }

            // Tolak login jika akun ditolak admin
            if ($user->isRejected()) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Pendaftaran akun Anda ditolak oleh admin.');
            }

            // Login user jika statusnya approved
            Auth::login($user);

            return redirect()->intended($this->redirectBasedOnRole($user));
        }

        // Jika akun belum ada, buat akun baru sebagai anggota dengan status pending
        User::create([
            'google_id'         => $googleUser->getId(),
            'email'             => $googleUser->getEmail(),
            'nama_lengkap'      => $googleUser->getName(),
            'foto_profil'       => $googleUser->getAvatar(),
            'role'              => User::ROLE_ANGGOTA,
            'status_verifikasi' => User::STATUS_PENDING,
            'password'          => null,
        ]);

        // Akun baru tidak langsung login, harus menunggu persetujuan admin
        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran berhasil. Akun Anda sedang menunggu verifikasi admin.');
    }

    // =========================================================================
    // LOGOUT
    // =========================================================================

    /**
     * POST /logout
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }

    // =========================================================================
    // HELPER — Tentukan halaman tujuan setelah login berdasarkan role
    // =========================================================================

    private function redirectBasedOnRole(User $user): string
    {
        return match ($user->role) {
            User::ROLE_ADMIN      => route('admin.dashboard'),
            User::ROLE_PUSTAKAWAN => route('pustakawan.dashboard'),
            default               => route('dashboard'),
        };
    }
}