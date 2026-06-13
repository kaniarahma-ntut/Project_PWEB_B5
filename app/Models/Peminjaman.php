<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    use HasFactory;

    // =========================================================================
    // KONFIGURASI MODEL
    // =========================================================================

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'book_item_id',
        'status_peminjaman',
        'due_at',
        'requested_return_at',
        'returned_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'requested_return_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // =========================================================================
    // KONSTANTA STATUS PEMINJAMAN
    // =========================================================================

    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_VALIDASI_PENGEMBALIAN = 'validasi pengembalian';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Setiap peminjaman dimiliki oleh satu user/anggota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Setiap peminjaman berhubungan dengan satu eksemplar buku.
     */
    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class);
    }

    /**
     * Setiap peminjaman bisa memiliki satu denda (jika terlambat).
     */
    public function denda(): HasOne
    {
        return $this->hasOne(Denda::class);
    }

    // =========================================================================
    // HELPER METHOD
    // =========================================================================

    public function isDipinjam(): bool
    {
        return $this->status_peminjaman === self::STATUS_DIPINJAM;
    }

    public function isValidasiPengembalian(): bool
    {
        return $this->status_peminjaman === self::STATUS_VALIDASI_PENGEMBALIAN;
    }

    public function isDikembalikan(): bool
    {
        return $this->status_peminjaman === self::STATUS_DIKEMBALIKAN;
    }

    /**
     * Cek apakah peminjaman terlambat.
     */
    public function isTerlambat(): bool
    {
        // Jika sudah dikembalikan, cek apakah returned_at > due_at
        if ($this->returned_at) {
            return $this->returned_at->greaterThan($this->due_at);
        }

        // Jika belum dikembalikan, cek apakah sekarang sudah lewat due_at
        return now()->greaterThan($this->due_at) && !$this->returned_at;
    }

    /**
     * Hitung jumlah hari keterlambatan.
     */
    public function getHariTerlambat(): int
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        $tanggalKembali = $this->returned_at ?? now();
        return (int) $this->due_at->diffInDays($tanggalKembali, false);
    }
}
