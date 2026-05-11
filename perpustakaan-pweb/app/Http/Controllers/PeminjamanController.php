<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeminjamanController extends Controller
{
    /**
     * index() — tampilkan peminjaman dengan pagination
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            // Admin melihat semua data peminjaman
            $peminjaman = Peminjaman::latest()->paginate(10);
        } else {
            // Petugas hanya melihat data yang dia buat
            $peminjaman = Peminjaman::where('user_id', auth()->id())
                ->latest()->paginate(10);
        }

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * create() — tampilkan form tambah peminjaman
     */
    public function create()
    {
        return view('peminjaman.create');
    }

    /**
     * store() — simpan data baru dengan validasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_anggota'      => 'required|string|max:20|unique:peminjaman,id_anggota',
            'nama_peminjam'   => 'required|string|min:3|max:100',
            'judul_buku'      => 'required|string|max:200',
            'kode_buku'       => 'nullable|string|max:20',
            'status'          => 'required|in:Dipinjam,Dikembalikan,Terlambat',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'      => 'nullable|string|max:500',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'id_anggota.unique' => 'ID Anggota sudah terdaftar dalam peminjaman.',
            'nama_peminjam.min' => 'Nama peminjam minimal 3 karakter.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.mimes'        => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max'          => 'Ukuran foto maksimal 2 MB.',
        ]);

        $data = $request->except(['foto', '_token']);
        $data['aktif']   = true;
        $data['user_id'] = auth()->id(); // simpan siapa yang membuat

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-peminjam', 'public');
        }

        Peminjaman::create($data);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    /**
     * show() — detail satu peminjaman (Route Model Binding)
     */
    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * edit() — form edit pre-filled
     */
    public function edit(Peminjaman $peminjaman)
    {
        return view('peminjaman.edit', compact('peminjaman'));
    }

    /**
     * update() — validasi unique kecuali ID ini
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'id_anggota'      => 'required|string|max:20|unique:peminjaman,id_anggota,' . $peminjaman->id,
            'nama_peminjam'   => 'required|string|min:3|max:100',
            'judul_buku'      => 'required|string|max:200',
            'kode_buku'       => 'nullable|string|max:20',
            'status'          => 'required|in:Dipinjam,Dikembalikan,Terlambat',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'      => 'nullable|string|max:500',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'id_anggota.unique' => 'ID Anggota sudah digunakan oleh peminjaman lain.',
            'nama_peminjam.min' => 'Nama peminjam minimal 3 karakter.',
        ]);

        $data = $request->except(['foto', '_method']);

        if ($request->hasFile('foto')) {
            if ($peminjaman->foto) {
                Storage::disk('public')->delete($peminjaman->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-peminjam', 'public');
        }

        $peminjaman->update($data);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    /**
     * destroy() — hapus data dengan konfirmasi JavaScript
     */
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->foto) {
            Storage::disk('public')->delete($peminjaman->foto);
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
