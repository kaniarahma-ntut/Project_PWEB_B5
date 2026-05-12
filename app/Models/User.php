<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // =========================================================================
    // KONFIGURASI MODEL
    // =========================================================================

    /**
     * Nama tabel menggunakan default Laravel ('users').
     * Tidak perlu deklarasi $table.
     */

    /**
     * Kolom-kolom yang boleh diisi secara massal (mass assignment).
     * Termasuk role dan google_id untuk OAuth.
     */
    protected $fillable = [
        'role',
        'email',
        'nama_lengkap',
        'foto_profil',
        'alamat',
        'kecamatan',
        'no_hp',
        'google_id',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (API response / toArray).
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'deleted_at'        => 'datetime',
        ];
    }

    // =========================================================================
    // KONSTANTA ROLE — hindari magic string di seluruh codebase
    // =========================================================================

    const ROLE_ADMIN      = 'admin';
    const ROLE_PUSTAKAWAN = 'pustakawan';
    const ROLE_ANGGOTA    = 'anggota';

    // =========================================================================
    // HELPER METHOD — cek role secara ekspresif
    // =========================================================================

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPustakawan(): bool
    {
        return $this->role === self::ROLE_PUSTAKAWAN;
    }

    public function isAnggota(): bool
    {
        return $this->role === self::ROLE_ANGGOTA;
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Satu user bisa memiliki banyak transaksi peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Satu user bisa memiliki banyak entri wishlist.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
