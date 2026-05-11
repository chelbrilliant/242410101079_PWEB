<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DaftarController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ProfileController;

// -------------------------------------------------------
// Route publik — bisa diakses tanpa login
// -------------------------------------------------------
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::get('/daftar', [DaftarController::class, 'index'])->name('daftar');
Route::get('/hitung/{a}/{b}', fn($a, $b) => $a + $b);

// -------------------------------------------------------
// Route yang membutuhkan login (middleware auth)
// -------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AKTIVITAS 5 — CRUD Peminjaman, diamankan dengan middleware auth
    // Soal 5 Aktivitas 6: Route::middleware('auth')->resource(...)
    Route::resource('peminjaman', PeminjamanController::class);

    // Test flash message
    Route::get('/test-flash', function () {
        return redirect()->route('dashboard')->with('success', 'Data berhasil disimpan!');
    });
});

// -------------------------------------------------------
// Route khusus Admin (middleware auth + cek.admin)
// -------------------------------------------------------
Route::middleware(['auth', 'cek.admin'])->group(function () {
    // Contoh route khusus admin — statistik dan laporan
    Route::get('/admin/statistik', function () {
        return view('admin.statistik');
    })->name('admin.statistik');
});

// -------------------------------------------------------
// Route Breeze (login, register, logout) — ditambah otomatis
// setelah php artisan breeze:install blade
// -------------------------------------------------------
require __DIR__.'/auth.php';
