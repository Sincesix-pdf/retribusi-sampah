<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Warga;

class TagihanController extends Controller
{
    public function indexTetap(Request $request)
    {
        $query = Tagihan::with('warga.pengguna')->where('jenis_retribusi', 'tetap');

        if ($request->has('bulan') && !empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }

        $tagihan = $query->paginate(10);

        return view('tagihan.index_tetap', compact('tagihan'));
    }


    public function indexTidakTetap()
    {
        $tagihan = Tagihan::where('jenis_retribusi', 'tidak_tetap')->with('warga.pengguna')->paginate(10);
        return view('tagihan.index_tidak_tetap', compact('tagihan'));
    }

    public function createTetap()
    {
        $warga = Warga::where('jenis_retribusi', 'tetap')->get();
        return view('tagihan.create_tetap', compact('warga'));
    }

    public function createTidakTetap()
    {
        $warga = Warga::where('jenis_retribusi', 'tidak tetap')->get();
        return view('tagihan.create_tidak_tetap', compact('warga'));
    }

    public function storeTetap(Request $request)
    {
        $request->validate([
            'NIK' => 'required|exists:warga,NIK',
            'tarif' => 'required|numeric',
            'bulan' => 'required|string',
            'tanggal_tagihan' => 'required|date'
        ]);

        Tagihan::create([
            'NIK' => $request->NIK,
            'jenis_retribusi' => 'tetap',
            'tarif' => $request->tarif,
            'bulan' => $request->bulan,
            'total_tagihan' => $request->tarif, // Total = tarif (karena tetap)
            'tanggal_tagihan' => $request->tanggal_tagihan
        ]);

        return redirect()->route('tagihan.index.tetap')->with('success', 'Tagihan Tetap Berhasil Dibuat');
    }

    public function storeTidakTetap(Request $request)
    {
        $request->validate([
            'NIK' => 'required|exists:warga,NIK',
            'tarif' => 'required|numeric',
            'volume' => 'required|numeric',
            'tanggal_tagihan' => 'required|date'
        ]);

        Tagihan::create([
            'NIK' => $request->NIK,
            'jenis_retribusi' => 'tidak_tetap',
            'tarif' => $request->tarif,
            'volume' => $request->volume,
            'total_tagihan' => $request->tarif * $request->volume, // Total = tarif * volume
            'tanggal_tagihan' => $request->tanggal_tagihan
        ]);

        return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan Tidak Tetap Berhasil Dibuat');
    }

    public function daftarTagihan()
    {
        return view('tagihan.daftartagihan');
    }


}
