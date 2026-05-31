<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    // Daftar peminjaman yang bisa dikelola pengembaliannya
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin: lihat semua yang berstatus Dipinjam atau Terlambat
            $peminjaman = Peminjaman::with(['user', 'buku'])
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->latest()->paginate(10);
        } else {
            // Customer: lihat milik sendiri yang belum dikembalikan
            $peminjaman = Peminjaman::where('user_id', $user->id)
                ->with('buku')
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->latest()->paginate(10);
        }

        return view('pengembalian.index', compact('peminjaman'));
    }

    // Form konfirmasi pengembalian
    public function show(Peminjaman $peminjaman)
    {
        // Customer hanya bisa lihat milik sendiri
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        // Hitung denda saat ini (belum disimpan)
        $keterlambatan = $peminjaman->hitungKeterlambatan();
        $denda         = $peminjaman->hitungDenda();

        return view('pengembalian.show', compact('peminjaman', 'keterlambatan', 'denda'));
    }

    // Proses pengembalian — update status + simpan denda ke DB
    public function update(Request $request, Peminjaman $peminjaman)
    {
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status === 'Dikembalikan') {
            return redirect()->route('pengembalian.index')
                ->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        // Hitung denda final saat dikembalikan
        $denda = $peminjaman->hitungDenda();

        // Update status dan simpan denda ke database
        $peminjaman->update([
            'status'        => 'Dikembalikan',
            'aktif'         => false,
            'denda'         => $denda,
            'denda_dibayar' => false, // bayar manual di perpustakaan
        ]);

        // Kembalikan stok buku +1
        if ($peminjaman->buku) {
            $peminjaman->buku->increment('stok');
            $peminjaman->buku->update(['tersedia' => true]);
        }

        $pesan = $denda > 0
            ? 'Buku berhasil dikembalikan. Terdapat denda Rp ' . number_format($denda, 0, ',', '.') . ' yang harus dibayar di perpustakaan.'
            : 'Buku berhasil dikembalikan. Terima kasih!';

        return redirect()->route('pengembalian.index')->with('success', $pesan);
    }

    // Admin: tandai denda sudah dibayar
    public function bayarDenda(Peminjaman $peminjaman)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $peminjaman->update(['denda_dibayar' => true]);

        return redirect()->back()
            ->with('success', 'Denda peminjaman #' . $peminjaman->id . ' sudah ditandai lunas.');
    }

    // AJAX: cek status keterlambatan real-time (JSON)
    public function cekStatus(Request $request, Peminjaman $peminjaman)
    {
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        return response()->json([
            'status'           => $peminjaman->status,
            'keterlambatan'    => $peminjaman->hitungKeterlambatan(),
            'denda'            => $peminjaman->hitungDenda(),
            'denda_format'     => 'Rp ' . number_format($peminjaman->hitungDenda(), 0, ',', '.'),
            'is_terlambat'     => $peminjaman->isTerlambat(),
            'tanggal_kembali'  => $peminjaman->tanggal_kembali?->format('d/m/Y'),
        ]);
    }
}
