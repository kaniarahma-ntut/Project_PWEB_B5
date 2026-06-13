<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DendaController extends Controller
{
    // =========================================================================
    // INDEX — Melihat daftar denda
    // =========================================================================

    /**
     * GET /dendas
     *
     * Admin & Pustakawan: melihat semua denda.
     * Anggota: hanya melihat denda miliknya sendiri.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Denda::with(['peminjaman.user', 'peminjaman.bookItem.book']);

        // Anggota hanya bisa melihat denda miliknya
        if ($user->isAnggota()) {
            $query->whereHas('peminjaman', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Filter status pembayaran
        if ($status = $request->input('status')) {
            $query->where('status_pembayaran', $status);
        }

        // Search berdasarkan nama peminjam atau judul buku
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('peminjaman.user', function ($userQuery) use ($search) {
                    $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('peminjaman.bookItem.book', function ($bookQuery) use ($search) {
                    $bookQuery->where('judul', 'like', "%{$search}%");
                });
            });
        }

        $dendas = $query->latest()->paginate(20)->withQueryString();

        return view('dendas.index', compact('dendas'));
    }

    // =========================================================================
    // SHOW — Detail denda
    // =========================================================================

    /**
     * GET /dendas/{denda}
     */
    public function show(Request $request, Denda $denda): View
    {
        $user = $request->user();

        // Anggota hanya bisa melihat denda miliknya
        if ($user->isAnggota() && $denda->peminjaman->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat denda ini.');
        }

        $denda->load(['peminjaman.user', 'peminjaman.bookItem.book']);

        return view('dendas.show', compact('denda'));
    }

    // =========================================================================
    // PAY — Anggota membayar denda (simulasi pembayaran)
    // =========================================================================

    /**
     * POST /dendas/{denda}/pay
     *
     * Simulasi pembayaran denda. Dalam production, ini akan terintegrasi
     * dengan payment gateway seperti Midtrans.
     */
    public function pay(Request $request, Denda $denda): RedirectResponse
    {
        $user = $request->user();

        // Pastikan hanya anggota pemilik denda yang bisa bayar
        if (!$user->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat membayar denda.');
        }

        if ($denda->peminjaman->user_id !== $user->id) {
            abort(403, 'Anda hanya dapat membayar denda milik Anda sendiri.');
        }

        // Cek apakah sudah dibayar
        if ($denda->isSudah()) {
            return back()->with('info', 'Denda ini sudah dibayar sebelumnya.');
        }

        // Simulasi pembayaran berhasil
        $denda->markAsPaid();

        return redirect()
            ->route('dendas.show', $denda)
            ->with('success', 'Pembayaran denda berhasil! Terima kasih.');
    }

    // =========================================================================
    // UPDATE STATUS — Admin/Pustakawan update status pembayaran manual
    // =========================================================================

    /**
     * PATCH /dendas/{denda}/status
     *
     * Admin/Pustakawan bisa update status pembayaran manual
     * (misalnya jika anggota bayar tunai di perpustakaan).
     */
    public function updateStatus(Request $request, Denda $denda): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat mengubah status denda.');
        }

        $data = $request->validate([
            'status_pembayaran' => ['required', 'in:belum,sudah'],
            'id_payment' => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['status_pembayaran'] === Denda::STATUS_SUDAH && $denda->isBelum()) {
            $denda->markAsPaid($data['id_payment'] ?? null);
        } elseif ($data['status_pembayaran'] === Denda::STATUS_BELUM && $denda->isSudah()) {
            // Batalkan pembayaran (kasus refund atau error)
            $denda->update([
                'status_pembayaran' => Denda::STATUS_BELUM,
                'paid_at' => null,
            ]);
        }

        return back()->with('success', 'Status pembayaran denda berhasil diperbarui.');
    }

    // =========================================================================
    // GENERATE — Auto-generate denda untuk peminjaman terlambat
    // =========================================================================

    /**
     * POST /dendas/generate/{peminjaman}
     *
     * Generate denda untuk peminjaman yang terlambat.
     * Biasanya dipanggil otomatis saat approve return, tapi bisa juga manual.
     */
    public function generate(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat generate denda.');
        }

        // Cek apakah peminjaman terlambat
        if (!$peminjaman->isTerlambat()) {
            return back()->with('error', 'Peminjaman ini tidak terlambat.');
        }

        // Cek apakah denda sudah ada
        if ($peminjaman->denda) {
            return back()->with('info', 'Denda untuk peminjaman ini sudah ada.');
        }

        // Hitung denda
        $hariTerlambat = $peminjaman->getHariTerlambat();
        $jumlahDenda = Denda::hitungDenda($hariTerlambat);

        // Buat denda
        Denda::create([
            'peminjaman_id' => $peminjaman->id,
            'jumlah_denda' => $jumlahDenda,
            'status_pembayaran' => Denda::STATUS_BELUM,
        ]);

        return back()->with('success', "Denda berhasil dibuat. Total: Rp " . number_format($jumlahDenda, 0, ',', '.'));
    }
}
