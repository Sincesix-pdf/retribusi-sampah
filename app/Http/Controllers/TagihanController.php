<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\TarifRetribusi;
use App\Models\Warga;
use Illuminate\Support\Facades\Http;
use Midtrans\Snap;
use Midtrans\Config;


class TagihanController extends Controller
{
    public function indexTetap(Request $request)
    {
        $bulan = $request->input('bulan');

        // Filter tagihan berdasarkan status
        $tagihanDiajukan = Tagihan::where('jenis_retribusi', 'tetap')
            ->where('status', 'diajukan')
            ->when($bulan, function ($query) use ($bulan) {
                return $query->where('bulan', $bulan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        $tagihanDisetujui = Tagihan::where('jenis_retribusi', 'tetap')
            ->where('status', 'disetujui')
            ->when($bulan, function ($query) use ($bulan) {
                return $query->where('bulan', $bulan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        return view('tagihan.index_tetap', compact('tagihanDiajukan', 'tagihanDisetujui'));
    }

    public function indexTidakTetap(Request $request)
    {
        $tanggal_tagihan = $request->input('tanggal_tagihan');

        // Filter tagihan tidak tetap berdasarkan status
        $tagihanDiajukan = Tagihan::where('jenis_retribusi', 'tidak_tetap')
            ->where('status', 'diajukan')
            ->when($tanggal_tagihan, function ($query) use ($tanggal_tagihan) {
                return $query->whereDate('tanggal_tagihan', $tanggal_tagihan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        $tagihanDisetujui = Tagihan::where('jenis_retribusi', 'tidak_tetap')
            ->where('status', 'disetujui')
            ->when($tanggal_tagihan, function ($query) use ($tanggal_tagihan) {
                return $query->whereDate('tanggal_tagihan', $tanggal_tagihan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        return view('tagihan.index_tidak_tetap', compact('tagihanDiajukan', 'tagihanDisetujui'));
    }

    public function generateTetap(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = Carbon::now()->year;

        $wargaTetap = Warga::where('jenis_retribusi', 'tetap')->with('jenisLayanan')->get();

        foreach ($wargaTetap as $warga) {
            $tagihanExist = Tagihan::where('NIK', $warga->NIK)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            if (!$tagihanExist) {
                $tarif = $warga->jenisLayanan->tarif ?? 0;

                Tagihan::create([
                    'NIK' => $warga->NIK,
                    'jenis_retribusi' => 'tetap',
                    'tarif' => $tarif,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'status' => 'diajukan'
                ]);
            } else {
                if ($tagihanExist->status === null) {
                    $tagihanExist->update(['status' => 'diajukan']);
                }
            }
        }

        logAktivitas('Generate Tagihan Tetap', "Generate tagihan tetap bulan $bulan tahun $tahun");

        return redirect()->route('tagihan.index.tetap')->with('success', "Tagihan bulan $bulan tahun $tahun berhasil dibuat!");
    }

    public function createTidakTetap()
    {
        $warga = Warga::where('jenis_retribusi', 'tidak_tetap')->get();
        $tarif = TarifRetribusi::all();
        return view('tagihan.create_tidak_tetap', compact('warga', 'tarif'));
    }

    public function storeTidakTetap(Request $request)
    {
        $request->validate([
            'NIK' => 'required|exists:warga,NIK',
            'tarif' => 'required|numeric',
            'volume' => 'required|numeric',
        ]);

        // Hitung total tagihan
        $total_tagihan = $request->tarif * $request->volume;

        // Simpan tagihan baru dan langsung ajukan ke Kepala Dinas
        Tagihan::create([
            'NIK' => $request->NIK,
            'jenis_retribusi' => 'tidak_tetap',
            'tarif' => $request->tarif,
            'volume' => $request->volume,
            'total_tagihan' => $total_tagihan,
            'tanggal_tagihan' => now(), // Set tanggal otomatis ke hari ini
            'status' => 'diajukan', // Langsung ubah status menjadi "diajukan"
        ]);

        logAktivitas('Buat Tagihan Tidak Tetap', "Tagihan untuk NIK {$request->NIK} berhasil dibuat");

        return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan Tidak Tetap Berhasil Dibuat dan Diajukan ke Kepala Dinas');
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

    public function generateMidtransSnapUrl($tagihan)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        // Buat order_id unik
        $order_id = 'INV-' . $tagihan->id . '-' . time();

        // Hitung jumlah tagihan
        $gross_amount = ($tagihan->jenis_retribusi == 'tetap')
            ? $tagihan->tarif
            : $tagihan->tarif * $tagihan->volume;

        // Waktu expired (misalnya 24 jam)
        $now = now();
        $expiredAt = $now->copy()->addHours(24);

        // Data transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
            ],
            'customer_details' => [
                'first_name' => $tagihan->warga->pengguna->nama,
                'email' => $tagihan->warga->pengguna->email,
                'phone' => $tagihan->warga->pengguna->no_hp,
            ],
            'expiry' => [
                'start_time' => $now->format("Y-m-d H:i:s O"),
                'unit' => 'hour',
                'duration' => 24,
            ],
        ];

        // Generate Snap URL
        $snapUrl = Snap::createTransaction($params)->redirect_url;

        // Simpan transaksi
        Transaksi::create([
            'order_id' => $order_id,
            'tagihan_id' => $tagihan->id,
            'amount' => $gross_amount,
            'status' => 'pending',
            'snap_url' => $snapUrl,
            'expired_at' => $expiredAt, // Tambahkan ini
        ]);

        return $snapUrl;
    }

    // Fungsi untuk menampilkan daftar tagihan di Kepala Dinas
    public function daftarTagihan()
    {
        // Ambil data tagihan tetap yang diajukan
        $tagihanTetap = Tagihan::where('status', 'diajukan')
            ->where('jenis_retribusi', 'tetap')
            ->with('warga.pengguna')
            ->get();

        // Ambil data tagihan tidak tetap yang diajukan
        $tagihanTidakTetap = Tagihan::where('status', 'diajukan')
            ->where('jenis_retribusi', 'tidak_tetap')
            ->with('warga.pengguna')
            ->get();

        return view('tagihan.daftar_tagihan', compact('tagihanTetap', 'tagihanTidakTetap'));
    }

    // Fungsi untuk menyetujui tagihan dan mengirim WhatsApp
    public function setujuiTagihan(Request $request)
    {
        $apiKey = env('FONNTE_API_KEY'); // API Key Fonnte

        $tagihan = Tagihan::whereIn('id', $request->tagihan_id)->get();

        foreach ($tagihan as $t) {
            // Update status tagihan menjadi 'disetujui'
            $t->update(['status' => 'disetujui']);

            // Ambil nomor HP warga
            $no_hp = $t->warga->pengguna->no_hp;
            $nama = $t->warga->pengguna->nama;
            $NIK = $t->warga->NIK;
            $tarif = $t->tarif;

            // Ambil Snap URL Midtrans
            $snapUrl = $this->generateMidtransSnapUrl($t);

            $transaksi = $t->transaksi()->latest()->first();

            // Cek apakah tagihan tetap atau tidak tetap
            if ($t->jenis_retribusi == 'tetap') {
                // Tagihan Tetap
                $bulan = $t->bulan;
                $tahun = $t->tahun;
                $nama_bulan = date('F', strtotime("$tahun-$bulan-01"));
                $total_tagihan = number_format($tarif, 0, ',', '.'); // Tidak ada perhitungan volume

                $pesan = "Halo *$nama* *$NIK*,\n\nTagihan Anda sebesar *Rp$total_tagihan* untuk periode *$nama_bulan, $tahun*.\n\nSilakan lakukan pembayaran melalui link berikut:\n$snapUrl\n\nTerima kasih.";
            } else {
                // Tagihan Tidak Tetap
                $volume = $t->volume; // Misalnya: 10 kubik
                $tanggal_tagihan = date('d F Y', strtotime($t->tanggal_tagihan));

                // Hitung total tagihan = tarif * volume
                $total_tagihan = $tarif * $volume;
                $total_tagihan_rp = number_format($total_tagihan, 0, ',', '.');

                $pesan = "Halo *$nama* *$NIK*,\n\nTagihan Anda sebesar *Rp$total_tagihan_rp* berdasarkan pemakaian *$volume kubik* pada tanggal *$tanggal_tagihan*.\n\nSilakan lakukan pembayaran melalui link berikut:\n$snapUrl\n\nTerima kasih.";
            }

            // Kirim pesan ke WhatsApp menggunakan Fonnte
            Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $no_hp,
                        'message' => $pesan,
                    ]);
        }

        logAktivitas('Setujui Tagihan', "Menyetujui tagihan untuk NIK $NIK dengan order id: {$transaksi->order_id}");

        return redirect()->back()->with('success', 'Tagihan telah disetujui dan Snap URL dikirim ke warga melalui WhatsApp.');
    }

    // Laporan tagihan (role keuangan)
    public function laporanTagihan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status');

        // Ambil semua transaksi dengan filter
        $transaksi = Transaksi::with(['tagihan.warga.pengguna'])
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereHas('tagihan', function ($query) use ($tahun) {
                    $query->where(function ($q) use ($tahun) {
                        $q->where('tahun', $tahun)
                            ->orWhereYear('tanggal_tagihan', $tahun);
                    });
                });
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereHas('tagihan', function ($query) use ($bulan) {
                    $query->where(function ($q) use ($bulan) {
                        $q->where('bulan', $bulan)
                            ->orWhereMonth('tanggal_tagihan', $bulan);
                    });
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'menunggak') {
                    $query->where(function ($q) {
                        $q->where('status', 'pending')
                            ->whereRaw('created_at <= NOW() - INTERVAL 30 DAY');
                    });
                } else {
                    $query->where('status', $status);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Tambahkan status menunggak
        $transaksi = $transaksi->map(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
            return $t;
        });

        // Ambil ID tagihan dari transaksi yang sudah difilter
        $tagihanIds = $transaksi->pluck('tagihan_id')->unique();

        // Tagihan Tetap yang terkait transaksi (filtered)
        $tagihanTetap = Tagihan::whereIn('id', $tagihanIds)
            ->where('jenis_retribusi', 'tetap')
            ->with('warga.pengguna')
            ->get();

        // Ambil semua tagihan tidak tetap sesuai bulan & tahun jika ada
        $tagihanTidakTetap = Tagihan::where('jenis_retribusi', 'tidak_tetap')
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereMonth('tanggal_tagihan', $bulan);
            })
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('tanggal_tagihan', $tahun);
            })
            ->with('warga.pengguna', 'transaksi')
            ->get();

        // Filter berdasarkan status transaksi
        $tagihanTidakTetap = $tagihanTidakTetap->filter(function ($tagihan) use ($status) {
            if (!$status)
                return true;

            $transaksi = $tagihan->transaksi ?? collect(); 

            foreach ($transaksi as $trx) {
                if ($status === 'menunggak') {
                    if ($trx->status === 'pending' && $trx->created_at->addDays(30)->lt(now())) {
                        return true;
                    }
                } else {
                    if ($trx->status === $status) {
                        return true;
                    }
                }
            }
            return false;
        });

        $menunggak = $transaksi->where('status_menunggak', true)->count();

        return view('tagihan.laporan', compact(
            'tagihanTetap',
            'tagihanTidakTetap',
            'transaksi',
            'menunggak',
        ));
    }

    // laporan Transaksi (role keuangan)
    public function cetak(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

        // Status dari request (settlement, pending, menunggak)
        $statusInput = $request->input('status');

        // Ubah status ke versi tampilan
        $status = match ($statusInput) {
            'settlement' => 'lunas',
            'pending' => 'belum bayar',
            'menunggak' => 'menunggak',
            default => null,
        };

        // Ambil transaksi sesuai tahun & bulan
        $transaksi = Transaksi::with(['tagihan.warga.pengguna'])
            ->whereHas('tagihan', function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);
                if (!empty($bulan)) {
                    $query->where('bulan', $bulan);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Tandai status menunggak
        $transaksi = $transaksi->map(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
            return $t;
        });

        // Ambil tagihan tetap dan tidak tetap
        $tagihanTetap = Tagihan::with(['warga.pengguna'])
            ->where('jenis_retribusi', 'tetap')
            ->where('tahun', $tahun)
            ->when($bulan, fn($q) => $q->where('bulan', $bulan))
            ->get();

        $tagihanTidakTetap = Tagihan::where('jenis_retribusi', 'tidak_tetap')
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_tagihan', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal_tagihan', $tahun))
            ->with('warga.pengguna')
            ->get();

        // Total pembayaran hanya untuk yang sukses
        $total_pembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        $pdf = Pdf::loadView('tagihan.laporan_cetak', compact(
            'transaksi',
            'tagihanTetap',
            'tagihanTidakTetap',
            'total_pembayaran',
            'bulan',
            'tahun',
            'status'
        ))->setPaper('A4', 'landscape');

        logAktivitas('Mencetak laporan transaksi');

        return $pdf->stream("Laporan_Keuangan_{$tahun}" . ($bulan ? "_$bulan" : "") . ".pdf");
    }
}
