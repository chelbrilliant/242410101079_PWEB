<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id_anggota'      => 'ANG-001',
                'nama_peminjam'   => 'Andi Pratama',
                'judul_buku'      => 'Pemrograman Laravel 10',
                'kode_buku'       => 'BK-001',
                'status'          => 'Dipinjam',
                'tanggal_pinjam'  => '2025-04-01',
                'tanggal_kembali' => null,
                'aktif'           => true,
                'keterangan'      => null,
            ],
            [
                'id_anggota'      => 'ANG-002',
                'nama_peminjam'   => 'Budi Santoso',
                'judul_buku'      => 'Clean Code: A Handbook',
                'kode_buku'       => 'BK-002',
                'status'          => 'Dikembalikan',
                'tanggal_pinjam'  => '2025-03-15',
                'tanggal_kembali' => '2025-03-28',
                'aktif'           => true,
                'keterangan'      => 'Dikembalikan tepat waktu',
            ],
            [
                'id_anggota'      => 'ANG-003',
                'nama_peminjam'   => 'Citra Dewi',
                'judul_buku'      => 'Fisika Untuk SMA',
                'kode_buku'       => 'BK-003',
                'status'          => 'Terlambat',
                'tanggal_pinjam'  => '2025-03-01',
                'tanggal_kembali' => null,
                'aktif'           => true,
                'keterangan'      => 'Sudah melewati batas waktu',
            ],
            [
                'id_anggota'      => 'ANG-004',
                'nama_peminjam'   => 'Dina Rahayu',
                'judul_buku'      => 'Algoritma dan Pemrograman',
                'kode_buku'       => 'BK-004',
                'status'          => 'Dipinjam',
                'tanggal_pinjam'  => '2025-04-10',
                'tanggal_kembali' => null,
                'aktif'           => true,
                'keterangan'      => null,
            ],
            [
                'id_anggota'      => 'ANG-005',
                'nama_peminjam'   => 'Eko Wijaya',
                'judul_buku'      => 'Basis Data dan SQL',
                'kode_buku'       => 'BK-005',
                'status'          => 'Dikembalikan',
                'tanggal_pinjam'  => '2025-03-20',
                'tanggal_kembali' => '2025-04-02',
                'aktif'           => false,
                'keterangan'      => 'Arsip lama',
            ],
            [
                'id_anggota'      => 'ANG-006',
                'nama_peminjam'   => 'Fajar Nugroho',
                'judul_buku'      => 'Jaringan Komputer Dasar',
                'kode_buku'       => 'BK-006',
                'status'          => 'Dipinjam',
                'tanggal_pinjam'  => '2025-04-15',
                'tanggal_kembali' => null,
                'aktif'           => true,
                'keterangan'      => null,
            ],
            [
                'id_anggota'      => 'ANG-007',
                'nama_peminjam'   => 'Gita Permata',
                'judul_buku'      => 'Kalkulus Lanjut',
                'kode_buku'       => 'BK-007',
                'status'          => 'Terlambat',
                'tanggal_pinjam'  => '2025-02-28',
                'tanggal_kembali' => null,
                'aktif'           => true,
                'keterangan'      => 'Dihubungi via email',
            ],
        ];

        foreach ($data as $item) {
            Peminjaman::create($item);
        }
    }
}
