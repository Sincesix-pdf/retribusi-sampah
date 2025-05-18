<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
class TransaksiController extends Controller
{
    public function index(Request $request)
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

        // Menambahkan status menunggak
        $transaksi = $transaksi->map(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
            return $t;
        });

        // Pisahkan transaksi Tetap dan Tidak Tetap
        $transaksiTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap';
        });

        $transaksiTidakTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tidak_tetap';
        });

        // Statistik
        $sudahBayar = $transaksi->where('status', 'settlement')->count();
        $belumBayar = $transaksi->where('status', 'pending')->count();
        $menunggak = $transaksi->where('status_menunggak', true)->count();
        $totalPembayaran = $transaksi->where('status', 'settlement')->sum('amount');
        $totalTransaksi = Transaksi::where('status', 'settlement')->count();

        logAktivitas('Melihat daftar transaksi');

        return view('transaksi.index', compact(
            'transaksi',
            'transaksiTetap',
            'transaksiTidakTetap',
            'sudahBayar',
            'belumBayar',
            'menunggak',
            'totalPembayaran',
            'totalTransaksi'
        ));
    }

    public function cetakLaporanTransaksi(Request $request)
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

        // Menambahkan status menunggak
        $transaksi = $transaksi->map(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status != 'settlement';
            return $t;
        });

        // Pisahkan transaksi Tetap dan Tidak Tetap
        $transaksiTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap';
        });

        $transaksiTidakTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tidak_tetap';
        });

        // Filter berdasarkan status, termasuk 'menunggak'
        if ($status == 'menunggak') {
            $transaksi = $transaksi->filter(fn($t) => $t->status_menunggak);
        } elseif ($status) {
            $transaksi = $transaksi->where('status', $status);
        }

        // Total pembayaran hanya untuk status 'settlement'
        $total_pembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        $pdf = Pdf::loadView('transaksi.rekap_pdf', compact(
            'transaksiTetap',
            'transaksiTidakTetap',
            'transaksi',
            'total_pembayaran',
            'bulan',
            'tahun',
            'status'
        ))->setPaper('A4', 'landscape');

        logAktivitas('Mencetak laporan transaksi');

        return $pdf->stream("Laporan_Keuangan_{$tahun}" . ($bulan ? "_$bulan" : "") . ".pdf");
    }

    //melihat riwayat pembayaran
    public function history()
    {
        $nik = Auth::user()->warga->NIK;

        $transaksi = Transaksi::whereHas('tagihan', function ($query) use ($nik) {
            $query->whereHas('warga', function ($query) use ($nik) {
                $query->where('NIK', $nik);
            });
        })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status != 'settlement';
                return $t;
            });

        logAktivitas('Melihat riwayat transaksi');

        return view('transaksi.history', compact('transaksi'));
    }

    //handle status payment
    public function handleWebhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            $transaksi = Transaksi::where('order_id', $request->order_id)->first();

            if (!$transaksi) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            if ($request->transaction_status === 'settlement') {
                $transaksi->update(['status' => 'settlement']);
                $transaksi->update(['created_at' => Carbon::now('Asia/Jakarta')]);
                $transaksi->update(['updated_at' => Carbon::now('Asia/Jakarta')]);

                // Catat log aktivitas
                $nik = $transaksi->tagihan->warga->NIK;
                $penggunaId = $transaksi->tagihan->warga->pengguna->id ?? null;

                logAktivitas(
                    'Pembayaran berhasil',
                    "NIK $nik berhasil melakukan pembayaran untuk order id {$transaksi->order_id}",
                    $penggunaId
                );

                // Kirim notifikasi WhatsApp
                $this->sendPaymentNotification($transaksi);
            } elseif (in_array($request->transaction_status, ['cancel', 'expire', 'failure'])) {
                $transaksi->update(['status' => 'cancel']);
            }
        }

        return response()->json(['message' => 'Webhook Berhasil Cihuyy'], 200);
    }

    private function sendPaymentNotification($transaksi)
    {
        $apiKey = env('FONNTE_API_KEY');
        $warga = $transaksi->tagihan->warga;
        $no_hp = $warga->pengguna->no_hp;
        $nama = $warga->pengguna->nama;
        $invoice = $transaksi->order_id;
        $amount = number_format($transaksi->amount, 0, ',', '.');
        $tanggal = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i:s');

        $pesan = "
============================
        *BUKTI PEMBAYARAN*
============================
Invoice: *$invoice*
Nama: *$nama*
Jumlah: *Rp$amount*
Tanggal: *$tanggal*
============================
Terima kasih! Pembayaran Anda telah berhasil. 
Harap simpan bukti pembayaran ini.
";

        Http::withHeaders(['Authorization' => $apiKey])
            ->post('https://api.fonnte.com/send', [
                'target' => $no_hp,
                'message' => $pesan,
            ]);
    }

    public function sendReminder($id)
    {
        $transaksi = Transaksi::with('tagihan.warga.pengguna')->findOrFail($id);

        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibayar atau gagal.');
        }

        // Cek apakah Snap URL expired
        if ($transaksi->expired_at && $transaksi->expired_at->isPast()) {
            // Re-generate Snap URL
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
            Config::$is3ds = env('MIDTRANS_IS_3DS', true);

            $tagihan = $transaksi->tagihan;
            $user = $tagihan->warga->pengguna;

            $newOrderId = 'INV-' . $tagihan->id . '-' . time() . rand(100, 999);
            $grossAmount = ($tagihan->jenis_retribusi === 'tetap')
                ? $tagihan->tarif
                : $tagihan->tarif * $tagihan->volume;

            $params = [
                'transaction_details' => [
                    'order_id' => $newOrderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $user->nama,
                    'email' => $user->email,
                    'phone' => $user->no_hp,
                ],
                'expiry' => [
                    'start_time' => now()->format("Y-m-d H:i:s O"),
                    'unit' => 'hour',
                    'duration' => 24,
                ],
            ];

            $newSnapUrl = Snap::createTransaction($params)->redirect_url;

            $transaksi->update([
                'order_id' => $newOrderId,
                'snap_url' => $newSnapUrl,
                'expired_at' => now()->addHours(24),
            ]);
        }

        // Kirim pesan WhatsApp
        $user = $transaksi->tagihan->warga->pengguna;
        $bulanTagihan = Carbon::create(null, $transaksi->tagihan->bulan)->locale('id')->isoFormat('MMMM');
        $tahunTagihan = $transaksi->tagihan->tahun;
        $jumlah = number_format($transaksi->amount, 0, ',', '.');
        $no_hp = $user->no_hp;

        $message = "*PENGINGAT PEMBAYARAN TAGIHAN*\n\n" .
            "Yth. *{$user->nama}*,\n" .
            "Tagihan Anda untuk bulan *{$bulanTagihan} {$tahunTagihan}* masih *belum dibayar*.\n\n" .
            "*Jumlah:* Rp{$jumlah}\n" .
            "*Nomor Invoice:* {$transaksi->order_id}\n" .
            "*Link Pembayaran:* {$transaksi->snap_url}\n\n" .
            "Segera lakukan pembayaran sebelum jatuh tempo. Terima kasih.";

        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_API_KEY'),
        ])->post('https://api.fonnte.com/send', [
                    'target' => $no_hp,
                    'message' => $message,
                ]);

        if ($response->successful()) {
            logAktivitas("Mengirim pengingat pembayaran ke {$user->nama} untuk order_id: {$transaksi->order_id}");
            return redirect()->back()->with('success', 'Pengingat pembayaran telah dikirim.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pengingat.');
        }
    }

    public function grafikPendapatan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
    
        // Ambil semua transaksi yang 'settlement' dan sudah dimuat relasinya
        $transaksi = Transaksi::with(['tagihan.warga'])
            ->where('status', 'settlement')
            ->whereHas('tagihan', function ($query) use ($tahun, $bulan) {
                $query->where(function ($q) use ($tahun) {
                    $q->where('tahun', $tahun)
                      ->orWhereYear('tanggal_tagihan', $tahun);
                });
    
                if ($bulan) {
                    $query->where(function ($q) use ($bulan) {
                        $q->where('bulan', $bulan)
                          ->orWhereMonth('tanggal_tagihan', $bulan);
                    });
                }
            })
            ->get();
    
        // Pendapatan per bulan
        $perBulan = $transaksi->groupBy(function ($item) {
            $tagihan = $item->tagihan;
            if ($tagihan->tanggal_tagihan) {
                return Carbon::parse($tagihan->tanggal_tagihan)->translatedFormat('F');
            } elseif ($tagihan->bulan && $tagihan->tahun) {
                return Carbon::createFromDate($tagihan->tahun, $tagihan->bulan, 1)->translatedFormat('F');
            } else {
                return 'Tidak Diketahui';
            }
        })->map(function ($group) {
            return $group->sum('amount');
        });
    
        // Pendapatan per jenis retribusi
        $perJenis = $transaksi->groupBy(function ($item) {
            return $item->tagihan->jenis_retribusi;
        })->map(function ($group) {
            return $group->sum('amount');
        });
    
        // Jumlah warga membayar per bulan
        $perWargaBayar = $transaksi->groupBy(function ($item) {
            $tagihan = $item->tagihan;
            if ($tagihan->tanggal_tagihan) {
                return Carbon::parse($tagihan->tanggal_tagihan)->translatedFormat('F');
            } elseif ($tagihan->bulan && $tagihan->tahun) {
                return Carbon::createFromDate($tagihan->tahun, $tagihan->bulan, 1)->translatedFormat('F');
            } else {
                return 'Tidak Diketahui';
            }
        })->map(function ($group) {
            return $group->count(); // Jumlah transaksi
        });
    
        return view('grafik.grafik_pendapatan', compact(
        'perBulan',
        'perJenis',
        'perWargaBayar'));
    }
    
    public function grafikPersebaran(Request $request)
    {
        $kecamatanId = $request->input('kecamatan', null);

        // Ambil semua kecamatan untuk dropdown
        $daftarKecamatan = Kecamatan::all();

        // Ambil data kelurahan berdasarkan kecamatan
        if ($kecamatanId) {
            $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)->withCount('warga')->get();
            $namaKecamatan = Kecamatan::find($kecamatanId)?->nama ?? 'Kecamatan tidak ditemukan';
        } else {
            $kelurahans = Kelurahan::withCount('warga')->get();
            $namaKecamatan = 'Semua Kecamatan';
        }

        logAktivitas("Melihat grafik persebaran untuk {$namaKecamatan}", "Melihat grafik persebaran untuk {$namaKecamatan}");

        return view('grafik.grafik_persebaran', compact(
            'kelurahans',
             'kecamatanId',
              'namaKecamatan',
               'daftarKecamatan'));
    }
}

