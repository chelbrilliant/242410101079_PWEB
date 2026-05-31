<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;

// ── Publik ────────────────────────────────────────────────
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');

// Daftar buku publik — semua bisa lihat
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// AJAX cari buku (untuk dropdown form peminjaman)
Route::get('/buku-cari', [BukuController::class, 'cariAjax'])->name('buku.cari')->middleware('auth');

// ── Khusus Admin ──────────────────────────────────────────
Route::middleware(['auth', 'cek.admin'])->group(function () {

    // CRUD Buku (admin only: create, edit, delete)
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Admin: tandai denda lunas
    Route::patch('/pengembalian/{peminjaman}/bayar-denda', [PengembalianController::class, 'bayarDenda'])->name('pengembalian.bayar-denda');
});

Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');

// ── Butuh login ───────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Peminjaman (admin & customer, dibedakan di controller)
    Route::resource('peminjaman', PeminjamanController::class);

    // AJAX live search peminjaman
    Route::get('/peminjaman-search', [PeminjamanController::class, 'search'])->name('peminjaman.search');

    // Pengembalian (admin & customer, dibedakan di controller)
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{peminjaman}', [PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::patch('/pengembalian/{peminjaman}', [PengembalianController::class, 'update'])->name('pengembalian.update');

    // AJAX: cek status keterlambatan real-time (JSON)
    Route::get('/pengembalian/{peminjaman}/cek', [PengembalianController::class, 'cekStatus'])->name('pengembalian.cek');

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';
