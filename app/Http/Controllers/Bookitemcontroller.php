<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookItemController extends Controller
{
    // =========================================================================
    // INDEX  semua eksemplar (bisa difilter per buku)
    // =========================================================================

    /**
     * GET /book-items  atau  GET /books/{book}/items
     */
    public function index(Request $request): View
    {
        $query = BookItem::with('book');

        // Filter berdasarkan buku tertentu
        if ($bookId = $request->input('book_id')) {
            $query->where('book_id', $bookId);
        }

        // Filter berdasarkan status
        if ($status = $request->input('status')) {
            $query->where('status_ketersediaan', $status);
        }

        $bookItems = $query->latest()->paginate(20)->withQueryString();
        $books     = Book::orderBy('judul')->get(['id', 'judul']); // untuk dropdown

        return view('book-items.index', compact('bookItems', 'books'));
    }

    // =========================================================================
    // SHOW — Detail satu eksemplar
    // =========================================================================

    /**
     * GET /book-items/{bookItem}
     */
    public function show(BookItem $bookItem): View
    {
        $bookItem->load(['book', 'peminjamans.user']);

        return view('book-items.show', compact('bookItem'));
    }

    // =========================================================================
    // CREATE + STORE — Tambah eksemplar baru untuk sebuah buku
    // =========================================================================

    /**
     * GET /books/{book}/items/create
     */
    public function create(Book $book): View
    {
        return view('book-items.create', compact('book'));
    }

    /**
     * POST /books/{book}/items
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'kode_buku'           => ['required', 'string', 'max:50', 'unique:book_items,kode_buku'],
            'kode_qr'             => ['nullable', 'string', 'unique:book_items,kode_qr'],
            'status_ketersediaan' => ['required', 'in:Tersedia,Dipinjam,Rusak,Hilang'],
        ]);

        $bookItem = $book->bookItems()->create($validated);

        return redirect()
            ->route('books.show', $book)
            ->with('success', "Eksemplar \"{$bookItem->kode_buku}\" berhasil ditambahkan.");
    }

    // =========================================================================
    // EDIT + UPDATE — Ubah data eksemplar
    // =========================================================================

    /**
     * GET /book-items/{bookItem}/edit
     */
    public function edit(BookItem $bookItem): View
    {
        $bookItem->load('book');

        return view('book-items.edit', compact('bookItem'));
    }

    /**
     * PUT/PATCH /book-items/{bookItem}
     */
    public function update(Request $request, BookItem $bookItem): RedirectResponse
    {
        $validated = $request->validate([
            'kode_buku'           => ['sometimes', 'required', 'string', 'max:50', "unique:book_items,kode_buku,{$bookItem->id}"],
            'kode_qr'             => ['nullable', 'string', "unique:book_items,kode_qr,{$bookItem->id}"],
            'status_ketersediaan' => ['sometimes', 'required', 'in:Tersedia,Dipinjam,Rusak,Hilang'],
        ]);

        $bookItem->update($validated);

        return redirect()
            ->route('books.show', $bookItem->book_id)
            ->with('success', "Eksemplar \"{$bookItem->kode_buku}\" berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY — Nonaktifkan eksemplar (Soft Delete)
    // =========================================================================

    /**
     * DELETE /book-items/{bookItem}
     *
     * Menggunakan SoftDelete agar riwayat peminjaman tidak orphan.
     * Eksemplar yang sedang dipinjam tidak bisa dinonaktifkan.
     */
    public function destroy(BookItem $bookItem): RedirectResponse
    {
        $bookId = $bookItem->book_id;

        // Cegah nonaktifkan jika eksemplar sedang dipinjam
        if ($bookItem->isDipinjam()) {
            return back()->with('error', "Eksemplar \"{$bookItem->kode_buku}\" tidak dapat dinonaktifkan karena sedang dipinjam.");
        }

        $kode = $bookItem->kode_buku;
        $bookItem->delete(); // SoftDelete — mengisi kolom deleted_at

        return redirect()
            ->route('books.show', $bookId)
            ->with('success', "Eksemplar \"{$kode}\" berhasil dinonaktifkan.");
    }

    // =========================================================================
    // RESTORE — Pulihkan eksemplar yang dinonaktifkan (Admin only)
    // =========================================================================

    /**
     * PATCH /book-items/{bookItem}/restore
     */
    public function restore(int $id): RedirectResponse
    {
        if (! request()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat memulihkan eksemplar buku.');
        }

        $bookItem = BookItem::withTrashed()->findOrFail($id);
        $bookItem->restore(); // Mengosongkan deleted_at

        return redirect()
            ->route('books.show', $bookItem->book_id)
            ->with('success', "Eksemplar \"{$bookItem->kode_buku}\" berhasil dipulihkan.");
    }

    // =========================================================================
    // UPDATE STATUS — Shortcut untuk mengubah status ketersediaan
    // =========================================================================

    /**
     * PATCH /book-items/{bookItem}/status
     */
    public function updateStatus(Request $request, BookItem $bookItem): RedirectResponse
    {
        $request->validate([
            'status_ketersediaan' => ['required', 'in:Tersedia,Dipinjam,Rusak,Hilang'],
        ]);

        $bookItem->update(['status_ketersediaan' => $request->status_ketersediaan]);

        return back()->with('success', "Status eksemplar \"{$bookItem->kode_buku}\" diubah menjadi \"{$request->status_ketersediaan}\".");
    }
}
