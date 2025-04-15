<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LogAktivitasController;
use App\Models\Kelurahan;

// Halaman utama
Route::view('/', 'index');

// Halaman login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Proses login & logout
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'admin'])->name('admin.index');
    // akses halaman Kelola WR
    Route::get('/admin/logaktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
});

// Kepala Dinas
Route::middleware(['auth', 'role:kepala_dinas'])->group(function () {
    Route::get('/kepala-dinas/dashboard', [AuthController::class, 'kepaladinas'])->name('kepala_dinas.index');
    Route::get('/kepala-dinas/daftarTagihan', [TagihanController::class, 'daftarTagihan'])->name('kepala_dinas.tagihan');
    Route::get('/kepala-dinas/grafik', [TagihanController::class, 'grafik'])->name('kepala_dinas.grafik');
    Route::post('/kepala-dinas/tagihan/setujui', [TagihanController::class, 'setujuiTagihan'])->name('kepala_dinas.tagihan.setujui');
});

// Keuangan
Route::middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/keuangan/dashboard', [AuthController::class, 'keuangan'])->name('keuangan.index');
    Route::get('/keuangan/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/keuangan/laporan-keuangan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::get('/keuangan/transaksi/laporan-keuangan/cetak-laporan', [TransaksiController::class, 'cetakLaporan'])->name('transaksi.cetak');
    Route::post('/keuangan/transaksi/{id}/sendReminder', [TransaksiController::class, 'sendReminder'])->name('transaksi.sendReminder');

});

// Pendataan
Route::middleware(['auth', 'role:pendataan'])->group(function () {
    // akses halaman dashboard
    Route::get('/pendataan/dashboard', [AuthController::class, 'pendataan'])->name('pendataan.index');

    // Akses halaman Kelola Warga
    Route::get('/pendataan/datawarga', [DataWargaController::class, 'index'])->name('datawarga.index');
    Route::get('/pendataan/datawarga/create', [DataWargaController::class, 'create'])->name('datawarga.create');
    Route::post('/pendataan/datawarga', [DataWargaController::class, 'store'])->name('datawarga.store');

    Route::get('/pendataan/datawarga/{NIK}/edit', [DataWargaController::class, 'edit'])->name('datawarga.edit');
    Route::put('/pendataan/datawarga/{NIK}', [DataWargaController::class, 'update'])->name('datawarga.update');

    Route::delete('/pendataan/datawarga/{warga}', [DataWargaController::class, 'destroy'])->name('datawarga.destroy');
    Route::get('/get-kelurahan', function (Request $request) {
        $kelurahan = Kelurahan::where('kecamatan_id', $request->kecamatan_id)->get();
        return response()->json($kelurahan);
    });

    // Akses Kelola Tagihan
    // Index dan Create untuk Tagihan Tetap
    Route::get('/pendataan/kelolatagihan/tetap', [TagihanController::class, 'indexTetap'])->name('tagihan.index.tetap');
    Route::get('/pendataan/kelolatagihan/tetap/create', [TagihanController::class, 'createTetap'])->name('tagihan.create.tetap');
    Route::post('/pendataan/kelolatagihan/generate-tetap', [TagihanController::class, 'generateTetap'])->name('tagihan.generate.tetap');
    Route::post('/pendataan/kelolatagihan/ajukan', [TagihanController::class, 'ajukanTagihan'])->name('tagihan.ajukan');


    // Index dan Create untuk Tagihan Tidak Tetap
    Route::get('/pendataan/kelolatagihan/tidak_tetap', [TagihanController::class, 'indexTidakTetap'])->name('tagihan.index.tidak_tetap');
    Route::get('/pendataan/kelolatagihan/tidak_tetap/create', [TagihanController::class, 'createTidakTetap'])->name('tagihan.create.tidak_tetap');

    // Store
    Route::post('/pendataan/kelolatagihan/tetap', [TagihanController::class, 'storeTetap'])->name('tagihan.store.tetap');
    Route::post('/pendataan/kelolatagihan/tidak_tetap', [TagihanController::class, 'storeTidakTetap'])->name('tagihan.store.tidak_tetap');

    // Edit update hapus
    Route::get('/pendataan/kelolatagihan/{id}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
    Route::put('/pendataan/kelolatagihan/{id}', [TagihanController::class, 'update'])->name('tagihan.update');
    Route::delete('/pendataan/kelolatagihan/{id}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');

});

// Warga
Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/warga/dashboard', [AuthController::class, 'warga'])->name('warga.index');
    Route::get('/warga/riwayat-transaksi', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::post('/warga/transaksi/cek-status/{order_id}', [TransaksiController::class, 'cekStatus'])->name('transaksi.cekStatus');
});

// route handle webhook ada di /routes/api.php

// Test fonnte
Route::get('/kirim-whatsapp', function () {
    $apiKey = 'mb5Vs3fJcZpJFj7ePNq6'; // Ganti dengan API Key Fonnte kamu
    $nohp = '089515946334'; // Ganti dengan nomor tujuan
    $pesan = 'Halo, ini pesan dari Laravel!';

    $response = Http::withHeaders([
        'Authorization' => $apiKey,
    ])->post('https://api.fonnte.com/send', [
                'target' => $nohp,
                'message' => $pesan,
            ]);

    return $response->json();
});
