<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
// use App\Models\Denda; // Uncomment jika Anda memiliki model Denda

class DashboardController extends Controller
{
    public function indexAdmin()
    {
        // 1. Stat Cards
        $totalBuku = Book::count();

        // Asumsi kolom 'due_at' menyimpan batas waktu dan 'returned_at' kosong jika belum dikembalikan
        $terlambat = Peminjaman::whereNull('returned_at')
                               ->where('due_at', '<', now())
                               ->count();

        // Asumsi Anda memiliki kolom 'role' pada tabel users
        $anggotaAktif = User::where('role', 'anggota')->count();

        // Jika ada tabel Denda, Anda bisa menjumlahkannya. Contoh: Denda::where('status', 'belum_lunas')->sum('nominal');
        $totalDenda = 0; // Sesuaikan dengan model Denda Anda

        // 2. Status Ketersediaan (Pie Chart)
        $tersedia = BookItem::where('status_ketersediaan', BookItem::STATUS_TERSEDIA)->count();
        $dipinjam = BookItem::where('status_ketersediaan', BookItem::STATUS_DIPINJAM)->count();
        $rusak = BookItem::where('status_ketersediaan', 'Rusak')->count(); // Sesuaikan dengan status rusak Anda

        // 3. Laporan Keterlambatan (Tabel)
        $laporanTerlambatRaw = Peminjaman::with(['user', 'bookItem.book'])
            ->whereNull('returned_at')
            ->where('due_at', '<', now())
            ->take(5)
            ->get();

        // Format data sesuai kebutuhan array di view (Bisa juga langsung loop object di blade)
        $laporanTerlambat = $laporanTerlambatRaw->map(function($p) {
            $hariTerlambat = now()->diffInDays($p->due_at);
            return [
                $p->user->nama_lengkap ?? 'Unknown',
                $p->bookItem->book->judul ?? 'Unknown',
                $hariTerlambat . ' hari',
                'belum' // Status denda (sesuaikan logika denda Anda)
            ];
        });

        // 4. Aktivitas Anggota
        $anggotaBaru = User::where('role', 'anggota')
                           ->whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->count();

        // Jumlah anggota unik yang saat ini sedang meminjam buku
        $anggotaMeminjam = Peminjaman::where('status_peminjaman', Peminjaman::STATUS_DIPINJAM)
                                     ->distinct('user_id')
                                     ->count();

        // Top Peminjam (User dengan peminjaman terbanyak)
        $topPeminjamRaw = Peminjaman::select('user_id', DB::raw('count(*) as total_pinjam'))
            ->groupBy('user_id')
            ->orderByDesc('total_pinjam')
            ->take(3)
            ->with('user')
            ->get();

        $topPeminjam = $topPeminjamRaw->map(function($p) {
            $nama = $p->user->nama_lengkap ?? 'Unknown';
            $inisial = strtoupper(substr($nama, 0, 2));
            return [$inisial, $nama, $p->total_pinjam];
        });

        return view('admin.dashboard', compact(
            'totalBuku', 'terlambat', 'anggotaAktif', 'totalDenda',
            'tersedia', 'dipinjam', 'rusak',
            'laporanTerlambat', 'anggotaBaru', 'anggotaMeminjam', 'topPeminjam'
        ));
    }

    public function indexPustakawan()
    {
        // Logika untuk pustakawan bisa disamakan atau disesuaikan
        // Untuk saat ini kita panggil data yang sama untuk view pustakawan
        $totalBuku = Book::count();
        $tersedia = BookItem::where('status_ketersediaan', BookItem::STATUS_TERSEDIA)->count();
        $dipinjam = BookItem::where('status_ketersediaan', BookItem::STATUS_DIPINJAM)->count();

        return view('pustakawan.dashboard', compact('totalBuku', 'tersedia', 'dipinjam'));
    }
}
