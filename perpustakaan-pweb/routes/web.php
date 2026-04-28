<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DaftarController;

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Tentang
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');

// Route dengan parameter — contoh Aktivitas Mandiri 2 no. 8
Route::get('/hitung/{a}/{b}', fn($a, $b) => $a + $b);

// Beranda (halaman utama)
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Peminjaman Buku
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');

// Daftar Buku
Route::get('/daftar', [DaftarController::class, 'index'])->name('daftar');
