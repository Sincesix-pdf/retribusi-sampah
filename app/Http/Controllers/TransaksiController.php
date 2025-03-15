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
}

