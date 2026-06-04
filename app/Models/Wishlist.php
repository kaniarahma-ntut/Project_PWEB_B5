<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    // Tentukan tabel jika nama tabelnya bukan bawaan plural (meskipun defaultnya 'wishlists')
    protected $table = 'wishlists';

    // Kolom apa saja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'book_id',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Data wishlist ini milik satu User tertentu.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Data wishlist ini merujuk ke satu Buku tertentu.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
