<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_buku' => 'BK-001', 'judul' => 'Pemrograman Laravel 10', 'pengarang' => 'Imam Syahroni', 'kategori' => 'Teknologi', 'tahun_terbit' => 2023, 'stok' => 3, 'tersedia' => true],
            ['kode_buku' => 'BK-002', 'judul' => 'Clean Code: A Handbook', 'pengarang' => 'Robert C. Martin', 'kategori' => 'Teknologi', 'tahun_terbit' => 2008, 'stok' => 2, 'tersedia' => true],
            ['kode_buku' => 'BK-003', 'judul' => 'Fisika Untuk SMA', 'pengarang' => 'Marthen Kanginan', 'kategori' => 'Sains', 'tahun_terbit' => 2020, 'stok' => 5, 'tersedia' => false],
            ['kode_buku' => 'BK-004', 'judul' => 'Algoritma dan Pemrograman', 'pengarang' => 'Rinaldi Munir', 'kategori' => 'Teknologi', 'tahun_terbit' => 2019, 'stok' => 4, 'tersedia' => true],
            ['kode_buku' => 'BK-005', 'judul' => 'Basis Data dan SQL', 'pengarang' => 'Fathansyah', 'kategori' => 'Teknologi', 'tahun_terbit' => 2018, 'stok' => 3, 'tersedia' => true],
            ['kode_buku' => 'BK-006', 'judul' => 'Jaringan Komputer Dasar', 'pengarang' => 'Andrew Tanenbaum', 'kategori' => 'Teknologi', 'tahun_terbit' => 2021, 'stok' => 2, 'tersedia' => false],
            ['kode_buku' => 'BK-007', 'judul' => 'Kalkulus Lanjut', 'pengarang' => 'James Stewart', 'kategori' => 'Sains', 'tahun_terbit' => 2016, 'stok' => 1, 'tersedia' => false],
        ];

        foreach ($data as $item) {
            Buku::create($item);
        }
    }
}
