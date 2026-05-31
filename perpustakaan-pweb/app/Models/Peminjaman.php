<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id', 'buku_id',
        'id_anggota', 'nama_peminjam', 'judul_buku', 'kode_buku',
        'status', 'tanggal_pinjam', 'tanggal_kembali',
        'aktif', 'keterangan', 'foto',
        'denda', 'denda_dibayar',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date',
        'aktif'           => 'boolean',
        'denda_dibayar'   => 'boolean',
        'denda'           => 'integer',
    ];

    // Relasi ke user peminjam
    public function user()  { return $this->belongsTo(User::class); }

    // Relasi ke buku
    public function buku()  { return $this->belongsTo(Buku::class); }

    // Hitung hari keterlambatan
    public function hitungKeterlambatan(): int
{
    if (!$this->tanggal_kembali || $this->status === 'Dikembalikan') return 0;

    $today = now()->startOfDay();
    $batas = Carbon::parse($this->tanggal_kembali)->startOfDay();

    $diff = $batas->diffInDays($today, false); // false = bisa negatif
    return $diff > 0 ? (int) $diff : 0;
}

    // Hitung denda: Rp 1.000 per hari terlambat
    public function hitungDenda(): int
    {
        return $this->hitungKeterlambatan() * 1000;
    }

    public function isTerlambat(): bool
    {
        return $this->hitungKeterlambatan() > 0;
    }
}
