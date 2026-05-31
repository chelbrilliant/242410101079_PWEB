<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeminjamanController extends Controller
{
    // Daftar peminjaman — admin lihat semua, customer lihat milik sendiri
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $peminjaman = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
        } else {
            $peminjaman = Peminjaman::where('user_id', $user->id)
                ->with('buku')->latest()->paginate(10);
        }

        return view('peminjaman.index', compact('peminjaman'));
    }

    // AJAX live search peminjaman
    public function search(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $user    = auth()->user();

        $query = Peminjaman::with(['user', 'buku']);

        // Customer hanya bisa search data miliknya sendiri
        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        }

        $results = $query->where(function ($q) use ($keyword) {
                $q->where('nama_peminjam', 'like', "%$keyword%")
                  ->orWhere('judul_buku',   'like', "%$keyword%")
                  ->orWhere('id_anggota',   'like', "%$keyword%")
                  ->orWhere('status',       'like', "%$keyword%");
            })
            ->latest()->take(20)
            ->get(['id', 'id_anggota', 'nama_peminjam', 'judul_buku', 'status', 'tanggal_pinjam', 'denda']);

        return response()->json([
            'status'  => 'success',
            'keyword' => $keyword,
            'total'   => $results->count(),
            'data'    => $results,
        ]);
    }

    // Form tambah peminjaman — pilih buku dari dropdown
    public function create()
    {
        // Ambil semua buku yang tersedia untuk dropdown
        $bukuTersedia = Buku::tersedia()->orderBy('judul')->get();
        return view('peminjaman.create', compact('bukuTersedia'));
    }

    // Simpan peminjaman baru
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'buku_id'         => 'required|exists:buku,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keterangan'      => 'nullable|string|max:500',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'buku_id.required' => 'Pilih buku yang akan dipinjam.',
            'buku_id.exists'   => 'Buku tidak ditemukan.',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        // Cek stok buku
        $buku = Buku::findOrFail($request->buku_id);
        if ($buku->stok <= 0 || !$buku->tersedia) {
            return back()->withErrors(['buku_id' => 'Stok buku habis atau tidak tersedia.'])->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-peminjam', 'public');
        }

        // Buat peminjaman — isi data dari user yang login
        Peminjaman::create([
            'user_id'         => $user->id,
            'buku_id'         => $buku->id,
            'id_anggota'      => 'USR-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'nama_peminjam'   => $user->name,
            'judul_buku'      => $buku->judul,
            'kode_buku'       => $buku->kode_buku,
            'status'          => 'Dipinjam',
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'aktif'           => true,
            'keterangan'      => $request->keterangan,
            'foto'            => $fotoPath,
            'denda'           => 0,
            'denda_dibayar'   => false,
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');
        if ($buku->stok <= 0) {
            $buku->update(['tersedia' => false]);
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman buku "' . $buku->judul . '" berhasil dibuat!');
    }

    // Detail peminjaman
    public function show(Peminjaman $peminjaman)
    {
        // Customer tidak bisa lihat peminjaman orang lain
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }
        return view('peminjaman.show', compact('peminjaman'));
    }

    // Form edit peminjaman
    public function edit(Peminjaman $peminjaman)
    {
        // Customer tidak bisa edit peminjaman orang lain
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }
        // Customer tidak bisa edit yang sudah dikembalikan
        if (auth()->user()->isCustomer() && $peminjaman->status === 'Dikembalikan') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Peminjaman yang sudah dikembalikan tidak bisa diubah.');
        }

        $bukuTersedia = Buku::tersedia()->orderBy('judul')->get();
        return view('peminjaman.edit', compact('peminjaman', 'bukuTersedia'));
    }

    // Update peminjaman
    public function update(Request $request, Peminjaman $peminjaman)
    {
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'buku_id'         => 'required|exists:buku,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keterangan'      => 'nullable|string|max:500',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        // Jika buku berubah, kembalikan stok buku lama dan kurangi stok buku baru
        if ($peminjaman->buku_id !== $buku->id && $peminjaman->status === 'Dipinjam') {
            // Kembalikan stok buku lama
            if ($peminjaman->buku) {
                $peminjaman->buku->increment('stok');
                $peminjaman->buku->update(['tersedia' => true]);
            }
            // Kurangi stok buku baru
            if ($buku->stok <= 0) {
                return back()->withErrors(['buku_id' => 'Stok buku baru habis.'])->withInput();
            }
            $buku->decrement('stok');
            if ($buku->stok <= 0) $buku->update(['tersedia' => false]);
        }

        $data = [
            'buku_id'         => $buku->id,
            'judul_buku'      => $buku->judul,
            'kode_buku'       => $buku->kode_buku,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan'      => $request->keterangan,
        ];

        // Admin bisa ubah status secara manual
        if (auth()->user()->isAdmin() && $request->has('status')) {
            $data['status'] = $request->status;
        }

        if ($request->hasFile('foto')) {
            if ($peminjaman->foto) Storage::disk('public')->delete($peminjaman->foto);
            $data['foto'] = $request->file('foto')->store('foto-peminjam', 'public');
        }

        $peminjaman->update($data);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    // Hapus peminjaman
    public function destroy(Peminjaman $peminjaman)
    {
        if (auth()->user()->isCustomer() && $peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        // Kembalikan stok jika masih berstatus dipinjam
        if ($peminjaman->status === 'Dipinjam' && $peminjaman->buku) {
            $peminjaman->buku->increment('stok');
            $peminjaman->buku->update(['tersedia' => true]);
        }

        if ($peminjaman->foto) Storage::disk('public')->delete($peminjaman->foto);
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
