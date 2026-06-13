<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    // =========================================================================
    // EXPORT PEMINJAMAN
    // =========================================================================

    /**
     * GET /exports/peminjamans
     *
     * Export data peminjaman ke CSV.
     * Admin & Pustakawan only.
     */
    public function peminjamans(Request $request): Response
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat export data.');
        }

        // Query peminjaman dengan relasi
        $peminjamans = Peminjaman::with(['user', 'bookItem.book', 'denda'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare CSV
        $filename = 'laporan_peminjaman_' . now()->format('Y-m-d_His') . '.csv';

        $callback = function() use ($peminjamans) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM untuk Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'ID',
                'Nama Peminjam',
                'Email',
                'Judul Buku',
                'Kode Buku',
                'Tanggal Pinjam',
                'Jatuh Tempo',
                'Tanggal Kembali',
                'Status',
                'Keterlambatan (Hari)',
                'Denda (Rp)',
                'Status Denda',
            ]);

            // Data rows
            foreach ($peminjamans as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->user->nama_lengkap,
                    $p->user->email,
                    $p->bookItem->book->judul,
                    $p->bookItem->kode_buku,
                    $p->created_at->format('Y-m-d H:i'),
                    $p->due_at->format('Y-m-d'),
                    $p->returned_at ? $p->returned_at->format('Y-m-d H:i') : '-',
                    $p->status_peminjaman,
                    $p->isTerlambat() ? $p->getHariTerlambat() : 0,
                    $p->denda ? $p->denda->jumlah_denda : 0,
                    $p->denda ? $p->denda->status_pembayaran : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // =========================================================================
    // EXPORT BUKU
    // =========================================================================

    /**
     * GET /exports/books
     *
     * Export data buku ke CSV.
     * Admin & Pustakawan only.
     */
    public function books(Request $request): Response
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat export data.');
        }

        // Query buku dengan eksemplar
        $books = Book::withTrashed()
            ->withCount('bookItems')
            ->with('bookItems')
            ->orderBy('judul')
            ->get();

        // Prepare CSV
        $filename = 'data_buku_' . now()->format('Y-m-d_His') . '.csv';

        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'ID',
                'Judul',
                'Penulis',
                'ISBN',
                'Kategori',
                'Total Eksemplar',
                'Tersedia',
                'Dipinjam',
                'Status',
                'Tanggal Dibuat',
            ]);

            // Data rows
            foreach ($books as $book) {
                $tersedia = $book->bookItems->where('status_ketersediaan', 'Tersedia')->count();
                $dipinjam = $book->bookItems->where('status_ketersediaan', 'Dipinjam')->count();

                fputcsv($file, [
                    $book->id,
                    $book->judul,
                    $book->penulis,
                    $book->ISBN ?? '-',
                    $book->kategori,
                    $book->book_items_count,
                    $tersedia,
                    $dipinjam,
                    $book->trashed() ? 'Nonaktif' : 'Aktif',
                    $book->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // =========================================================================
    // EXPORT ANGGOTA
    // =========================================================================

    /**
     * GET /exports/users
     *
     * Export data anggota ke CSV.
     * Admin only.
     */
    public function users(Request $request): Response
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            abort(403, 'Hanya admin yang dapat export data anggota.');
        }

        // Query users dengan statistik peminjaman
        $users = User::withTrashed()
            ->withCount('peminjamans')
            ->orderBy('nama_lengkap')
            ->get();

        // Prepare CSV
        $filename = 'data_anggota_' . now()->format('Y-m-d_His') . '.csv';

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'ID',
                'Nama Lengkap',
                'Email',
                'Role',
                'No HP',
                'Alamat',
                'Kecamatan',
                'Total Peminjaman',
                'Status',
                'Tanggal Daftar',
            ]);

            // Data rows
            foreach ($users as $u) {
                fputcsv($file, [
                    $u->id,
                    $u->nama_lengkap,
                    $u->email,
                    $u->role,
                    $u->no_hp ?? '-',
                    $u->alamat ?? '-',
                    $u->kecamatan ?? '-',
                    $u->peminjamans_count,
                    $u->trashed() ? 'Nonaktif' : 'Aktif',
                    $u->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // =========================================================================
    // EXPORT DENDA
    // =========================================================================

    /**
     * GET /exports/dendas
     *
     * Export data denda ke CSV.
     * Admin & Pustakawan only.
     */
    public function dendas(Request $request): Response
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat export data.');
        }

        // Query denda dengan relasi
        $dendas = \App\Models\Denda::with(['peminjaman.user', 'peminjaman.bookItem.book'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare CSV
        $filename = 'laporan_denda_' . now()->format('Y-m-d_His') . '.csv';

        $callback = function() use ($dendas) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'ID Denda',
                'ID Peminjaman',
                'Nama Peminjam',
                'Email',
                'Judul Buku',
                'Kode Buku',
                'Jatuh Tempo',
                'Tanggal Kembali',
                'Keterlambatan (Hari)',
                'Jumlah Denda (Rp)',
                'Status Pembayaran',
                'ID Payment',
                'Tanggal Bayar',
            ]);

            // Data rows
            foreach ($dendas as $d) {
                fputcsv($file, [
                    $d->id,
                    $d->peminjaman_id,
                    $d->peminjaman->user->nama_lengkap,
                    $d->peminjaman->user->email,
                    $d->peminjaman->bookItem->book->judul,
                    $d->peminjaman->bookItem->kode_buku,
                    $d->peminjaman->due_at->format('Y-m-d'),
                    $d->peminjaman->returned_at ? $d->peminjaman->returned_at->format('Y-m-d') : '-',
                    $d->peminjaman->getHariTerlambat(),
                    $d->jumlah_denda,
                    $d->status_pembayaran,
                    $d->id_payment ?? '-',
                    $d->paid_at ? $d->paid_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
