<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAdmin
{
    /**
     * Hanya mengizinkan user dengan role 'admin' mengakses route tertentu.
     * Jika bukan admin, redirect ke dashboard dengan pesan error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak! Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
