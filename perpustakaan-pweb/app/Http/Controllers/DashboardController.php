<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy statistik - digunakan oleh komponen x-stat-card
        $statistik = [
            ['judul' => 'Total Peminjaman', 'nilai' => 5,   'ikon' => '📖'],
            ['judul' => 'Sedang Dipinjam',  'nilai' => 3,   'ikon' => '🔄'],
            ['judul' => 'Dikembalikan',     'nilai' => 1,   'ikon' => '✅'],
            ['judul' => 'Terlambat',        'nilai' => 1,   'ikon' => '⚠️'],
        ];

        // Data dummy menu admin - digunakan oleh @forelse
        $menuAdmin = [
            [
                'ikon'      => '📝',
                'judul'     => 'Manajemen Peminjaman',
                'deskripsi' => 'Tambah, edit, dan hapus data peminjaman buku. Semua perubahan langsung tersimpan.',
                'route'     => 'peminjaman.index',
                'label'     => 'Kelola Peminjaman',
            ],
            [
                'ikon'      => '📋',
                'judul'     => 'Daftar & Laporan',
                'deskripsi' => 'Lihat seluruh riwayat peminjaman, filter per kategori atau status, dan cari data spesifik.',
                'route'     => 'daftar',
                'label'     => 'Lihat Laporan',
            ],
            [
                'ikon'      => 'ℹ️',
                'judul'     => 'Tentang Sistem',
                'deskripsi' => 'Informasi mengenai Sistem Informasi Perpustakaan — fitur, teknologi, dan pengembang.',
                'route'     => 'tentang',
                'label'     => 'Baca Selengkapnya',
            ],
        ];

        return view('dashboard', compact('statistik', 'menuAdmin'));
    }
}
