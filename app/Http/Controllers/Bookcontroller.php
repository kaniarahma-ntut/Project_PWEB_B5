<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    // =========================================================================
    // INDEX — Tampilkan daftar buku (semua role)
    // =========================================================================

    /**
     * GET /books
     * - Admin & Pustakawan: melihat semua buku termasuk yang soft-deleted (via tab/filter)
     * - Anggota: hanya melihat buku aktif, bisa search
     */
    public function index(Request $request): View
    {
        $user  = $request->user();
        $query = Book::with('bookItems'); // eager load eksemplar sekaligus

        // Fitur Search — tersedia untuk semua role
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filter kategori
        if ($kategori = $request->input('kategori')) {
            $query->where('kategori', $kategori);
        }

        // Admin & Pustakawan: bisa filter untuk melihat buku yang dinonaktifkan
        if ($user->isAdmin() || $user->isPustakawan()) {
            if ($request->input('tampilkan') === 'nonaktif') {
                $query->onlyTrashed(); // hanya yang soft-deleted
            } elseif ($request->input('tampilkan') === 'semua') {
                $query->withTrashed(); // aktif + non-aktif
            }
            // default: hanya aktif (tanpa modifier)
        }
        // Anggota hanya melihat buku aktif (query default sudah exclude soft-deleted)

        $books      = $query->latest()->paginate(12)->withQueryString();
        $kategoris  = Book::distinct()->pluck('kategori'); // untuk dropdown filter

        return view('books.index', compact('books', 'kategoris'));
    }

    // =========================================================================
    // SHOW — Detail satu buku
    // =========================================================================

    /**
     * GET /books/{book}
     */
    public function show($id): View
    {
        $book = Book::withTrashed()->findOrFail($id);
        // Load eksemplar dan riwayat peminjaman aktif
        $book->load(['bookItems', 'wishlists']);

        return view('books.show', compact('book'));
    }

    // =========================================================================
    // CREATE + STORE — Tambah buku baru (Admin & Pustakawan)
    // =========================================================================

    /**
     * GET /books/create
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * POST /books
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 's3');
        }

        $book = Book::create($data);

        return redirect()
            ->route('books.show', $book)
            ->with('success', "Buku \"{$book->judul}\" berhasil ditambahkan.");

    }

    // =========================================================================
    // EDIT + UPDATE — Ubah data buku (Admin & Pustakawan)
    // =========================================================================

    /**
     * GET /books/{book}/edit
     */
    public function edit(Book $book): View
    {
        return view('books.edit', compact('book'));
    }

    /**
     * PUT/PATCH /books/{book}
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();
        //test
        // Ganti cover jika ada file baru yang diupload
        if ($request->hasFile('cover')) {

            // 1. Cek dulu, apakah buku ini SEBELUMNYA punya cover?
            // Jika ada, hapus cover lamanya dari storage (S3/Lokal)
            if ($book->cover) {
                Storage::delete($book->cover);
            }

            // 2. Simpan cover yang baru
            $data['cover'] = $request->file('cover')->store('covers', 's3');
        }

        $book->update($data);

        return redirect()
            ->route('books.show', $book)
            ->with('success', "Data buku \"{$book->judul}\" berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY — Nonaktifkan buku (Soft Delete) — Admin & Pustakawan
    // =========================================================================

    /**
     * DELETE /books/{book}
     *
     * Tidak menghapus data secara permanen. Buku hanya ditandai deleted_at.
     * Riwayat peminjaman dan wishlist tetap aman.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Cegah nonaktifkan buku yang eksemplarnya masih dipinjam
        $masihDipinjam = $book->bookItems()
                              ->whereIn('status_ketersediaan', ['Dipinjam'])
                              ->exists();

        if ($masihDipinjam) {
            return back()->with('error', "Buku \"{$book->judul}\" tidak dapat dinonaktifkan karena masih ada eksemplar yang sedang dipinjam.");
        }

        $book->delete(); // SoftDelete — mengisi kolom deleted_at

        return redirect()
            ->route('books.index')
            ->with('success', "Buku \"{$book->judul}\" berhasil dinonaktifkan.");
    }

    // =========================================================================
    // RESTORE — Pulihkan buku yang dinonaktifkan — Admin Only
    // =========================================================================

    /**
     * PATCH /books/{book}/restore
     *
     * Route model binding tidak otomatis resolve soft-deleted model,
     * jadi kita ambil manual menggunakan withTrashed().
     */
    public function restore(int $id): RedirectResponse
    {
        $book = Book::withTrashed()->findOrFail($id);

        // Pustakawan tidak bisa restore, hanya admin
        if (! request()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat memulihkan buku.');
        }

        $book->restore(); // Mengosongkan kolom deleted_at

        return redirect()
            ->route('books.index')
            ->with('success', "Buku \"{$book->judul}\" berhasil dipulihkan.");
    }
}
