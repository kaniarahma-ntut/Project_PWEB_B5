<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckRole; // Pastikan ini ada!
use Illuminate\Support\Facades\Route;

// =============================================================================
// AUTH — Google OAuth
// =============================================================================

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('google',          [AuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Route login halaman landing
Route::get('/login', fn() => view('auth.login'))->name('login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// =============================================================================
// AREA TERPROTEKSI — Semua route di dalam sini wajib sudah login
// =============================================================================

Route::middleware(['auth'])->group(function () {

// =========================================================================
    // DASHBOARD UMUM & PENGATUR LALU LINTAS ROLE
    // =========================================================================
    Route::get('/', fn() => redirect()->route('dashboard'));

    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Cek jika yang login adalah Admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Cek jika yang login adalah Pustakawan
        if ($user->isPustakawan()) {
            return redirect()->route('pustakawan.dashboard');
        }

        // Jika bukan keduanya (berarti Anggota), tampilkan view anggota
        return view('anggota.dashboard');

    })->name('dashboard');
    // -------------------------------------------------------------------------
    // BUKU
    // -------------------------------------------------------------------------
    Route::resource('books', BookController::class);
    Route::patch('books/{book}/restore', [BookController::class, 'restore'])
         ->name('books.restore')
         ->withTrashed();

    // -------------------------------------------------------------------------
    // EKSEMPLAR BUKU (BookItem)
    // -------------------------------------------------------------------------
    Route::prefix('books/{book}/items')->name('book-items.')->group(function () {
        Route::get('create',  [BookItemController::class, 'create'])->name('create');
        Route::post('/',      [BookItemController::class, 'store'])->name('store');
    });

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
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])
         ->name('users.restore');

    // -------------------------------------------------------------------------
    // ROUTE KHUSUS ROLE — Dashboard Admin & Pustakawan
    // -------------------------------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware([CheckRole::class.':admin'])->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('in', [DashboardController::class, 'indexAdmin'])->name('dashboard');
    });

    Route::prefix('pustakawan')->name('pustakawan.')->middleware([CheckRole::class.':pustakawan'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'indexPustakawan'])->name('dashboard');
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
