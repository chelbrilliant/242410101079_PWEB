<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',
        'judul',
        'pengarang',
        'kategori',
        'tahun_terbit',
        'stok',
        'tersedia',
        'foto',
    ];

    protected $casts = [
        'tersedia'      => 'boolean',
        'tahun_terbit'  => 'integer',
        'stok'          => 'integer',
    ];

    // Local Scope: buku yang tersedia
    public function scopeTersedia($query)
    {
        return $query->where('tersedia', true);
    }

    // Relasi belongsToMany ke Peminjaman
    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_buku', 'buku_id', 'peminjaman_id');
    }
}
