<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Warga;
use Illuminate\Support\Facades\Http;
use App\Services\WhatsAppService;

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

    public function generateTetap()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $wargaTetap = Warga::where('jenis_retribusi', 'tetap')->with('jenisLayanan')->get(); // Pastikan ada relasi jenis_layanan

        foreach ($wargaTetap as $warga) {
            // Cek apakah tagihan bulan ini sudah ada
            $tagihanExist = Tagihan::where('NIK', $warga->NIK)
                ->where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->exists();

            if (!$tagihanExist) {
                // Ambil tarif dari jenis layanan
                $tarif = $warga->jenisLayanan->tarif ?? 0; // Pastikan ada tarif, jika null set default 0

                // Simpan tagihan baru
                Tagihan::create([
                    'NIK' => $warga->NIK,
                    'jenis_retribusi' => 'tetap',
                    'tarif' => $tarif,
                    'bulan' => $bulanIni,
                    'tahun' => $tahunIni,
                ]);
            }
        }

        return redirect()->route('tagihan.index.tetap')->with('success', 'Tagihan berhasil dibuat!');
    }

    public function ajukanTagihan()
    {
        // Ambil semua tagihan yang belum diajukan
        $tagihan = Tagihan::whereNull('status')->get();

        if ($tagihan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tagihan yang dapat diajukan.');
        }

        // Ubah status tagihan menjadi 'diajukan'
        Tagihan::whereNull('status')->update(['status' => 'diajukan']);

        return redirect()->back()->with('success', 'Tagihan berhasil diajukan ke Kepala Dinas.');
    }


    public function createTetap()
    {
        $warga = Warga::where('jenis_retribusi', 'tetap')->get();
        return view('tagihan.create_tetap', compact('warga'));
    }

    public function createTidakTetap()
    {
        $warga = Warga::where('jenis_retribusi', 'tidak_tetap')->get();
        return view('tagihan.create_tidak_tetap', compact('warga'));
    }

    public function storeTetap(Request $request)
    {
        $request->validate([
            'NIK' => 'required|exists:warga,NIK',
            'tarif' => 'required|numeric',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2020',
        ]);

        Tagihan::create([
            'NIK' => $request->NIK,
            'jenis_retribusi' => 'tetap',
            'tarif' => $request->tarif,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
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
            'tanggal_tagihan' => $request->tanggal_tagihan
        ]);

        return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan Tidak Tetap Berhasil Dibuat');
    }

    public function edit($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $warga = Warga::where('jenis_retribusi', $tagihan->jenis_retribusi)->get();

        if ($tagihan->jenis_retribusi === 'tetap') {
            return view('tagihan.edit_tetap', compact('tagihan', 'warga'));
        } else {
            return view('tagihan.edit_tidak_tetap', compact('tagihan', 'warga'));
        }
    }

    public function update(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->jenis_retribusi === 'tetap') {
            $request->validate([
                'NIK' => 'required|exists:warga,NIK',
                'tarif' => 'required|numeric',
                'bulan' => 'required|numeric|min:1|max:12',
                'tahun' => 'required|numeric|min:2020',
            ]);

            $tagihan->update([
                'NIK' => $request->NIK,
                'tarif' => $request->tarif,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'total_tagihan' => $request->tarif,
            ]);

            return redirect()->route('tagihan.index.tetap')->with('success', 'Tagihan tetap berhasil diperbarui');
        } else {
            $request->validate([
                'NIK' => 'required|exists:warga,NIK',
                'tarif' => 'required|numeric',
                'volume' => 'required|numeric',
                'tanggal_tagihan' => 'required|date',
            ]);

            $tagihan->update([
                'NIK' => $request->NIK,
                'tarif' => $request->tarif,
                'volume' => $request->volume,
                'total_tagihan' => $request->tarif * $request->volume,
                'tanggal_tagihan' => $request->tanggal_tagihan,
            ]);

            return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan tidak tetap berhasil diperbarui');
        }
    }


    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if (in_array($tagihan->jenis_retribusi, ['tetap', 'tidak_tetap'])) {
            $tagihan->delete();

            if ($tagihan->jenis_retribusi === 'tetap') {
                return redirect()->route('tagihan.index.tetap')->with('success', 'Tagihan tetap berhasil dihapus');
            } else {
                return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan tidak tetap berhasil dihapus');
            }
        }

        return redirect()->back()->with('error', 'Tagihan tidak dapat dihapus');
    }

    // Fungsi untuk menampilkan daftar tagihan di Kepala Dinas
    public function daftarTagihan()
    {
        $tagihan = Tagihan::where('status', 'diajukan')->get();
        return view('tagihan.daftar_tagihan', compact('tagihan'));
    }

    // Fungsi untuk menyetujui tagihan dan mengirim WhatsApp
    public function setujuiTagihan(Request $request)
    {
        $apiKey = 'mb5Vs3fJcZpJFj7ePNq6'; // Ganti dengan API Key Fonnte

        $tagihan = Tagihan::whereIn('id', $request->tagihan_id)->get();

        foreach ($tagihan as $t) {
            // Update status tagihan menjadi 'disetujui'
            $t->update(['status' => 'disetujui']);

            // Ambil nomor HP warga
            $no_hp = $t->warga->pengguna->no_hp;
            $nama = $t->warga->pengguna->nama;
            $tarif = number_format($t->tarif, 0, ',', '.');

            // Pesan WhatsApp yang dikirim
            $pesan = "Halo *$nama*,\n\nTagihan Anda sebesar *Rp$tarif* telah disetujui.\n\nSilakan lakukan pembayaran tepat waktu. Terima kasih!";

            // Kirim pesan ke WhatsApp menggunakan Fonnte
            Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $no_hp,
                        'message' => $pesan,
                    ]);
        }

        return redirect()->back()->with('success', 'Tagihan telah disetujui dan dikirim ke warga melalui WhatsApp.');
    }

    public function grafik()
    {
        return view('log-aktivitas.index');
    }
}
