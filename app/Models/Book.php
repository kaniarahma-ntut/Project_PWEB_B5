<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    // =========================================================================
    // KONFIGURASI MODEL
    // =========================================================================

    protected $table = 'books';

    protected $fillable = [
        'judul',
        'ISBN',
        'penulis',
        'deskripsi',
        'kategori',
        'cover',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Satu judul buku memiliki banyak eksemplar fisik.
     * Contoh: "Clean Code" bisa punya 5 eksemplar (BK-001 s/d BK-005).
     */
    public function bookItems(): HasMany
    {
        return $this->hasMany(BookItem::class);
    }

    /**
     * Satu judul buku bisa di-wishlist oleh banyak user.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // =========================================================================
    // SCOPE — query builder shortcut
    // =========================================================================

    /**
     * Filter buku berdasarkan kata kunci (judul, penulis, atau kategori).
     * Penggunaan: Book::search('laravel')->get()
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('penulis', 'like', "%{$keyword}%")
              ->orWhere('kategori', 'like', "%{$keyword}%")
              ->orWhere('ISBN', 'like', "%{$keyword}%");
        });
    }

    /**
     * Filter hanya buku yang memiliki minimal 1 eksemplar tersedia.
     */
    public function scopeAvailable($query)
    {
        return $query->whereHas('bookItems', function ($q) {
            $q->where('status_ketersediaan', 'Tersedia');
        });
    }

    // =========================================================================
    // ACCESSOR
    // =========================================================================

    /**
     * Hitung jumlah eksemplar yang sedang tersedia.
     */
    public function getJumlahTersediaAttribute(): int
    {
        return $this->bookItems()->where('status_ketersediaan', 'Tersedia')->count();
    }

    /**
     * Hitung total eksemplar (semua status).
     */
    public function getTotalEksemplarAttribute(): int
    {
        return $this->bookItems()->count();
    }
}
