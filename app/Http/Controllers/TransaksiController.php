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
    /**
     * Hitung akumulasi tunggakan, total tunggakan, dan rincian tunggakan dari koleksi transaksi.
     *
     * @param \Illuminate\Support\Collection $transaksi
     * @return array
     */
    private function hitungTunggakan($transaksi)
    {
        $tunggakan = $transaksi->filter(function ($t) {
            return $t->created_at->addDays(30)->lt(now()) && $t->status != 'settlement';
        });

        $jumlahTunggakan = $tunggakan->count();
        $totalTunggakan = $tunggakan->sum('amount');
        $rincianTunggakan = $tunggakan->map(function ($t) {
            if ($t->tagihan && $t->tagihan->bulan && $t->tagihan->tahun) {
                return date('F Y', mktime(0, 0, 0, $t->tagihan->bulan, 1, $t->tagihan->tahun));
            }
            return 'Periode Tidak Diketahui';
        })->values();

        return [
            'jumlahTunggakan' => $jumlahTunggakan,
            'totalTunggakan' => $totalTunggakan,
            'rincianTunggakan' => $rincianTunggakan,
        ];
    }
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status');
        $kecamatan = Kecamatan::all();
        $kecamatan_id = $request->input('kecamatan_id');
        $kelurahan_id = $request->input('kelurahan_id');


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

        // Menambahkan status menunggak dan akumulasi tunggakan per NIK
        $transaksi = $transaksi->map(function ($t) use ($transaksi) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';

            // Hitung akumulasi tunggakan untuk NIK yang sama
            $nik = $t->tagihan->warga->NIK ?? null;
            if ($nik) {
                $t->akumulasi_tunggakan = $transaksi->filter(function ($item) use ($nik) {
                    return ($item->tagihan->warga->NIK ?? null) == $nik
                        && $item->created_at->addDays(30)->lt(now())
                        && $item->status !== 'settlement';
                })->count();
            } else {
                $t->akumulasi_tunggakan = 0;
            }
            return $t;
        });

        // Pisahkan transaksi Tetap dan Tidak Tetap
        $transaksiTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap';
        });

        $transaksiTidakTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'retasi';
        });

        // Statistik
        $totalTransaksi = $transaksi->count();
        $sudahBayar = $transaksi->where('status', 'settlement')->count();
        $belumBayar = $transaksi->filter(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
            return $t->status === 'pending' && !$t->status_menunggak;
        })->count();
        $menunggak = $transaksi->where('status_menunggak', true)->count();
        $totalPembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        // Hitung tunggakan
        $tunggakan = $this->hitungTunggakan($transaksi);

        logAktivitas('Melihat daftar transaksi');

        return view('transaksi.index', compact(
            'transaksi',
            'transaksiTetap',
            'transaksiTidakTetap',
            'sudahBayar',
            'belumBayar',
            'menunggak',
            'totalPembayaran',
            'totalTransaksi',
            'tunggakan',
            'kecamatan'
        ));
    }

    public function cetakLaporanTransaksi(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $kecamatan_id = $request->input('kecamatan_id');
        $kelurahan_id = $request->input('kelurahan_id');

        // Ambil semua transaksi dengan filter
        $transaksi = Transaksi::with(['tagihan.warga.kelurahan.kecamatan', 'tagihan.warga.pengguna'])
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
            ->when($status && $status !== 'menunggak', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate('updated_at', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate('updated_at', '<=', $tanggalSelesai);
            })
            ->when($kecamatan_id, function ($query) use ($kecamatan_id) {
                $query->whereHas('tagihan.warga.kelurahan', function ($q) use ($kecamatan_id) {
                    $q->where('kecamatan_id', $kecamatan_id);
                });
            })
            ->when($kelurahan_id, function ($query) use ($kelurahan_id) {
                $query->whereHas('tagihan.warga', function ($q) use ($kelurahan_id) {
                    $q->where('kelurahan_id', $kelurahan_id);
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Tambahin status menunggak
        $transaksi = $transaksi->map(function ($t) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status != 'settlement';
            return $t;
        });

        if ($status == 'menunggak') {
            $transaksi = $transaksi->filter(fn($t) => $t->status_menunggak);
        } elseif ($status) {
            $transaksi = $transaksi->where('status', $status);
        }

        $transaksiTetap = $transaksi->filter(fn($t) => $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap');
        $transaksiTidakTetap = $transaksi->filter(fn($t) => $t->tagihan && $t->tagihan->jenis_retribusi === 'retasi');

        $total_pembayaran = ($transaksiTetap->isEmpty() && $transaksiTidakTetap->isEmpty())
            ? 0
            : $transaksi->where('status', 'settlement')->sum('amount');

        $pdf = Pdf::loadView('transaksi.rekap_pdf', compact(
            'transaksiTetap',
            'transaksiTidakTetap',
            'transaksi',
            'total_pembayaran',
            'bulan',
            'tahun',
            'status',
            'tanggalMulai',
            'tanggalSelesai',
            'kecamatan_id',
            'kelurahan_id'
        ))->setPaper('A4', 'landscape');

        logAktivitas('Mencetak laporan transaksi', 'Laporan transaksi dicetak pada ' . now()->format('d-m-Y H:i:s'));

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
            ->sortByDesc(fn($t) => $t->status == 'pending');

        // Tambahkan status_menunggak dan akumulasi_tunggakan
        $transaksi = $transaksi->map(function ($t) use ($transaksi) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status != 'settlement';
            $nik = $t->tagihan->warga->NIK ?? null;
            if ($nik) {
                $t->akumulasi_tunggakan = $transaksi->filter(function ($item) use ($nik) {
                    return ($item->tagihan->warga->NIK ?? null) == $nik
                        && $item->created_at->addDays(30)->lt(now())
                        && $item->status != 'settlement';
                })->count();
            } else {
                $t->akumulasi_tunggakan = 0;
            }
            return $t;
        });

        // Pakai helper
        $tunggakan = $this->hitungTunggakan($transaksi);

        // Pisahkan transaksi Tetap dan Tidak Tetap
        $transaksiTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap';
        });
        $transaksiTidakTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'retasi';
        });

        logAktivitas('Melihat riwayat transaksi');

        return view('transaksi.history', [
            'transaksi' => $transaksi,
            'transaksiTetap' => $transaksiTetap,
            'transaksiTidakTetap' => $transaksiTidakTetap,
            'jumlahTunggakan' => $tunggakan['jumlahTunggakan'],
            'totalTunggakan' => $tunggakan['totalTunggakan'],
            'rincianTunggakan' => $tunggakan['rincianTunggakan'],
        ]);
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

        $pesan = "Halo *$nama*, pembayaran berhasil ✅\n\nUntuk cetak nota bisa kunjungi website kami di:\nhttps://loosely-content-bird.ngrok-free.app";

        Http::withHeaders(['Authorization' => $apiKey])
            ->post('https://api.fonnte.com/send', [
                'target' => $no_hp,
                'message' => $pesan,
            ]);
    }

    public function sendReminder($id)
    {
        $transaksi = Transaksi::with('tagihan.warga.pengguna')->findOrFail($id);

        // Ambil user dan info tagihan
        $user = $transaksi->tagihan->warga->pengguna;
        $no_hp = $user->no_hp;

        // Cek status menunggak
        $status_menunggak = $transaksi->created_at->addDays(30)->lt(now()) && $transaksi->status !== 'settlement';

        if ($transaksi->status !== 'pending' && !$status_menunggak) {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibayar atau gagal.');
        }

        // Jika Snap URL expired, generate ulang
        if ($transaksi->expired_at && $transaksi->expired_at->isPast()) {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
            Config::$is3ds = env('MIDTRANS_IS_3DS', true);

            $tagihan = $transaksi->tagihan;

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

        // Jika menunggak, cari semua tunggakan NIK ini
        if ($status_menunggak) {
            $nik = $transaksi->tagihan->warga->NIK ?? null;
            $tunggakanTransaksi = Transaksi::with('tagihan')
                ->whereHas('tagihan', function ($q) use ($nik) {
                    $q->where('NIK', $nik);
                })
                ->where('status', 'pending')
                ->get()
                ->filter(function ($t) {
                    return $t->created_at->addDays(30)->lt(now());
                });

            $jumlahTunggakan = $tunggakanTransaksi->count();
            $totalTunggakan = $tunggakanTransaksi->sum('amount');

            // Rincian tunggakan dengan link pembayaran per transaksi
            $rincianTunggakan = $tunggakanTransaksi->map(function ($t) {
                if ($t->tagihan && $t->tagihan->bulan && $t->tagihan->tahun) {
                    $periode = date('F Y', mktime(0, 0, 0, $t->tagihan->bulan, 1, $t->tagihan->tahun));
                } else {
                    $periode = 'Periode Tidak Diketahui';
                }
                // Sertakan link pembayaran jika ada
                $link = $t->snap_url ? "\n$t->snap_url" : '';
                return "- $periode$link";
            })->implode("\n");

            $message = "*PERINGATAN TUNGGAKAN TAGIHAN*\n\n" .
                "Yth. *{$user->nama}*,\n" .
                "Anda memiliki *{$jumlahTunggakan} tunggakan* pembayaran retribusi sampah untuk bulan:\n" .
                "{$rincianTunggakan}\n\n" .
                "*Total yang harus dibayarkan:* Rp" . number_format($totalTunggakan, 0, ',', '.') . "\n\n" .
                "Segera lakukan pembayaran sebelum layanan dihentikan. Terima kasih.";
        } else {
            // Reminder biasa
            $bulanTagihan = Carbon::create(null, $transaksi->tagihan->bulan)->locale('id')->isoFormat('MMMM');
            $tahunTagihan = $transaksi->tagihan->tahun;
            $jumlah = number_format($transaksi->amount, 0, ',', '.');

            $message = "*PENGINGAT PEMBAYARAN TAGIHAN*\n\n" .
                "Yth. *{$user->nama}*,\n" .
                "Tagihan Anda untuk bulan *{$bulanTagihan} {$tahunTagihan}* masih *belum dibayar*.\n\n" .
                "*Jumlah:* Rp{$jumlah}\n" .
                "*Nomor Invoice:* {$transaksi->order_id}\n" .
                "*Link Pembayaran:* {$transaksi->snap_url}\n\n" .
                "Segera lakukan pembayaran sebelum jatuh tempo. Terima kasih.";
        }

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

    public function sendReminderMenunggak()
    {
        // Ambil semua transaksi pending
        $transaksiPending = Transaksi::with('tagihan.warga.pengguna')
            ->where('status', 'pending')
            ->get();

        // Filter manual transaksi yang menunggak (created_at lebih dari 30 hari)
        $transaksiMenunggak = $transaksiPending->filter(function ($transaksi) {
            return $transaksi->created_at->addDays(30)->lt(now());
        });

        // Loop dan kirim pengingat satu-satu
        foreach ($transaksiMenunggak as $transaksi) {
            $this->sendReminder($transaksi->id);
        }

        logAktivitas('Mengirim pengingat tunggakan secara massal');
        return back()->with('success', 'Pengingat berhasil dikirim ke semua warga yang menunggak.');
    }

    public function sendReminderPending()
    {
        $transaksiPending = Transaksi::with('tagihan.warga.pengguna')
            ->where('status', 'pending')
            ->get()
            ->filter(function ($t) {
                // Hitung status menunggak
                $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
                return !$t->status_menunggak;
            });

        foreach ($transaksiPending as $transaksi) {
            $this->sendReminder($transaksi->id);
        }

        logAktivitas('Mengirim pengingat ke semua transaksi pending (belum menunggak)');
        return back()->with('success', 'Pengingat berhasil dikirim ke semua warga yang belum membayar.');
    }

    // bayar di web
    public function bayarLangsung($id)
    {
        $transaksi = Transaksi::with('tagihan.warga.pengguna')->findOrFail($id);

        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibayar atau gagal.');
        }

        // Jika Snap URL expired, generate ulang
        if ($transaksi->expired_at && $transaksi->expired_at->isPast()) {
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

        return redirect($transaksi->snap_url);
    }
    public function cetakBukti($id)
    {
        $transaksi = Transaksi::with('tagihan.warga')->findOrFail($id);

        if ($transaksi->status !== 'settlement') {
            return redirect()->back()->with('error', 'Bukti hanya tersedia untuk transaksi yang berhasil.');
        }

        $jenisRetribusi = $transaksi->tagihan->jenis_retribusi ?? 'tetap';
        $bulanTagihan = null;
        $tahunTagihan = null;
        $volume = null;

        if ($jenisRetribusi === 'tetap') {
            $bulanTagihan = \Carbon\Carbon::create()->month((int) $transaksi->tagihan->bulan)->locale('id')->isoFormat('MMMM');
            $tahunTagihan = $transaksi->tagihan->tahun;
        } else {
            $volume = $transaksi->tagihan->volume;
        }

        $pdf = Pdf::loadView('transaksi.nota', compact([
            'transaksi',
            'bulanTagihan',
            'tahunTagihan',
            'jenisRetribusi',
            'volume'
        ]))->setPaper([0, 0, 595, 382]);

        return $pdf->stream("Bukti-Pembayaran-{$transaksi->order_id}.pdf");
    }

    // grafik laporan
    public function grafikPendapatan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

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

        // Pendapatan per bulan (hanya bulan yang ada data, urut naik)
        $perBulan = $transaksi->groupBy(function ($item) {
            $tagihan = $item->tagihan;
            if ($tagihan->tanggal_tagihan) {
                return (int) Carbon::parse($tagihan->tanggal_tagihan)->format('n');
            } elseif ($tagihan->bulan && $tagihan->tahun) {
                return (int) $tagihan->bulan;
            } else {
                return null;
            }
        })->filter(function ($group, $bulanNum) {
            return $bulanNum !== null && $bulanNum > 0;
        })->sortKeys()->mapWithKeys(function ($group, $bulanNum) {
            $namaBulan = Carbon::create()->month($bulanNum)->translatedFormat('F');
            return [$namaBulan => $group->sum('amount')];
        });

        // Pendapatan per jenis retribusi
        $perJenis = $transaksi->groupBy(function ($item) {
            return $item->tagihan->jenis_retribusi;
        })->map(function ($group) {
            return $group->sum('amount');
        });

        // Jumlah warga membayar per bulan (hanya bulan yang ada data, urut naik)
        $perWargaBayar = $transaksi->groupBy(function ($item) {
            $tagihan = $item->tagihan;
            if ($tagihan->tanggal_tagihan) {
                return (int) Carbon::parse($tagihan->tanggal_tagihan)->format('n');
            } elseif ($tagihan->bulan && $tagihan->tahun) {
                return (int) $tagihan->bulan;
            } else {
                return null;
            }
        })->filter(function ($group, $bulanNum) {
            return $bulanNum !== null && $bulanNum > 0;
        })->sortKeys()->mapWithKeys(function ($group, $bulanNum) {
            $namaBulan = Carbon::create()->month($bulanNum)->translatedFormat('F');
            return [$namaBulan => $group->count()];
        });

        // Kategori: Jumlah yang membayar dan total nominal bayar
        $perKategori = $transaksi->groupBy(function ($item) {
            return $item->tagihan->warga->kategori_retribusi ?? 'Tidak Diketahui';
        })->map(function ($group) {
            return [
                'jumlah_bayar' => $group->count(),
                'total_bayar' => $group->sum('amount'),
            ];
        });

        // Akumulasi total semua yang telah dibayar
        $totalSemuaBayar = $perKategori->sum('total_bayar');

        logAktivitas("Melihat grafik pendatapan", "Melihat grafik pendapatan untuk bulan {$bulan} tahun {$tahun}");

        return view('grafik.grafik_pendapatan', compact(
            'perBulan',
            'perJenis',
            'perWargaBayar',
            'perKategori',
            'totalSemuaBayar'
        ));
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
            'daftarKecamatan'
        ));
    }
    public function getKelurahan($kecamatan_id)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
        return response()->json($kelurahan);
    }
}