<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// AUTH — Google OAuth (tidak butuh middleware auth)
// =============================================================================

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('google',          [AuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Route login sederhana (halaman landing / login page)
Route::get('/login', fn() => view('auth.login'))->name('login');

// Logout (POST agar tidak bisa dipanggil via URL langsung)
Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// =============================================================================
// AREA TERPROTEKSI — semua route di bawah ini wajib sudah login
// =============================================================================

Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------------------
    // DASHBOARD — setiap role punya view berbeda
    // -------------------------------------------------------------------------
    Route::get('/',           fn() => redirect()->route('dashboard'));
    Route::get('/dashboard',  fn() => view('dashboard'))->name('dashboard');

    // -------------------------------------------------------------------------
    // BUKU — Resource route + restore (Admin & Pustakawan)
    // -------------------------------------------------------------------------

    // Resource CRUD standar: index, create, store, show, edit, update, destroy
    Route::resource('books', BookController::class);

    // Restore buku yang dinonaktifkan (Admin only — dicek di dalam controller)
    Route::patch('books/{book}/restore', [BookController::class, 'restore'])
         ->name('books.restore')
         ->withTrashed(); // Izinkan route model binding resolve soft-deleted model

    // -------------------------------------------------------------------------
    // EKSEMPLAR BUKU (BookItem) — nested di bawah /books/{book}/items
    // -------------------------------------------------------------------------

    // Nested: tambah & list eksemplar milik sebuah buku
    Route::prefix('books/{book}/items')->name('book-items.')->group(function () {
        Route::get('create',  [BookItemController::class, 'create'])->name('create');
        Route::post('/',      [BookItemController::class, 'store'])->name('store');
    });

    // Shallow: operasi pada eksemplar individual (tidak perlu konteks book_id di URL)
    Route::prefix('book-items')->name('book-items.')->group(function () {
        Route::get('/',                         [BookItemController::class, 'index'])->name('index');
        Route::get('{bookItem}',                [BookItemController::class, 'show'])->name('show');
        Route::get('{bookItem}/edit',           [BookItemController::class, 'edit'])->name('edit');
        Route::put('{bookItem}',                [BookItemController::class, 'update'])->name('update');
        Route::delete('{bookItem}',             [BookItemController::class, 'destroy'])->name('destroy');
        Route::patch('{bookItem}/status',       [BookItemController::class, 'updateStatus'])->name('updateStatus');
        Route::patch('{bookItem}/restore',      [BookItemController::class, 'restore'])->name('restore');
    });

    // -------------------------------------------------------------------------
    // MANAJEMEN AKUN (User)
    // -------------------------------------------------------------------------

    // Resource CRUD: index (admin), show, create (admin), store (admin),
    //                edit, update, destroy (admin)
    Route::resource('users', UserController::class);

    // Restore akun yang dinonaktifkan (Admin only)
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])
         ->name('users.restore');

    // -------------------------------------------------------------------------
    // ROUTE KHUSUS ROLE — Dashboard Admin & Pustakawan
    // -------------------------------------------------------------------------

    Route::middleware(['auth'])->group(function () {

        // Admin dashboard
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        });

        // Pustakawan dashboard
        Route::prefix('pustakawan')->name('pustakawan.')->group(function () {
            Route::get('dashboard', fn() => view('pustakawan.dashboard'))->name('dashboard');
        });
    });
});
