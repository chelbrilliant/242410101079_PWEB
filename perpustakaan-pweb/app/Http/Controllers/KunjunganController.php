<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        // Tambah jumlah kunjungan
        $jumlah = $request->session()->get('kunjungan_jumlah', 0) + 1;
        $request->session()->put('kunjungan_jumlah', $jumlah);

        // Catat waktu kunjungan pertama
        if (!$request->session()->has('kunjungan_pertama')) {
            $request->session()->put('kunjungan_pertama', now()->format('d F Y, H:i:s'));
        }

        // Update waktu kunjungan terakhir
        $request->session()->put('kunjungan_terakhir', now()->format('d F Y, H:i:s'));

        $data = [
            'jumlah'    => $jumlah,
            'pertama'   => $request->session()->get('kunjungan_pertama'),
            'terakhir'  => $request->session()->get('kunjungan_terakhir'),
        ];

        return view('kunjungan', compact('data'));
    }

    public function reset(Request $request)
    {
        $request->session()->forget(['kunjungan_jumlah', 'kunjungan_pertama', 'kunjungan_terakhir']);

        // Jika request dari AJAX — return JSON
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'status'  => 'success',
                'message' => 'Hitungan kunjungan berhasil direset!',
            ]);
        }

        return redirect()->route('kunjungan')->with('success', 'Hitungan kunjungan berhasil direset!');
    }
}
