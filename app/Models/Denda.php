<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    // =========================================================================
    // KONFIGURASI MODEL
    // =========================================================================

    protected $table = 'dendas';

    protected $fillable = [
        'peminjaman_id',
        'status_pembayaran',
        'id_payment',
        'jumlah_denda',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'jumlah_denda' => 'integer',
    ];

    // =========================================================================
    // KONSTANTA
    // =========================================================================

    const STATUS_BELUM = 'belum';
    const STATUS_SUDAH = 'sudah';

    const DENDA_PER_HARI = 1000; // Rp 1.000 per hari keterlambatan

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Denda ini terkait dengan satu peminjaman.
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // =========================================================================
    // ACCESSOR & MUTATOR
    // =========================================================================

    /**
     * Format jumlah denda dalam Rupiah.
     */
    public function getJumlahDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_denda, 0, ',', '.');
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Cek apakah denda sudah dibayar.
     */
    public function isBelum(): bool
    {
        return $this->status_pembayaran === self::STATUS_BELUM;
    }

    /**
     * Cek apakah denda sudah dibayar.
     */
    public function isSudah(): bool
    {
        return $this->status_pembayaran === self::STATUS_SUDAH;
    }

    /**
     * Tandai denda sebagai sudah dibayar.
     */
    public function markAsPaid(?string $paymentId = null): void
    {
        $this->update([
            'status_pembayaran' => self::STATUS_SUDAH,
            'id_payment' => $paymentId ?? 'MANUAL-' . now()->timestamp,
            'paid_at' => now(),
        ]);
    }

    // =========================================================================
    // STATIC HELPER — Hitung denda berdasarkan hari keterlambatan
    // =========================================================================

    /**
     * Hitung jumlah denda berdasarkan hari terlambat.
     *
     * @param int $hariTerlambat Jumlah hari keterlambatan
     * @return int Jumlah denda dalam Rupiah
     */
    public static function hitungDenda(int $hariTerlambat): int
    {
        return $hariTerlambat * self::DENDA_PER_HARI;
    }
}
