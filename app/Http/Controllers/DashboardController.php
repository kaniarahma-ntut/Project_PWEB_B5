<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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


// 5. Tren Peminjaman (6 Bulan Terakhir)
        $labelsBulan = [];
        $trenDipinjam = [];
        $trenDikembalikan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->locale('id')->subMonths($i);
            $labelsBulan[] = $date->translatedFormat('M');

            $trenDipinjam[] = Peminjaman::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->count();

            $trenDikembalikan[] = Peminjaman::whereNotNull('returned_at')
                                            ->whereMonth('returned_at', $date->month)
                                            ->whereYear('returned_at', $date->year)
                                            ->count();
        }

        // PASTIKAN BARIS DI BAWAH INI MEMILIKI 3 VARIABEL BARU TERSEBUT
        return view('admin.dashboard', compact(
            'totalBuku', 'terlambat', 'anggotaAktif', 'totalDenda',
            'tersedia', 'dipinjam', 'rusak',
            'laporanTerlambat', 'anggotaBaru', 'anggotaMeminjam', 'topPeminjam',
            'labelsBulan', 'trenDipinjam', 'trenDikembalikan' // <-- PASTIKAN INI ADA
        ));
    }


    public function indexPustakawan()
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


// 5. Tren Peminjaman (6 Bulan Terakhir)
        $labelsBulan = [];
        $trenDipinjam = [];
        $trenDikembalikan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->locale('id')->subMonths($i);
            $labelsBulan[] = $date->translatedFormat('M');

            $trenDipinjam[] = Peminjaman::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->count();

            $trenDikembalikan[] = Peminjaman::whereNotNull('returned_at')
                                            ->whereMonth('returned_at', $date->month)
                                            ->whereYear('returned_at', $date->year)
                                            ->count();
        }

        // PASTIKAN BARIS DI BAWAH INI MEMILIKI 3 VARIABEL BARU TERSEBUT
        return view('admin.dashboard', compact(
            'totalBuku', 'terlambat', 'anggotaAktif', 'totalDenda',
            'tersedia', 'dipinjam', 'rusak',
            'laporanTerlambat', 'anggotaBaru', 'anggotaMeminjam', 'topPeminjam',
            'labelsBulan', 'trenDipinjam', 'trenDikembalikan' // <-- PASTIKAN INI ADA
        ));
    }


public function indexAnggota()
    {
        $user = auth()->user();

        // 1. Ambil data buku yang sedang dipinjam (maksimal 3 untuk tampilan sekilas)
        $sedangDipinjam = Peminjaman::with('bookItem.book')
            ->where('user_id', $user->id)
            ->whereIn('status_peminjaman', [
                Peminjaman::STATUS_DIPINJAM,
                Peminjaman::STATUS_VALIDASI_PENGEMBALIAN
            ])
            ->latest()
            ->take(3)
            ->get();

        // 2. Cari buku paling populer (paling banyak dipinjam)
        $bukuPopulerIds = Peminjaman::join('book_items', 'peminjamans.book_item_id', '=', 'book_items.id')
            ->select('book_items.book_id', DB::raw('count(*) as total_pinjam'))
            ->groupBy('book_items.book_id')
            ->orderByDesc('total_pinjam')
            ->take(4)
            ->pluck('book_id');

        // Jika sudah ada data peminjaman, tampilkan buku populer
        if ($bukuPopulerIds->isNotEmpty()) {

            // UBAH BAGIAN INI: Menggunakan sortBy dari Collection Laravel
            // agar kompatibel 100% dengan PostgreSQL
            $rekomendasi = Book::whereIn('id', $bukuPopulerIds)
                               ->get()
                               ->sortBy(function($book) use ($bukuPopulerIds) {
                                   return array_search($book->id, $bukuPopulerIds->toArray());
                               })
                               ->values(); // Reset array index setelah diurutkan

        } else {
            // Fallback: Jika database masih sepi/belum ada riwayat peminjaman, tampilkan buku terbaru
            $rekomendasi = Book::latest()->take(4)->get();
        }

        return view('anggota.dashboard', compact('sedangDipinjam', 'rekomendasi'));
    }
}
