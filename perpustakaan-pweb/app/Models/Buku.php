<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kode_buku', 'judul', 'pengarang',
        'kategori', 'tahun_terbit', 'stok', 'tersedia',
    ];

    protected $casts = [
        'tersedia'     => 'boolean',
        'tahun_terbit' => 'integer',
        'stok'         => 'integer',
    ];

    // Scope buku yang masih bisa dipinjam
    public function scopeTersedia($query)
    {
        return $query->where('tersedia', true)->where('stok', '>', 0);
    }

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
