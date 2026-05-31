<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        // Statistik untuk stat cards beranda
        $totalBuku       = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $sedangDipinjam  = Peminjaman::where('status', 'Dipinjam')->count();
        $terlambat       = Peminjaman::where('status', 'Terlambat')->count();

        // Ambil nama dari cookie untuk "Selamat datang kembali"
        $lastUserName = $request->cookie('last_user_name');

        return view('beranda', compact(
            'totalBuku', 'totalPeminjaman', 'sedangDipinjam', 'terlambat', 'lastUserName'
        ));
    }
}
