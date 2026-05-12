<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel wishlists: daftar buku yang ingin dipinjam oleh anggota di masa mendatang.
     *
     * Relasi:
     *   wishlists BELONGS TO users (many-to-one)
     *   wishlists BELONGS TO books (many-to-one)
     *
     * Constraint UNIQUE pada (user_id, book_id) mencegah duplikasi wishlist
     * untuk kombinasi user + buku yang sama.
     *
     * Hanya menyimpan created_at (tidak butuh updated_at karena wishlist
     * tidak pernah diupdate, hanya dibuat atau dihapus).
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete(); // Hapus wishlist jika user dihapus permanen

            $table->foreignId('book_id')
                  ->constrained('books')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete(); // Hapus wishlist jika buku dihapus permanen

            // Satu user hanya bisa wishlist satu buku satu kali
            $table->unique(['user_id', 'book_id']);

            // Hanya butuh created_at, tidak perlu updated_at
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
