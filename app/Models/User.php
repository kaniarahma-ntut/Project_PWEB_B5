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
     * Kolom-kolom yang boleh diisi secara massal.
     * Termasuk role, status_verifikasi, dan google_id untuk OAuth.
     */
    protected $fillable = [
        'role',
        'status_verifikasi',
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
     * Kolom yang disembunyikan saat serialisasi.
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
    // KONSTANTA ROLE
    // =========================================================================

    const ROLE_ADMIN      = 'admin';
    const ROLE_PUSTAKAWAN = 'pustakawan';
    const ROLE_ANGGOTA    = 'anggota';

    // =========================================================================
    // KONSTANTA STATUS VERIFIKASI AKUN
    // =========================================================================

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // =========================================================================
    // HELPER METHOD — CEK ROLE
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
    // HELPER METHOD — CEK STATUS VERIFIKASI
    // =========================================================================

    public function isPending(): bool
    {
        return $this->status_verifikasi === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status_verifikasi === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status_verifikasi === self::STATUS_REJECTED;
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