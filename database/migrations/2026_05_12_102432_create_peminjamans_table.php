<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel peminjamans: mencatat transaksi peminjaman buku.
     *
     * Alur status:
     *   dipinjam → validasi pengembalian → dikembalikan
     *
     * Anggota mengajukan pengembalian → status jadi "validasi pengembalian"
     * Pustakawan/Admin mengkonfirmasi → status jadi "dikembalikan"
     * Jika melebihi due_at saat dikembalikan → otomatis generate denda
     *
     * Relasi:
     *   peminjamans BELONGS TO users (many-to-one)
     *   peminjamans BELONGS TO book_items (many-to-one)
     *   peminjamans HAS ONE denda (one-to-one)
     *
     * Tidak menggunakan timestamps() standar karena:
     *   - created_at dipakai sebagai tgl_pinjam (alias semantik)
     *   - updated_at tetap ada untuk tracking perubahan status
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();

            // FK ke anggota yang meminjam
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // Jangan hapus user jika punya riwayat pinjam

            // FK ke eksemplar fisik yang dipinjam
            $table->foreignId('book_item_id')
                  ->constrained('book_items')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // Jangan hapus book_item yang sedang/pernah dipinjam

            // Status alur peminjaman
            $table->enum('status_peminjaman', [
                'dipinjam',
                'validasi pengembalian',
                'dikembalikan',
            ])->default('dipinjam');

            // created_at dari timestamps() berfungsi sebagai tgl_pinjam
            // due_at: tenggat waktu pengembalian (biasanya 7 atau 14 hari dari pinjam)
            $table->timestamp('due_at');

            // Diisi saat anggota mengajukan pengembalian (trigger status → validasi pengembalian)
            $table->timestamp('requested_return_at')->nullable();

            // Diisi saat pustakawan/admin mengkonfirmasi pengembalian fisik buku
            $table->timestamp('returned_at')->nullable();

            // created_at = tgl_pinjam, updated_at = terakhir status berubah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
