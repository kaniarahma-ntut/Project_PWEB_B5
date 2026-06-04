<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\Bookcontroller;
use App\Http\Controllers\Bookitemcontroller;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\PeminjamanController;
use App\Http\Middleware\CheckRole; // Pastikan ini ada!
use Illuminate\Support\Facades\Route;

// =============================================================================
// AUTH — Google OAuth
// =============================================================================

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('google',          [Authcontroller::class, 'redirectToGoogle'])->name('google');
    Route::get('google/callback', [Authcontroller::class, 'handleGoogleCallback'])->name('google.callback');
});

// Route login halaman landing
Route::get('/login', fn() => view('auth.login'))->name('login');

// Logout
Route::post('/logout', [Authcontroller::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// =============================================================================
// AREA TERPROTEKSI — Semua route di dalam sini wajib sudah login
// =============================================================================

Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------------------
    // DASHBOARD UMUM (Anggota / Default)
    // -------------------------------------------------------------------------
    // BARIS INI YANG TADI TERHAPUS:
    Route::get('/',           fn() => redirect()->route('dashboard'));
    Route::get('/dashboard',  fn() => view('anggota.dashboard'))->name('dashboard');

    // -------------------------------------------------------------------------
    // BUKU
    // -------------------------------------------------------------------------
    Route::resource('books', Bookcontroller::class);
    Route::patch('books/{book}/restore', [Bookcontroller::class, 'restore'])
         ->name('books.restore')
         ->withTrashed();

    // -------------------------------------------------------------------------
    // EKSEMPLAR BUKU (BookItem)
    // -------------------------------------------------------------------------
    Route::prefix('books/{book}/items')->name('book-items.')->group(function () {
        Route::get('create',  [Bookitemcontroller::class, 'create'])->name('create');
        Route::post('/',      [Bookitemcontroller::class, 'store'])->name('store');
    });

    Route::prefix('book-items')->name('book-items.')->group(function () {
        Route::get('/',                         [Bookitemcontroller::class, 'index'])->name('index');
        Route::get('{bookItem}',                [Bookitemcontroller::class, 'show'])->name('show');
        Route::get('{bookItem}/edit',           [Bookitemcontroller::class, 'edit'])->name('edit');
        Route::put('{bookItem}',                [Bookitemcontroller::class, 'update'])->name('update');
        Route::delete('{bookItem}',             [Bookitemcontroller::class, 'destroy'])->name('destroy');
        Route::patch('{bookItem}/status',       [Bookitemcontroller::class, 'updateStatus'])->name('updateStatus');
        Route::patch('{bookItem}/restore',      [Bookitemcontroller::class, 'restore'])->name('restore');
    });

    // -------------------------------------------------------------------------
    // MANAJEMEN AKUN (User)
    // -------------------------------------------------------------------------
    Route::resource('users', Usercontroller::class);
    Route::patch('users/{user}/restore', [Usercontroller::class, 'restore'])
         ->name('users.restore');

    // -------------------------------------------------------------------------
    // ROUTE KHUSUS ROLE — Dashboard Admin & Pustakawan
    // -------------------------------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware([CheckRole::class.':admin'])->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('in', fn() => view('admin.dashboard'))->name('dashboard');
    });

    Route::prefix('pustakawan')->name('pustakawan.')->middleware([CheckRole::class.':pustakawan'])->group(function () {
        Route::get('dashboard', fn() => view('pustakawan.dashboard'))->name('dashboard');
    });

    // -------------------------------------------------------------------------
    // MANAJEMEN PEMINJAMAN
    // -------------------------------------------------------------------------
    Route::resource('peminjamans', PeminjamanController::class)
        ->only(['index', 'store', 'show']);

    Route::patch('peminjamans/{peminjaman}/request-return', [PeminjamanController::class, 'requestReturn'])
        ->name('peminjamans.request-return');

    Route::patch('peminjamans/{peminjaman}/approve-return', [PeminjamanController::class, 'approveReturn'])
        ->name('peminjamans.approve-return');

    Route::patch('peminjamans/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])
        ->name('peminjamans.updateStatus');
});
