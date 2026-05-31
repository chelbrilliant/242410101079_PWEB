<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Auto-update status jadi Terlambat jika sudah lewat tanggal kembali
        Peminjaman::where('status', 'Dipinjam')
            ->whereNotNull('tanggal_kembali')
            ->whereDate('tanggal_kembali', '<', now()->toDateString())
            ->update(['status' => 'Terlambat']);

        if ($user->isAdmin()) {
            // Admin: lihat semua data
            $statistik = [
                ['judul' => 'Total Buku',       'nilai' => Buku::count(),                                       'ikon' => '📚', 'warna' => 'var(--primary)'],
                ['judul' => 'Total Peminjaman', 'nilai' => Peminjaman::count(),                                 'ikon' => '📋', 'warna' => 'var(--primary-light)'],
                ['judul' => 'Sedang Dipinjam',  'nilai' => Peminjaman::where('status', 'Dipinjam')->count(),    'ikon' => '🔄', 'warna' => 'var(--warning)'],
                ['judul' => 'Terlambat',        'nilai' => Peminjaman::where('status', 'Terlambat')->count(),   'ikon' => '⚠️', 'warna' => 'var(--danger)'],
                ['judul' => 'Dikembalikan',     'nilai' => Peminjaman::where('status', 'Dikembalikan')->count(),'ikon' => '✅', 'warna' => 'var(--success)'],
                ['judul' => 'Total Customer',   'nilai' => User::where('role', 'customer')->count(),            'ikon' => '👤', 'warna' => 'var(--teal)'],
            ];
            $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();
            $bukuHabis         = Buku::where('stok', 0)->orWhere('tersedia', false)->count();

        } else {
            // Customer: lihat data milik sendiri saja
            $milik = Peminjaman::where('user_id', $user->id);
            $statistik = [
                ['judul' => 'Total Peminjaman', 'nilai' => (clone $milik)->count(),                                 'ikon' => '📋', 'warna' => 'var(--primary)'],
                ['judul' => 'Sedang Dipinjam',  'nilai' => (clone $milik)->where('status', 'Dipinjam')->count(),    'ikon' => '🔄', 'warna' => 'var(--warning)'],
                ['judul' => 'Dikembalikan',     'nilai' => (clone $milik)->where('status', 'Dikembalikan')->count(),'ikon' => '✅', 'warna' => 'var(--success)'],
                ['judul' => 'Terlambat',        'nilai' => (clone $milik)->where('status', 'Terlambat')->count(),   'ikon' => '⚠️', 'warna' => 'var(--danger)'],
            ];
            $peminjamanTerbaru = Peminjaman::where('user_id', $user->id)->with('buku')->latest()->take(5)->get();
            $bukuHabis         = null;
        }

        return view('dashboard', compact('statistik', 'peminjamanTerbaru', 'bukuHabis'));
    }
}
