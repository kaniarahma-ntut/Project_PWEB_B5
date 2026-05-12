<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel books: menyimpan data bibliografis buku (master data judul).
     * Setiap buku bisa memiliki banyak eksemplar fisik di tabel book_items.
     * SoftDeletes memungkinkan buku dinonaktifkan tanpa kehilangan riwayat peminjaman.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('judul');

            // ISBN bisa nullable untuk buku lama yang tidak punya ISBN
            $table->string('ISBN', 20)->nullable()->unique();

            $table->string('penulis');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');

            // Path/URL cover buku, nullable jika belum diupload
            $table->string('cover')->nullable();

            // SoftDeletes: buku yang dinonaktifkan masih bisa dilihat di riwayat peminjaman
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
