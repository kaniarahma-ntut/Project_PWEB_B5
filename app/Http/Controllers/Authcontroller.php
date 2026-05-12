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
     * Scopes 'email' dan 'profile' sudah cukup untuk kebutuhan sistem ini.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    // =========================================================================
    // CALLBACK — Terima data dari Google & proses login/register
    // =========================================================================

    /**
     * GET /auth/google/callback
     *
     * Alur:
     * 1. Ambil data user dari Google.
     * 2. Cari user di DB berdasarkan google_id atau email.
     * 3. Jika belum ada → buat akun baru sebagai 'anggota'.
     * 4. Jika sudah ada namun google_id belum tersimpan → update google_id.
     * 5. Cek apakah akun dinonaktifkan (soft-deleted) → tolak login.
     * 6. Login user dan redirect ke dashboard.
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

        // Cari berdasarkan google_id terlebih dahulu (paling akurat)
        // Kemudian fallback ke email (untuk akun yang sudah ada sebelumnya)
        $user = User::withTrashed()
                    ->where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        // Tolak login jika akun sudah dinonaktifkan oleh admin
        if ($user && $user->trashed()) {
            return redirect()
                ->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        if ($user) {
            // Akun sudah ada: perbarui google_id jika belum tersimpan
            // (kasus: user pernah didaftarkan manual oleh admin)
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Update foto profil dari Google jika user belum punya foto
            if (! $user->foto_profil && $googleUser->getAvatar()) {
                $user->update(['foto_profil' => $googleUser->getAvatar()]);
            }
        } else {
            // Akun belum ada → daftarkan sebagai anggota baru secara otomatis
            $user = User::create([
                'google_id'    => $googleUser->getId(),
                'email'        => $googleUser->getEmail(),
                'nama_lengkap' => $googleUser->getName(),
                'foto_profil'  => $googleUser->getAvatar(),
                'role'         => User::ROLE_ANGGOTA, // default role untuk pendaftar baru
                'password'     => null, // tidak butuh password karena login via Google
            ]);
        }

        // Login user (tanpa "remember me" — session biasa)
        Auth::login($user);

        // Arahkan ke dashboard sesuai role
        return redirect()->intended($this->redirectBasedOnRole($user));
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
            default               => route('dashboard'), // anggota
        };
    }
}
