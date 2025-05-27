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
        $tagihanDiajukan = Tagihan::where('jenis_retribusi', 'retasi')
            ->where('status', 'diajukan')
            ->when($tanggal_tagihan, function ($query) use ($tanggal_tagihan) {
                return $query->whereDate('tanggal_tagihan', $tanggal_tagihan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        $tagihanDisetujui = Tagihan::where('jenis_retribusi', 'retasi')
            ->where('status', 'disetujui')
            ->when($tanggal_tagihan, function ($query) use ($tanggal_tagihan) {
                return $query->whereDate('tanggal_tagihan', $tanggal_tagihan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        $tagihanDitolak = Tagihan::where('jenis_retribusi', 'retasi')
            ->where('status', 'ditolak')
            ->when($tanggal_tagihan, function ($query) use ($tanggal_tagihan) {
                return $query->whereDate('tanggal_tagihan', $tanggal_tagihan);
            })
            ->with(['warga.pengguna', 'warga.jenisLayanan'])
            ->get();

        return view('tagihan.index_tidak_tetap', compact(
            'tagihanDiajukan',
            'tagihanDisetujui',
            'tagihanDitolak',
        ));
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
        $warga = Warga::where('jenis_retribusi', 'retasi')->get();
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
            'jenis_retribusi' => 'retasi',
            'tarif' => $request->tarif,
            'volume' => $request->volume,
            'total_tagihan' => $total_tagihan,
            'tanggal_tagihan' => now(), // Set tanggal otomatis ke hari ini
            'status' => 'diajukan', // Langsung ubah status menjadi "diajukan"
        ]);

        logAktivitas('Buat Tagihan Retasi', "Tagihan untuk NIK {$request->NIK} berhasil dibuat");

        return redirect()->route('tagihan.index.tidak_tetap')->with('success', 'Tagihan Retasi Berhasil Dibuat dan Diajukan ke Kepala Dinas');
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
            ->where('jenis_retribusi', 'retasi')
            ->with('warga.pengguna')
            ->paginate(10);

        return view('tagihan.daftar_tagihan', compact(
            'tagihanTetap',
            'tagihanTidakTetap'
        ));
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
                $total_tagihan = number_format($tarif, 0, ',', '.');

                $pesan = "Halo *$nama* *$NIK*,\n\nTagihan Anda sebesar *Rp$total_tagihan* untuk periode *$nama_bulan, $tahun*.\n\nSilakan lakukan pembayaran melalui link berikut:\n$snapUrl\n\nTerima kasih.";
            } else {
                // Tagihan Tidak Tetap
                $volume = $t->volume;
                $tanggal_tagihan = date('d F Y', strtotime($t->tanggal_tagihan));

                // Hitung total tagihan = tarif * volume
                $total_tagihan = $tarif * $volume;
                $total_tagihan_rp = number_format($total_tagihan, 0, ',', '.');

                $pesan = "Halo *$nama* *$NIK*,\n\nTagihan Anda sebesar *Rp$total_tagihan_rp* berdasarkan pemakaian *$volume kubik* pada tanggal *$tanggal_tagihan*.\n\nSilakan lakukan pembayaran melalui link berikut:\n$snapUrl\n\nTerima kasih.";
            }

            // Kirim pesan ke WhatsApp melalui Fonnte
            Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $no_hp,
                        'message' => $pesan,
                    ]);
        }
        // delay untuk API WA
        sleep(5);

        logAktivitas('Setujui Tagihan', "Menyetujui tagihan untuk NIK $NIK dengan order id: {$transaksi->order_id}");

        return redirect()->back()->with('success', 'Tagihan telah disetujui dan dikirim ke warga melalui WhatsApp.');
    }

    // Fungsi untuk menolak tagihan
    public function tolakTagihan(Request $request)
    {
        $ids = explode(',', $request->tagihan_ids);
        $alasan = $request->alasan;

        $tagihan = Tagihan::whereIn('id', $ids)->get();

        foreach ($tagihan as $t) {
            $t->update([
                'status' => 'ditolak',
                'keterangan' => $alasan,
            ]);
        }

        logAktivitas('Tolak Tagihan', "Menolak tagihan ID: " . implode(', ', $ids) . " dengan alasan: $alasan");

        return redirect()->back()->with('success', 'Tagihan berhasil ditolak.');
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
        $tagihanTidakTetap = Tagihan::where('jenis_retribusi', 'retasi')
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

        logAktivitas('Melihat laporan tagihan');
        return view('tagihan.laporan', compact(
            'tagihanTetap',
            'tagihanTidakTetap',
            'transaksi',
            'menunggak',
        ));
    }

    // laporan tagihan (role keuangan)
    public function cetakLaporanTagihan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $statusInput = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        // Ambil semua transaksi yang terkait filter
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
            ->when($statusInput, function ($query) use ($statusInput) {
                if ($statusInput === 'menunggak') {
                    $query->where(function ($q) {
                        $q->where('status', 'pending')
                            ->whereRaw('created_at <= NOW() - INTERVAL 30 DAY');
                    });
                } else {
                    $query->where('status', $statusInput);
                }
            })
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate('created_at', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate('created_at', '<=', $tanggalSelesai);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
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

        // Tagihan Tidak Tetap (Retasi) yang terkait transaksi (filtered)
        $tagihanTidakTetap = Tagihan::whereIn('id', $tagihanIds)
            ->where('jenis_retribusi', 'retasi')
            ->with('warga.pengguna')
            ->get();

        $total_pembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        $status = match ($statusInput) {
            'settlement' => 'lunas',
            'pending' => 'belum bayar',
            'menunggak' => 'menunggak',
            default => null,
        };

        logAktivitas(
            'Mencetak laporan tagihan',
            'Laporan tagihan dicetak pada ' . now()->format('d-m-Y H:i:s')
        );

        return Pdf::loadView('tagihan.laporan_cetak', compact(
            'tagihanTetap',
            'tagihanTidakTetap',
            'transaksi',
            'total_pembayaran',
            'bulan',
            'tahun',
            'status',
            'tanggalMulai',
            'tanggalSelesai'
        ))->setPaper('A4', 'landscape')->stream("Laporan_Tagihan_{$tahun}" . ($bulan ? "_$bulan" : "") . ".pdf");
    }
}
