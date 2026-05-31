<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Daftar buku — publik (read only), admin bisa CRUD
    public function index(Request $request)
    {
        $keyword = $request->input('search', '');
        $buku = Buku::when($keyword, fn($q) => $q->where('judul', 'like', "%$keyword%")
                ->orWhere('pengarang', 'like', "%$keyword%")
                ->orWhere('kategori', 'like', "%$keyword%")
                ->orWhere('kode_buku', 'like', "%$keyword%"))
            ->latest()->paginate(10)->withQueryString();

        return view('buku.index', compact('buku', 'keyword'));
    }

    // Form tambah buku — admin only
    public function create()
    {
        return view('buku.create');
    }

    // Simpan buku baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_buku'    => 'required|string|max:20|unique:buku,kode_buku',
            'judul'        => 'required|string|max:200',
            'pengarang'    => 'required|string|max:100',
            'kategori'     => 'required|string|max:50',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok'         => 'required|integer|min:0',
        ], [
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
        ]);

        Buku::create([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'pengarang'    => $request->pengarang,
            'kategori'     => $request->kategori,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => $request->stok,
            'tersedia'     => $request->stok > 0,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku "' . $request->judul . '" berhasil ditambahkan!');
    }

    // Detail buku
    public function show(Buku $buku)
    {
        return view('buku.show', compact('buku'));
    }

    // Form edit buku — admin only
    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    // Update buku
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode_buku'    => 'required|string|max:20|unique:buku,kode_buku,' . $buku->id,
            'judul'        => 'required|string|max:200',
            'pengarang'    => 'required|string|max:100',
            'kategori'     => 'required|string|max:50',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok'         => 'required|integer|min:0',
        ]);

        $buku->update([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'pengarang'    => $request->pengarang,
            'kategori'     => $request->kategori,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => $request->stok,
            'tersedia'     => $request->stok > 0,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui!');
    }

    // Hapus buku — admin only
    public function destroy(Buku $buku)
    {
        // Cek apakah masih ada peminjaman aktif
        if ($buku->peminjaman()->where('status', 'Dipinjam')->exists()) {
            return redirect()->route('buku.index')
                ->with('error', 'Buku tidak bisa dihapus karena masih dipinjam!');
        }

        $judul = $buku->judul;
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku "' . $judul . '" berhasil dihapus.');
    }

    // AJAX — cari buku untuk dropdown di form peminjaman (JSON)
    public function cariAjax(Request $request)
    {
        $keyword = $request->input('keyword', '');

        $buku = Buku::tersedia()
            ->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%$keyword%")
                  ->orWhere('kode_buku', 'like', "%$keyword%")
                  ->orWhere('pengarang', 'like', "%$keyword%");
            })
            ->take(10)
            ->get(['id', 'kode_buku', 'judul', 'pengarang', 'stok']);

        // Kembalikan JSON untuk AJAX
        return response()->json([
            'status'  => 'success',
            'keyword' => $keyword,
            'total'   => $buku->count(),
            'data'    => $buku,
        ]);
    }
}
