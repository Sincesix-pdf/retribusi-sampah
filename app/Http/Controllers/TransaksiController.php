<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Warga;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tagihan;
use Midtrans\Notification;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status'); // Ambil input status dari filter

        $transaksi = Transaksi::with(['tagihan.warga.pengguna'])
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereHas('tagihan', function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                });
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereHas('tagihan', function ($query) use ($bulan) {
                    $query->where('bulan', $bulan);
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $sudahBayar = $transaksi->where('status', 'settlement')->count();
        $belumBayar = $transaksi->where('status', 'pending')->count();
        $totalPembayaran = $transaksi->where('status', 'settlement')->sum('amount');
        $totalTransaksi = $transaksi->count();

        return view('transaksi.index', compact(
            'transaksi',
            'sudahBayar',
            'belumBayar',
            'totalPembayaran',
            'totalTransaksi'
        ));
    }

    public function cetakLaporan(Request $request)
    {
        $bulan = $request->input('bulan'); // null = semua bulan
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status'); // Optional filter status

        // Ambil semua status jika status kosong, atau hanya yang dipilih
        $transaksi = Transaksi::with(['tagihan.warga'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->whereIn('status', ['settlement', 'pending']);
            })
            ->whereHas('tagihan', function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);

                if (!empty($bulan)) {
                    $query->where('bulan', $bulan);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Total hanya dari transaksi yang lunas
        $total_pembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        $pdf = Pdf::loadView('transaksi.rekap_pdf', compact('transaksi', 'total_pembayaran', 'bulan', 'tahun', 'status'));

        return $pdf->stream("Laporan_Keuangan_{$tahun}" . ($bulan ? "_$bulan" : "") . ".pdf");
    }

    public function history()
    {
        // Ambil NIK warga yang sedang login
        $nik = Auth::user()->warga->NIK;

        // Ambil semua transaksi berdasarkan NIK yang terkait dengan tagihan dan warga
        $transaksi = Transaksi::whereHas('tagihan', function ($query) use ($nik) {
            $query->whereHas('warga', function ($query) use ($nik) {
                $query->where('NIK', $nik);
            });
        })->orderBy('created_at', 'desc')->get();

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

                // Kirim notifikasi WhatsApp
                $this->sendWhatsAppNotification($transaksi);
            } elseif (in_array($request->transaction_status, ['cancel', 'expire', 'failure'])) {
                $transaksi->update(['status' => 'cancel']);
            }
        }

        return response()->json(['message' => 'Webhook Berhasil Cihuyy'], 200);
    }

    private function sendWhatsAppNotification($transaksi)
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
            return redirect()->back()->with('success', 'Pengingat pembayaran telah dikirim.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pengingat.');
        }
    }

    public function grafikPendapatan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');

        $query = Transaksi::where('transaksi.status', 'settlement')
            ->join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id');

        // Total pendapatan per bulan
        $perBulan = Transaksi::selectRaw('tagihan.bulan, tagihan.tahun, SUM(transaksi.amount) as total')
            ->join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id')
            ->where('transaksi.status', 'settlement')
            ->when($bulan, fn($q) => $q->where('tagihan.bulan', $bulan))
            ->where('tagihan.tahun', $tahun)
            ->groupBy('tagihan.bulan', 'tagihan.tahun')
            ->orderBy('tagihan.tahun')
            ->orderBy('tagihan.bulan')
            ->get()
            ->mapWithKeys(fn($item) => [
                \Carbon\Carbon::create(null, (int) $item->bulan)->translatedFormat('F') => $item->total
            ]);

        // Semua jenis retribusi yang wajib ditampilkan
        $allJenis = ['tetap', 'tidak_tetap'];

        // Data pendapatan per jenis
        $jenisRaw = $query->selectRaw('tagihan.jenis_retribusi, SUM(transaksi.amount) as total')
            ->groupBy('tagihan.jenis_retribusi')
            ->get()
            ->pluck('total', 'jenis_retribusi');

        $perJenis = collect($allJenis)->mapWithKeys(function ($jenis) use ($jenisRaw) {
            $label = ucwords(str_replace('_', ' ', $jenis)); // Tetap, Tidak Tetap
            return [$label => $jenisRaw[$jenis] ?? 0];
        });

        // Jumlah warga membayar per bulan
        $perWargaBayar = Transaksi::selectRaw('tagihan.bulan, tagihan.tahun, COUNT(DISTINCT tagihan.NIK) as jumlah')
            ->join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id')
            ->where('transaksi.status', 'settlement')
            ->when($bulan, fn($q) => $q->where('tagihan.bulan', $bulan))
            ->where('tagihan.tahun', $tahun)
            ->groupBy('tagihan.bulan', 'tagihan.tahun')
            ->orderBy('tagihan.tahun')
            ->orderBy('tagihan.bulan')
            ->get()
            ->mapWithKeys(fn($item) => [
                \Carbon\Carbon::create(null, (int) $item->bulan)->translatedFormat('F') => $item->jumlah
            ]);

        return view('grafik.grafik_pendapatan', compact(
            'perBulan',
            'perJenis',
            'perWargaBayar'
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

        return view('grafik.grafik_persebaran', compact('kelurahans', 'kecamatanId', 'namaKecamatan', 'daftarKecamatan'));
    }


}

