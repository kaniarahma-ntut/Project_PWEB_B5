<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    // =========================================================================
    // INDEX — Melihat daftar wishlist
    // =========================================================================

    /**
     * GET /wishlists
     *
     * Menampilkan semua buku yang ada di wishlist anggota.
     * Hanya anggota yang bisa akses halaman ini.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Pastikan hanya anggota yang bisa akses
        if (!$user->isAnggota()) {
            abort(403, 'Fitur wishlist hanya tersedia untuk anggota.');
        }

        $wishlists = Wishlist::with('book.bookItems')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('wishlists.index', compact('wishlists'));
    }

    // =========================================================================
    // STORE — Tambahkan buku ke wishlist
    // =========================================================================

    /**
     * POST /wishlists
     *
     * Menambahkan buku ke wishlist anggota.
     * Menggunakan unique constraint di database untuk mencegah duplikasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Pastikan hanya anggota yang bisa wishlist
        if (!$user->isAnggota()) {
            abort(403, 'Fitur wishlist hanya tersedia untuk anggota.');
        }

        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        // Cek apakah buku sudah ada di wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('book_id', $data['book_id'])
            ->exists();

        if ($exists) {
            return back()->with('info', 'Buku ini sudah ada di wishlist Anda.');
        }

        // Tambahkan ke wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'book_id' => $data['book_id'],
        ]);

        $book = Book::find($data['book_id']);

        return back()->with('success', "Buku \"{$book->judul}\" berhasil ditambahkan ke wishlist.");
    }

    // =========================================================================
    // DESTROY — Hapus buku dari wishlist
    // =========================================================================

    /**
     * DELETE /wishlists/{wishlist}
     *
     * Menghapus buku dari wishlist anggota.
     */
    public function destroy(Request $request, Wishlist $wishlist): RedirectResponse
    {
        $user = $request->user();

        // Pastikan user hanya bisa hapus wishlist miliknya sendiri
        if ($wishlist->user_id !== $user->id) {
            abort(403, 'Anda tidak dapat menghapus wishlist orang lain.');
        }

        $bookTitle = $wishlist->book->judul;
        $wishlist->delete();

        return back()->with('success', "Buku \"{$bookTitle}\" berhasil dihapus dari wishlist.");
    }

    // =========================================================================
    // TOGGLE — Toggle wishlist (untuk AJAX atau direct action)
    // =========================================================================

    /**
     * POST /wishlists/toggle
     *
     * Toggle wishlist: jika sudah ada di wishlist, hapus. Jika belum, tambahkan.
     * Berguna untuk button di halaman detail buku.
     */
    public function toggle(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isAnggota()) {
            abort(403, 'Fitur wishlist hanya tersedia untuk anggota.');
        }

        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('book_id', $data['book_id'])
            ->first();

        $book = Book::find($data['book_id']);

        if ($wishlist) {
            // Sudah ada di wishlist, hapus
            $wishlist->delete();
            return back()->with('success', "Buku \"{$book->judul}\" dihapus dari wishlist.");
        } else {
            // Belum ada di wishlist, tambahkan
            Wishlist::create([
                'user_id' => $user->id,
                'book_id' => $data['book_id'],
            ]);
            return back()->with('success', "Buku \"{$book->judul}\" ditambahkan ke wishlist.");
        }
    }
}
