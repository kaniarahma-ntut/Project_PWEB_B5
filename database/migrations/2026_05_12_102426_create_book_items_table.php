<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel book_items: mewakili eksemplar fisik dari sebuah buku.
     * Contoh: buku "Laravel for Beginners" bisa punya 3 eksemplar fisik
     *         masing-masing dengan kode_buku & kode_qr unik.
     *
     * Relasi: book_items BELONGS TO books (many-to-one)
     *         book_items HAS MANY peminjamans (one-to-many)
     *
     * Tidak menggunakan SoftDeletes karena status fisik buku ditangani
     * lewat kolom status_ketersediaan (Rusak/Hilang).
     */
    public function up(): void
    {
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();

            // FK ke tabel books — jika buku induk dihapus (soft), eksemplar tetap ada
            $table->foreignId('book_id')
                  ->constrained('books')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // Jangan hapus book jika masih ada eksemplar aktif

            // Kode unik per eksemplar fisik (contoh: "BK-001", "BK-002")
            $table->string('kode_buku')->unique();

            // QR code disimpan sebagai string (bisa berupa URL atau data encoded)
            $table->string('kode_qr')->unique()->nullable();

            // Status fisik eksemplar buku saat ini
            $table->enum('status_ketersediaan', [
                'Tersedia',
                'Dipinjam',
                'Rusak',
                'Hilang',
            ])->default('Tersedia');

            // SoftDeletes: eksemplar yang dihapus tetap tercatat untuk audit trail
            // riwayat peminjaman tidak akan orphan
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_items');
    }
};
