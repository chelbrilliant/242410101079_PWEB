<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// ── Guest only (belum login) ──────────────────────────────────────────
Route::middleware('guest')->group(function () {

    // Register — hanya untuk customer
    Route::get('register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});

// ── Sudah login ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

});
