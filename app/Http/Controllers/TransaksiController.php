<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tagihan;
use Midtrans\Notification;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;
class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi dengan tagihan dan warga terkait
        $transaksi = Transaksi::with(['tagihan.warga'])->orderBy('created_at', 'desc')->get();

        return view('transaksi.index', compact('transaksi'));
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


}

