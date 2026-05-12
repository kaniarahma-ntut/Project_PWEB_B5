<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel dendas: mencatat denda keterlambatan pengembalian buku.
     *
     * Denda dibuat otomatis (via Observer atau Event) saat peminjaman
     * dikembalikan SETELAH melewati due_at.
     *
     * Alur status pembayaran:
     *   (denda dibuat) → belum → sudah
     *
     * id_payment diisi setelah transaksi sukses dari payment gateway (Midtrans, dll).
     *
     * Relasi:
     *   dendas BELONGS TO peminjamans (one-to-one)
     *   Satu peminjaman hanya menghasilkan SATU denda (unique FK)
     */
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();

            // FK ke peminjaman yang terlambat — unique karena one-to-one
            $table->foreignId('peminjaman_id')
                  ->unique()
                  ->constrained('peminjamans')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // Jangan hapus peminjaman jika masih ada denda

            // Status apakah denda sudah dibayar
            $table->enum('status_pembayaran', ['belum', 'sudah'])->default('belum');

            // ID transaksi dari payment gateway (Midtrans order_id / transaction_id)
            // Nullable karena belum ada saat denda pertama kali dibuat
            $table->string('id_payment')->nullable()->unique();

            // Total denda dalam Rupiah (contoh: Rp 1.000/hari × jumlah hari telat)
            $table->unsignedBigInteger('jumlah_denda');

            // Diisi saat pembayaran berhasil dikonfirmasi
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
