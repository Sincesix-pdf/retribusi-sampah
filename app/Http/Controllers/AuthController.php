<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Warga;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            logAktivitas('Login ke sistem', 'Berhasil login sebagai ' . $user->role->nama_role);

            // Redirect berdasarkan role
            switch ($user->role->nama_role) {
                case 'pendataan':
                    return redirect()->route('pendataan.index'); // Halaman pendataan
                case 'keuangan':
                    return redirect()->route('keuangan.index'); // Halaman keuangan
                case 'kepala_dinas':
                    return redirect()->route('kepala_dinas.index'); // Halaman kepala dinas
                case 'admin':
                    return redirect()->route('admin.index'); // Halaman admin
                case 'warga':
                    return redirect()->route('warga.index'); // Halaman warga
                default:
                    return redirect('/'); // Redirect default jika role tidak dikenali
            }
        }

        // Log jika gagal login
        logAktivitas('Login ke sistem', 'Gagal login dengan email: ' . $request->email);

        // Jika gagal, kembalikan ke halaman login dengan error
        return redirect()->back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        logAktivitas('Logout dari sistem', 'berhasil Logout dari sistem');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }

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

    public function dashboard(Request $request)
    {
        $jumlahWarga = Warga::count();
        $jumlahRetribusiTetap = Warga::where('jenis_retribusi', 'tetap')->count();
        $jumlahRetribusiTidakTetap = Warga::where('jenis_retribusi', 'retasi')->count();
        $jumlahPetugas = Pengguna::whereIn('role_id', [1, 2, 3, 4])->count();

        $role = Auth::user()->role->nama_role;

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

        // Ambil semua transaksi dengan tagihan yang sesuai filter
        $transaksi = Transaksi::with('tagihan.warga')
            ->whereHas('tagihan', function ($query) use ($tahun, $bulan) {
                $query->when($tahun, function ($q) use ($tahun) {
                    $q->where(function ($subQ) use ($tahun) {
                        $subQ->where('tahun', $tahun)
                            ->orWhereYear('tanggal_tagihan', $tahun);
                    });
                })
                    ->when($bulan, function ($q) use ($bulan) {
                        $q->where(function ($subQ) use ($bulan) {
                            $subQ->where('bulan', $bulan)
                                ->orWhereMonth('tanggal_tagihan', $bulan);
                        });
                    });
            })
            ->get();

        // Tambahin flag status_menunggak
        $transaksi = $transaksi->map(function ($t) use ($transaksi) {
            $t->status_menunggak = $t->created_at->addDays(30)->lt(now()) && $t->status !== 'settlement';
            return $t;
        });

        // Warga yang punya tagihan (unik berdasarkan NIK)
        $wargaDenganTagihan = $transaksi->pluck('tagihan.warga.NIK')->unique()->count();

        // Pisahkan transaksi Tetap dan Tidak Tetap
        $transaksiTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'tetap';
        });

        $transaksiTidakTetap = $transaksi->filter(function ($t) {
            return $t->tagihan && $t->tagihan->jenis_retribusi === 'retasi';
        });

        // Statistik
        $sudahBayar = $transaksi->where('status', 'settlement')->count();
        $belumBayar = $transaksi->where('status', 'pending')->count();
        $menunggak = $transaksi->where('status_menunggak', true)->count();
        $totalPembayaran = $transaksi->where('status', 'settlement')->sum('amount');

        // Transaksi settlement global (semua yang sudah dirilis / punya tagihan)
        $totalTransaksi = $transaksi->count();

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
        
        // Hitung tunggakan
        $tunggakan = $this->hitungTunggakan($transaksi);

        return view('dashboard', compact(
            'jumlahWarga',
            'jumlahRetribusiTetap',
            'jumlahRetribusiTidakTetap',
            'jumlahPetugas',
            'role',
            'bulan',
            'tahun',
            'wargaDenganTagihan',
            'sudahBayar',
            'belumBayar',
            'menunggak',
            'tunggakan',
            'totalPembayaran',
            'totalTransaksi',
            'transaksiTetap',
            'transaksiTidakTetap'
        ));
    }




    public function warga()
    {
        return view(
            'warga.index',
            ['warga' => Auth::user()->warga->jenis_retribusi],
            ['nama' => Auth::user()->warga->pengguna->nama]
        );
    }
}