<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookItem extends Model
{
    use HasFactory, SoftDeletes;

    // =========================================================================
    // KONFIGURASI MODEL
    // =========================================================================

    protected $table = 'book_items';

    protected $fillable = [
        'book_id',
        'kode_buku',
        'kode_qr',
        'status_ketersediaan',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // =========================================================================
    // KONSTANTA STATUS
    // =========================================================================

    const STATUS_TERSEDIA = 'Tersedia';
    const STATUS_DIPINJAM = 'Dipinjam';
    const STATUS_RUSAK    = 'Rusak';
    const STATUS_HILANG   = 'Hilang';

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Setiap eksemplar fisik adalah milik satu judul buku.
     * Relasi: BookItem BELONGS TO Book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Satu eksemplar bisa memiliki riwayat peminjaman lebih dari sekali
     * (setelah dikembalikan, bisa dipinjam lagi).
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Peminjaman aktif saat ini (status bukan 'dikembalikan').
     */
    public function peminjamansAktif(): HasMany
    {
        return $this->hasMany(Peminjaman::class)
                    ->whereIn('status_peminjaman', ['dipinjam', 'validasi pengembalian']);
    }

    // =========================================================================
    // HELPER METHOD
    // =========================================================================

    public function isTersedia(): bool
    {
        return $this->status_ketersediaan === self::STATUS_TERSEDIA;
    }

    public function isDipinjam(): bool
    {
        return $this->status_ketersediaan === self::STATUS_DIPINJAM;
    }
}
