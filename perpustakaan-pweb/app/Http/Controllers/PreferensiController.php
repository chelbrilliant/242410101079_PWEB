<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferensiController extends Controller
{
    /**
     * SOAL 3e — Halaman preferensi (tema + font)
     */
    public function index(Request $request)
    {
        // Baca cookie yang sudah tersimpan untuk pre-fill form
        $tema      = $request->cookie('tema', 'light');
        $ukuranFont = $request->cookie('ukuran_font', 'medium');

        return view('preferensi', compact('tema', 'ukuranFont'));
    }

    /**
     * SOAL 3g — Simpan preferensi dari form ke cookie, kembalikan JSON
     * Dipanggil via Fetch POST dari JavaScript
     */
    public function simpan(Request $request)
    {
        $tema       = $request->input('tema', 'light');
        $ukuranFont = $request->input('ukuran_font', 'medium');

        // Baca cookie yang dikirim
        $temaLama = $request->cookie('tema', 'belum diset');

        return response()->json([
            'status'       => 'success',
            'message'      => 'Preferensi berhasil disimpan!',
            'tema'         => $tema,
            'ukuran_font'  => $ukuranFont,
            'tema_lama'    => $temaLama,
        ])
        ->cookie('tema', $tema, 60 * 24 * 30)           // 30 hari
        ->cookie('ukuran_font', $ukuranFont, 60 * 24 * 30);
    }
}
