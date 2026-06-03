<?php

namespace App\Http\Controllers;

use App\Models\BookItem;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    // =========================================================================
    // INDEX — Melihat data peminjaman
    // =========================================================================

    /**
     * GET /peminjamans
     *
     * Admin & Pustakawan: melihat semua data peminjaman.
     * Anggota: hanya melihat data peminjaman miliknya sendiri.
     */
    public function index(Request $request): View
    {
        $authUser = $request->user();

        $query = Peminjaman::with([
            'user',
            'bookItem.book',
        ]);

        // Anggota hanya boleh melihat riwayat peminjamannya sendiri
        if ($authUser->isAnggota()) {
            $query->where('user_id', $authUser->id);
        }

        // Filter status peminjaman
        if ($status = $request->input('status_peminjaman')) {
            $query->where('status_peminjaman', $status);
        }

        // Search berdasarkan nama peminjam, email, judul buku, atau kode buku
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('bookItem', function ($itemQuery) use ($search) {
                    $itemQuery->where('kode_buku', 'like', "%{$search}%")
                              ->orWhereHas('book', function ($bookQuery) use ($search) {
                                  $bookQuery->where('judul', 'like', "%{$search}%");
                              });
                });
            });
        }

        $peminjamans = $query->latest()->paginate(20)->withQueryString();

        return view('peminjamans.index', compact('peminjamans'));
    }

    // =========================================================================
    // SHOW — Melihat detail peminjaman
    // =========================================================================

    /**
     * GET /peminjamans/{peminjaman}
     */
    public function show(Request $request, Peminjaman $peminjaman): View
    {
        $authUser = $request->user();

        // Anggota hanya boleh melihat detail peminjamannya sendiri
        if ($authUser->isAnggota() && $peminjaman->user_id !== $authUser->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data peminjaman ini.');
        }

        $peminjaman->load([
            'user',
            'bookItem.book',
        ]);

        return view('peminjamans.show', compact('peminjaman'));
    }

    // =========================================================================
    // STORE — Anggota melakukan peminjaman
    // =========================================================================

    /**
     * POST /peminjamans
     *
     * Alur:
     * 1. Anggota memilih eksemplar buku yang tersedia.
     * 2. Sistem membuat data peminjaman dengan status "dipinjam".
     * 3. Status book_item berubah menjadi "Dipinjam".
     */
    public function store(Request $request): RedirectResponse
    {
        $authUser = $request->user();

        if (! $authUser->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat melakukan peminjaman buku.');
        }

        $data = $request->validate([
            'book_item_id' => ['required', 'exists:book_items,id'],
        ]);

        $peminjaman = DB::transaction(function () use ($authUser, $data) {
            $bookItem = BookItem::lockForUpdate()->findOrFail($data['book_item_id']);

            if (! $bookItem->isTersedia()) {
                abort(422, 'Buku ini tidak tersedia untuk dipinjam.');
            }

            $peminjaman = Peminjaman::create([
                'user_id'             => $authUser->id,
                'book_item_id'        => $bookItem->id,
                'status_peminjaman'   => Peminjaman::STATUS_DIPINJAM,
                'due_at'              => now()->addDays(7),
                'requested_return_at' => null,
                'returned_at'         => null,
            ]);

            $bookItem->update([
                'status_ketersediaan' => BookItem::STATUS_DIPINJAM,
            ]);

            return $peminjaman;
        });

        return redirect()
            ->route('peminjamans.show', $peminjaman)
            ->with('success', 'Peminjaman buku berhasil dibuat.');
    }

    // =========================================================================
    // REQUEST RETURN — Anggota mengajukan pengembalian
    // =========================================================================

    /**
     * PATCH /peminjamans/{peminjaman}/request-return
     *
     * Alur:
     * 1. Anggota klik ajukan pengembalian.
     * 2. Status berubah dari "dipinjam" menjadi "validasi pengembalian".
     * 3. Admin/Pustakawan nanti memvalidasi buku fisiknya.
     */
    public function requestReturn(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $authUser = $request->user();

        if (! $authUser->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat mengajukan pengembalian.');
        }

        if ($peminjaman->user_id !== $authUser->id) {
            abort(403, 'Anda hanya dapat mengajukan pengembalian untuk peminjaman milik Anda sendiri.');
        }

        if (! $peminjaman->isDipinjam()) {
            return back()->with('error', 'Peminjaman ini tidak dapat diajukan untuk pengembalian.');
        }

        $peminjaman->update([
            'status_peminjaman'   => Peminjaman::STATUS_VALIDASI_PENGEMBALIAN,
            'requested_return_at' => now(),
        ]);

        return redirect()
            ->route('peminjamans.show', $peminjaman)
            ->with('success', 'Pengajuan pengembalian berhasil dikirim. Menunggu validasi admin/pustakawan.');
    }

    // =========================================================================
    // APPROVE RETURN — Admin/Pustakawan validasi pengembalian
    // =========================================================================

    /**
     * PATCH /peminjamans/{peminjaman}/approve-return
     *
     * Alur:
     * 1. Admin/Pustakawan memvalidasi buku sudah dikembalikan.
     * 2. Status peminjaman berubah menjadi "dikembalikan".
     * 3. Status book_item berubah menjadi "Tersedia".
     */
    public function approveReturn(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $authUser = $request->user();

        if (! $authUser->isAdmin() && ! $authUser->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat memvalidasi pengembalian.');
        }

        if (! $peminjaman->isValidasiPengembalian()) {
            return back()->with('error', 'Peminjaman ini belum berada pada tahap validasi pengembalian.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'status_peminjaman' => Peminjaman::STATUS_DIKEMBALIKAN,
                'returned_at'       => now(),
            ]);

            $peminjaman->bookItem->update([
                'status_ketersediaan' => BookItem::STATUS_TERSEDIA,
            ]);
        });

        return redirect()
            ->route('peminjamans.show', $peminjaman)
            ->with('success', 'Pengembalian buku berhasil divalidasi.');
    }

    // =========================================================================
    // UPDATE STATUS — Admin/Pustakawan mengubah status peminjaman secara manual
    // =========================================================================

    /**
     * PATCH /peminjamans/{peminjaman}/status
     *
     * Dipakai jika admin/pustakawan perlu mengubah status peminjaman secara manual.
     */
    public function updateStatus(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $authUser = $request->user();

        if (! $authUser->isAdmin() && ! $authUser->isPustakawan()) {
            abort(403, 'Hanya admin atau pustakawan yang dapat mengubah status peminjaman.');
        }

        $data = $request->validate([
            'status_peminjaman' => [
                'required',
                Rule::in([
                    Peminjaman::STATUS_DIPINJAM,
                    Peminjaman::STATUS_VALIDASI_PENGEMBALIAN,
                    Peminjaman::STATUS_DIKEMBALIKAN,
                ]),
            ],
        ]);

        DB::transaction(function () use ($peminjaman, $data) {
            $status = $data['status_peminjaman'];

            $updateData = [
                'status_peminjaman' => $status,
            ];

            if ($status === Peminjaman::STATUS_VALIDASI_PENGEMBALIAN) {
                $updateData['requested_return_at'] = now();
            }

            if ($status === Peminjaman::STATUS_DIKEMBALIKAN) {
                $updateData['returned_at'] = now();
            }

            $peminjaman->update($updateData);

            if ($status === Peminjaman::STATUS_DIKEMBALIKAN) {
                $peminjaman->bookItem->update([
                    'status_ketersediaan' => BookItem::STATUS_TERSEDIA,
                ]);
            } else {
                $peminjaman->bookItem->update([
                    'status_ketersediaan' => BookItem::STATUS_DIPINJAM,
                ]);
            }
        });

        return redirect()
            ->route('peminjamans.show', $peminjaman)
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}