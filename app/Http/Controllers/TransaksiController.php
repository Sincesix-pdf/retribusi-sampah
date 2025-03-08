<?php

namespace App\Http\Controllers;

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

    public function handleWebhook(Request $request)
    {
        Log::info("Webhook received", $request->all()); // Logging untuk debug

        $orderId = $request->order_id;
        $status = $request->transaction_status;

        // Cari transaksi berdasarkan order_id
        $transaksi = Transaksi::where('order_id', $orderId)->first();

        if ($transaksi) {
            // Update status transaksi di database
            $transaksi->update(['status' => $status]);

            // Jika status settlement (pembayaran sukses), update juga tagihan
            if ($status == 'settlement') {
                $transaksi->tagihan->update(['status' => 'settlement']);
            }
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    public function cekStatus($order_id)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $base64Auth = base64_encode($serverKey . ':');

        // Request ke Midtrans untuk cek status transaksi
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $base64Auth,
            'Accept' => 'application/json',
        ])->get("https://api.sandbox.midtrans.com/v2/$order_id/status");

        if ($response->successful()) {
            $data = $response->json();
            $status = $data['transaction_status'];

            // Cari transaksi berdasarkan order_id
            $transaksi = Transaksi::where('order_id', $order_id)->first();

            if ($transaksi) {
                if ($status == 'settlement') {
                    $transaksi->update(['status' => 'settlement']);
                    $transaksi->tagihan->update(['status' => 'settlement']); // Sesuai ENUM
                } elseif ($status == 'cancel' || $status == 'expire' || $status == 'failure') {
                    $transaksi->update(['status' => $status]);
                    $transaksi->tagihan->update(['status' => $status]);
                }

                return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
            }
        }

        return redirect()->back()->with('error', 'Gagal mengambil status transaksi.');
    }

}

