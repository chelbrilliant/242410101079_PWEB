<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id_anggota',
        'nama_peminjam',
        'judul_buku',
        'kode_buku',
        'status',
        'tanggal_pinjam',
        'tanggal_kembali',
        'aktif',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'tanggal_pinjam'    => 'date',
        'tanggal_kembali'   => 'date',
        'aktif'             => 'boolean',
    ];

    // Local Scope: hanya peminjaman aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Local Scope: filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Relasi belongsToMany ke Buku
    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'peminjaman_buku', 'peminjaman_id', 'buku_id');
    }
}
