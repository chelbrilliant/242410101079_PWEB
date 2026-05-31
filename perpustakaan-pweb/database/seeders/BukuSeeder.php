<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_buku' => 'BK-001', 'judul' => 'Pemrograman Laravel 10',        'pengarang' => 'Imam Syahroni',      'kategori' => 'Teknologi',  'tahun_terbit' => 2023, 'stok' => 3, 'tersedia' => true],
            ['kode_buku' => 'BK-002', 'judul' => 'Clean Code: A Handbook',         'pengarang' => 'Robert C. Martin',   'kategori' => 'Teknologi',  'tahun_terbit' => 2008, 'stok' => 2, 'tersedia' => true],
            ['kode_buku' => 'BK-003', 'judul' => 'Fisika Untuk SMA',               'pengarang' => 'Marthen Kanginan',   'kategori' => 'Sains',      'tahun_terbit' => 2020, 'stok' => 5, 'tersedia' => true],
            ['kode_buku' => 'BK-004', 'judul' => 'Algoritma dan Pemrograman',      'pengarang' => 'Rinaldi Munir',      'kategori' => 'Teknologi',  'tahun_terbit' => 2019, 'stok' => 4, 'tersedia' => true],
            ['kode_buku' => 'BK-005', 'judul' => 'Basis Data dan SQL',             'pengarang' => 'Fathansyah',         'kategori' => 'Teknologi',  'tahun_terbit' => 2018, 'stok' => 3, 'tersedia' => true],
            ['kode_buku' => 'BK-006', 'judul' => 'Jaringan Komputer Dasar',        'pengarang' => 'Andrew Tanenbaum',   'kategori' => 'Teknologi',  'tahun_terbit' => 2021, 'stok' => 2, 'tersedia' => true],
            ['kode_buku' => 'BK-007', 'judul' => 'Kalkulus Lanjut',                'pengarang' => 'James Stewart',      'kategori' => 'Sains',      'tahun_terbit' => 2016, 'stok' => 1, 'tersedia' => true],
            ['kode_buku' => 'BK-008', 'judul' => 'Sejarah Peradaban Islam',        'pengarang' => 'Badri Yatim',        'kategori' => 'Agama',      'tahun_terbit' => 2017, 'stok' => 4, 'tersedia' => true],
            ['kode_buku' => 'BK-009', 'judul' => 'Laskar Pelangi',                 'pengarang' => 'Andrea Hirata',      'kategori' => 'Fiksi',      'tahun_terbit' => 2005, 'stok' => 3, 'tersedia' => true],
            ['kode_buku' => 'BK-010', 'judul' => 'Manajemen Sumber Daya Manusia',  'pengarang' => 'Gary Dessler',       'kategori' => 'Non-Fiksi',  'tahun_terbit' => 2020, 'stok' => 2, 'tersedia' => true],
        ];

        foreach ($data as $item) {
            Buku::firstOrCreate(['kode_buku' => $item['kode_buku']], $item);
        }
    }
}
