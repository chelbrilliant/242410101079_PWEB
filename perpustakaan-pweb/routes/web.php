<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DaftarController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferensiController;
use App\Http\Controllers\KunjunganController;

// ── Route publik ────────────────────────────────────────
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::get('/daftar', [DaftarController::class, 'index'])->name('daftar');
Route::get('/hitung/{a}/{b}', fn($a, $b) => $a + $b);

// ── SOAL 4 — Kunjungan (session counter, publik) ────────
Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan');
Route::post('/kunjungan/reset', [KunjunganController::class, 'reset'])->name('kunjungan.reset');

// ── SOAL 3 — Preferensi / dark mode (publik) ────────────
Route::get('/preferensi', [PreferensiController::class, 'index'])->name('preferensi');
Route::post('/preferensi/simpan', [PreferensiController::class, 'simpan'])->name('preferensi.simpan');

// ── Route yang butuh login ───────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    // Live Search AJAX (butuh auth)
    Route::get('/peminjaman-search', [PeminjamanController::class, 'search'])->name('peminjaman.search');

    Route::get('/test-flash', function () {
        return redirect()->route('dashboard')->with('success', 'Data berhasil disimpan!');
    });
});

// ── Route khusus Admin ───────────────────────────────────
Route::middleware(['auth', 'cek.admin'])->group(function () {
    Route::get('/admin/statistik', function () {
        return view('admin.statistik');
    })->name('admin.statistik');
});

require __DIR__.'/auth.php';
